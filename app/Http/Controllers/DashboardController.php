<?php

namespace App\Http\Controllers;

use DOMXPath;
use Exception;
use DOMDocument;
use Carbon\Carbon;
use App\Models\User;
use SimpleXMLElement;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\RegisteredPerson;
use App\Models\TransactionHistory;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(): View {

        // =============================
        // DUMMY DATA FOR DASHBOARD
        // =============================

        // Visitor summary
        $totalVisitor = RegisteredPerson::select('user_id')->distinct()->count();
        $totalKaryawan = User::where(['is_employee' => 1, 'status' => "active"])->count();

        // External visitor
        $totalVisitorExternal = RegisteredPerson::where('is_employee', false)
                            ->select('user_id')
                            ->distinct()
                            ->count();
        $pendingExternal  = RegisteredPerson::where('status_level',1)->where('is_employee',false)->count();
        $approvedExternal = RegisteredPerson::where('status_level', 2)
                            ->where('is_employee', false)
                            ->whereDate('expired_at', '>', today())
                            ->distinct('user_id')
                            ->count('user_id');

        // Internal visitor
        $totalVisitorInternal = RegisteredPerson::where('is_employee', true)
                            ->select('user_id')
                            ->distinct()
                            ->count();
        $pendingInternal  = RegisteredPerson::where('status_level',1)->where('is_employee',true)->count();
        $approvedInternal = RegisteredPerson::where('status_level', 2)
                            ->where('is_employee', true)
                            ->distinct('user_id')
                            ->count('user_id');

        // Chart monthly visitor
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei',
            'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
            'November', 'Desember'
        ];
        $totalPermonth = RegisteredPerson::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');
        $visitorPerMonthByMonthNumber = []; // Gunakan nama variabel sementara yang lebih deskriptif

        // Mengisi data untuk 12 bulan
        for ($i = 1; $i <= 12; $i++) {
            $visitorPerMonthByMonthNumber[$i] = $totalPermonth[$i] ?? 0;
        }

        // **Bagian Penting:** Ubah array asosiatif (dengan key 1-12) menjadi array nilai berurutan.
        $visitorPerMonth = array_values($visitorPerMonthByMonthNumber);

        // Popular visit area
        $popularVisit = TransactionHistory::whereDate('tr_date', today())
            ->select('door_name')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('door_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // format untuk Chart.js
        $areaNames = $popularVisit->pluck('door_name');
        $areaCounts = $popularVisit->pluck('total');


        // Latest visitors
        $latestVisitors = TransactionHistory::orderByDesc('tr_date')
            ->orderByDesc('tr_time')
            ->limit(5)->get();

        // RETURN TO BLADE
        return view('dashboard', compact(
            'totalVisitor',
            'totalKaryawan',
            'totalVisitorExternal',
            'totalVisitorInternal',

            'pendingExternal',
            'approvedExternal',

            'pendingInternal',
            'approvedInternal',

            'months',
            'visitorPerMonth',

            'areaNames',
            'areaCounts',

            'latestVisitors'
        ));
    }
}
