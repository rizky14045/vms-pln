<?php

namespace App\Services;

use App\Helper\ResponseHelper;
use App\Models\RegisteredPerson;
use App\Models\User;
use App\Models\VisitorCard;
use App\Models\VisitorCardHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class VisitorCardService
{
    protected $vaultSiteService;

    public function __construct(VaultSiteService $vaultSiteService)
    {
        $this->vaultSiteService = $vaultSiteService;
    }

    public function getAllCards(array $filters = [])
    {
        try {
            $query = VisitorCard::query();

            if (!empty($filters['search'])) {
                $query->where('card_number', 'like', '%' . $filters['search'] . '%');
            }

            if (isset($filters['status']) && $filters['status'] !== '') {
                $query->where('status', $filters['status']);
            }

            $cards = $query->orderBy('card_number')->get();

            return ResponseHelper::successServiceResponse('Get all visitor cards success', $cards);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get all visitor cards failed', $e->getMessage());
        }
    }

    // Kartu yang boleh dipilih saat approve visitor: hanya yang berstatus aktif/tersedia (1)
    public function getAvailableCards()
    {
        return VisitorCard::where('status', 1)->orderBy('card_number')->get();
    }

    // Kartu yang sedang dipakai (status 0), untuk fitur "Kembalikan Kartu"
    public function getInUseCards()
    {
        return VisitorCard::where('status', 0)->orderBy('card_number')->get();
    }

    public function getCardById(int $id)
    {
        try {
            $card = VisitorCard::find($id);

            if (!$card) {
                return ResponseHelper::errorServiceResponse(404, 'Visitor card not found');
            }

            return ResponseHelper::successServiceResponse('Get visitor card success', $card);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get visitor card failed', $e->getMessage());
        }
    }

    public function createCard(array $data)
    {
        try {
            $card = VisitorCard::create([
                'card_number' => $data['card_number'] ?? null,
                'status' => $data['status'] ?? 1,
            ]);

            return ResponseHelper::successServiceResponse('Create visitor card success', $card);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Create visitor card failed', $e->getMessage());
        }
    }

    public function updateCard(int $id, array $data)
    {
        try {
            $card = VisitorCard::findOrFail($id);

            $card->update([
                'card_number' => $data['card_number'] ?? $card->card_number,
                'status' => $data['status'] ?? $card->status,
            ]);

            return ResponseHelper::successServiceResponse('Update visitor card success', $card);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Update visitor card failed', $e->getMessage());
        }
    }

    /**
     * Pinjamkan (assign) beberapa kartu ke satu registered person visitor.
     * Kartu yang bisa dipilih hanya yang berstatus aktif/tersedia (1).
     * Setiap kartu yang berhasil di-assign: status -> 0, dan dicatat ke history.
     */
    public function assignCardsToVisitor(array $cardIds, int $registeredPersonId)
    {
        try {
            $registeredPerson = RegisteredPerson::find($registeredPersonId);

            if (!$registeredPerson) {
                return ResponseHelper::errorServiceResponse(404, 'Registered person not found');
            }

            $cardIds = array_values(array_unique($cardIds));

            // Lock baris kartu supaya tidak ada admin lain yang bisa "rebutan" kartu yang sama
            // di saat bersamaan (race condition), sekaligus dibungkus transaction supaya
            // kalau ada yang gagal di tengah jalan, semua perubahan ikut di-revert (atomic).
            DB::transaction(function () use ($cardIds, $registeredPerson) {
                $cards = VisitorCard::whereIn('id', $cardIds)->where('status', 1)->lockForUpdate()->get();

                if ($cards->count() !== count($cardIds)) {
                    throw new Exception('Beberapa kartu yang dipilih sudah tidak tersedia.');
                }

                foreach ($cards as $card) {
                    $card->update(['status' => 0]);

                    VisitorCardHistory::create([
                        'visitor_card_id' => $card->id,
                        'registered_person_id' => $registeredPerson->id,
                        'user_id' => $registeredPerson->user_id,
                        'borrowed_at' => now(),
                        'returned_at' => null,
                    ]);
                }
            });

            $cards = VisitorCard::whereIn('id', $cardIds)->get();

            return ResponseHelper::successServiceResponse('Assign visitor cards success', $cards);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Assign visitor cards failed', $e->getMessage());
        }
    }

    /**
     * Kembalikan beberapa kartu: status -> 1 (aktif/tersedia lagi), dan tutup history-nya
     * (isi returned_at pada history yang masih terbuka untuk kartu tersebut).
     */
    public function returnCards(array $cardIds)
    {
        try {
            $usersToRevokeVaultAccess = [];

            DB::transaction(function () use ($cardIds, &$usersToRevokeVaultAccess) {
                $cards = VisitorCard::whereIn('id', $cardIds)->where('status', 0)->lockForUpdate()->get();
                $affectedRegisteredPersonIds = [];

                foreach ($cards as $card) {
                    $card->update(['status' => 1]);

                    $openHistory = VisitorCardHistory::where('visitor_card_id', $card->id)
                        ->whereNull('returned_at')
                        ->latest('borrowed_at')
                        ->first();

                    if ($openHistory) {
                        $openHistory->update(['returned_at' => now()]);

                        if ($openHistory->registered_person_id) {
                            $affectedRegisteredPersonIds[] = $openHistory->registered_person_id;
                        }
                    }
                }

                // Untuk tiap registrasi visitor yang baru saja mengembalikan kartu,
                // cek apakah SEMUA kartu miliknya (dari registrasi manapun) sudah
                // kembali. Kalau sudah, tandai selesai + nonaktifkan usernya, dan
                // kumpulkan usernya untuk dicabut akses vault-nya SETELAH transaction
                // ini commit (supaya panggilan API eksternal tidak menahan DB lock,
                // dan gagal/lambatnya API vault tidak sampai me-rollback data kartu).
                foreach (array_unique($affectedRegisteredPersonIds) as $registeredPersonId) {
                    $user = $this->markVisitCompletedIfAllCardsReturned($registeredPersonId);
                    if ($user) {
                        $usersToRevokeVaultAccess[$user->id] = $user;
                    }
                }
            });

            foreach ($usersToRevokeVaultAccess as $user) {
                if (!empty($user->id_card_number)) {
                    $this->vaultSiteService->deleteFaceCard($user->id_card_number);
                    $this->vaultSiteService->deleteCard($user->id_card_number);
                }
            }

            $cards = VisitorCard::whereIn('id', $cardIds)->get();

            return ResponseHelper::successServiceResponse('Return visitor cards success', $cards);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Return visitor cards failed', $e->getMessage());
        }
    }

    /**
     * Kalau registered person ini statusnya masih Approved DAN user-nya sudah tidak
     * memegang kartu apapun lagi (dari registrasi manapun), tandai kunjungan selesai
     * dan nonaktifkan user-nya. Dicek per user (bukan cuma per registrasi), supaya
     * user yang kebetulan pegang kartu dari lebih dari satu registrasi tidak
     * dinonaktifkan padahal masih ada kartu lain yang belum dikembalikan.
     *
     * Return User yang baru dinonaktifkan (supaya bisa dicabut akses vault-nya oleh
     * pemanggil), atau null kalau tidak ada perubahan.
     */
    protected function markVisitCompletedIfAllCardsReturned(int $registeredPersonId): ?User
    {
        $registeredPerson = RegisteredPerson::find($registeredPersonId);

        if (!$registeredPerson || !$registeredPerson->user_id) {
            return null;
        }

        $stillHoldingAnyCard = VisitorCardHistory::where('user_id', $registeredPerson->user_id)
            ->whereNull('returned_at')
            ->exists();

        if ($stillHoldingAnyCard) {
            return null;
        }

        // Hanya timpa status kalau memang masih "Approved", supaya tidak menimpa
        // status lain seperti Rejected/Expired/Deleted kalau kebetulan berbarengan.
        if ($registeredPerson->status_level == 2) {
            $registeredPerson->update([
                'status' => 'Kartu Dikembalikan',
                'status_level' => 5,
            ]);
        }

        $user = User::find($registeredPerson->user_id);
        if (!$user) {
            return null;
        }

        $user->update([
            'is_registered' => 0,
            'status' => 'inactive',
        ]);

        return $user;
    }

    /**
     * Ambil kartu-kartu yang masih dipegang (belum dikembalikan) oleh satu user tertentu.
     *
     * PENTING: query ini di-scope ke user_id + returned_at IS NULL pada baris history-nya
     * sendiri (bukan ke status kartu saat ini). Jadi kalau user A pernah pegang kartu #1
     * lalu mengembalikannya, dan kartu #1 itu sekarang dipinjam user B, baris history milik
     * user A untuk kartu #1 sudah closed (returned_at terisi) dan TIDAK akan ikut kehitung
     * di sini — walaupun kartu #1 saat ini berstatus "dipakai" (oleh user B, bukan user A).
     */
    public function getUnreturnedCardsForUser(int $userId)
    {
        return VisitorCardHistory::with('visitorCard')
            ->where('user_id', $userId)
            ->whereNull('returned_at')
            ->get();
    }

    public function getAllHistories(array $filters = [])
    {
        try {
            $query = VisitorCardHistory::with(['visitorCard', 'user']);

            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $query->whereBetween('borrowed_at', [
                    Carbon::parse($filters['start_date'])->startOfDay(),
                    Carbon::parse($filters['end_date'])->endOfDay(),
                ]);
            } elseif (!empty($filters['start_date'])) {
                $query->where('borrowed_at', '>=', Carbon::parse($filters['start_date'])->startOfDay());
            } elseif (!empty($filters['end_date'])) {
                $query->where('borrowed_at', '<=', Carbon::parse($filters['end_date'])->endOfDay());
            }

            $histories = $query->orderByDesc('borrowed_at')->get();

            return ResponseHelper::successServiceResponse('Get all visitor card histories success', $histories);
        } catch (Exception $e) {
            return ResponseHelper::errorServiceResponse(500, 'Get all visitor card histories failed', $e->getMessage());
        }
    }
}
