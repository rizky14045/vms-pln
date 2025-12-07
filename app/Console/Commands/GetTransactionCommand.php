<?php

namespace App\Console\Commands;

use App\Models\ApiHitLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\RegisteredPerson;
use App\Models\TransactionHistory;
use App\Services\VaultSiteService;

class GetTransactionCommand extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get user transaction';

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

        $dateFrom = $today->format("Y/m/d");
        $dateTo = $today->copy()->addDay()->format("Y/m/d");

        // Ambil log
        $log = ApiHitLog::first();

        // Jika belum ada log → hit API tanpa dateFrom
        if (!$log) {
            $transactions = $this->vaultSiteService->getTransaction(null, $dateTo);
            $this->bulkInsertTransactions($transactions);

            // Simpan log
            ApiHitLog::create([
                'last_hit' => now()
            ]);

            return;
        }

        // Jika log sudah ada
        $transactions = $this->vaultSiteService->getTransaction($dateFrom, $dateTo);

        if (!$transactions || count($transactions) === 0) {
            return;
        }

        $lastHit = Carbon::parse($log->last_hit);

        // Filter → hanya ambil record yang TRDate+TRTime lebih besar dari last_hit
        $newData = [];

        foreach ($transactions as $trx) {
            $trxTimestamp = Carbon::parse($trx['TrDate'].' '.$trx['TrTime']);

            if ($trxTimestamp->greaterThan($lastHit)) {
                $newData[] = [
                    'tr_date'     => $trx['TrDate'],
                    'tr_time'     => $trx['TrTime'],
                    'card_no'     => $trx['CardNo'],
                    'transaction' => $trx['Transaction'],
                    'tr_code'     => $trx['TrCode'],
                    'door_name'   => $trx['DoorName'],
                    'card_name'   => $trx['CardName'],
                    'department'  => $trx['Department'],
                    'staff_no'    => $trx['StaffNo'],
                    'nric'        => $trx['Nric'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        // Jika ada data baru → insert sekaligus
        if (count($newData) > 0) {
            TransactionHistory::insert($newData);

            // update last_hit
            $log->update([
                'last_hit' => now(),
            ]);
        }
    }

    private function bulkInsertTransactions($transactions)
    {
        if (!$transactions || count($transactions) === 0) return;

        $data = [];

        foreach ($transactions as $trx) {
            $data[] = [
                'tr_date'     => $trx['TrDate'],
                'tr_time'     => $trx['TrTime'],
                'card_no'     => $trx['CardNo'],
                'transaction' => $trx['Transaction'],
                'tr_code'     => $trx['TrCode'],
                'door_name'   => $trx['DoorName'],
                'card_name'   => $trx['CardName'],
                'department'  => $trx['Department'],
                'staff_no'    => $trx['StaffNo'],
                'nric'        => $trx['Nric'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        TransactionHistory::insert($data);
    }


}
