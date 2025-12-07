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
        $totalVisitor = RegisteredPerson::count();;
        $totalKaryawan = User::where('is_employee',1)->count();

        // External visitor
        $totalVisitorExternal = RegisteredPerson::whereDate('created_at',today())->where('is_employee',false)->count();
        $pendingExternal  = RegisteredPerson::where('status_level',1)->whereDate('created_at',today())->where('is_employee',false)->count();
        $approvedExternal = RegisteredPerson::where('status_level',2)->whereDate('created_at',today())->where('is_employee',false)->count();
        $rejectedExternal = RegisteredPerson::where('status_level',0)->whereDate('created_at',today())->where('is_employee',false)->count();

        // Internal visitor
        $totalVisitorInternal = RegisteredPerson::whereDate('created_at',today())->where('is_employee',1)->count();
        $pendingInternal  = RegisteredPerson::where('status_level',1)->whereDate('created_at',today())->where('is_employee',1)->count();
        $approvedInternal = RegisteredPerson::where('status_level',2)->whereDate('created_at',today())->where('is_employee',1)->count();
        $rejectedInternal = RegisteredPerson::where('status_level',0)->whereDate('created_at',today())->where('is_employee',1)->count();

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
            'rejectedExternal',

            'pendingInternal',
            'approvedInternal',
            'rejectedInternal',

            'months',
            'visitorPerMonth',

            'areaNames',
            'areaCounts',

            'latestVisitors'
        ));
    }
}
