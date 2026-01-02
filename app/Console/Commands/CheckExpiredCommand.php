<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\RegisteredPerson;
use App\Models\User;
use App\Services\VaultSiteService;

class CheckExpiredCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registerperson:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user expired';

    /**
     * Execute the console command.
     */
    public function __construct(
        protected VaultSiteService $vaultSiteService
    ) {
        parent::__construct(); // ← WAJIB
    }

    public function handle()
    {
        $today = Carbon::now();

        $records = RegisteredPerson::where('is_employee', 0)
            ->where('is_deleted_card', 0)
            ->where('expired_at', '<', $today)
            ->get();

        $user_ids = $records->pluck('user_id')->toArray();
        foreach ($records as $record) {

            // 👉 Aksi ketika expired
            // Contoh: tandai kartu telah dihapus
            $this->vaultSiteService->deleteFaceCard($record->user->id_card_number);
            $this->vaultSiteService->deleteCard($record->user->id_card_number);
            $record->update([
                'is_deleted_card' => 1,
                'status' => 'Expired',
                'status_level' => 3,
            ]);

            // Kamu bisa juga menambahkan log, notifikasi, dsb.
            // Log::info("Card expired for ID: {$record->id}");
        }

        User::whereIn('id', $user_ids)->update([
            'is_registered' => 0,
            'status' => 'inactive',
        ]);

        $this->info("Expired RegisterPerson checked: " . $records->count());

    }
}
