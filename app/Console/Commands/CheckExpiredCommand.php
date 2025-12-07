<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\RegisteredPerson;
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
            ->where('expired_at', '<=', $today)
            ->get();

        foreach ($records as $record) {

            // 👉 Aksi ketika expired
            // Contoh: tandai kartu telah dihapus
            $this->vaultSiteService->deleteCard($record->user->id_card_number);
            $record->update([
                'is_deleted_card' => 1,
            ]);

            // Kamu bisa juga menambahkan log, notifikasi, dsb.
            // Log::info("Card expired for ID: {$record->id}");
        }

        $this->info("Expired RegisterPerson checked: " . $records->count());

    }
}
