<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $now = Carbon::now();

        $startToday = $now->copy()->startOfDay();
        $endToday = $now->copy()->endOfDay();

        $start7d = $now->copy()->subDays(6)->startOfDay();
        $start30d = $now->copy()->subDays(29)->startOfDay();

        $start12m = $now->copy()->startOfMonth()->subMonths(11);
        $end12m = $now->copy()->endOfMonth()->endOfDay();

        $start24h = $now->copy()->subHours(23)->minute(0)->second(0);
        $end24h = $now->copy()->minute(59)->second(59);

        $users12m = User::query()
            ->whereNull('deleted')
            ->whereBetween('registerDate', [$start12m->timestamp, $end12m->timestamp])
            ->get(['registerDate']);

        $kpiToday = $users12m->whereBetween('registerDate', [$startToday->timestamp, $endToday->timestamp])->count();
        $kpi7 = $users12m->where('registerDate', '>=', $start7d->timestamp)->count();
        $kpi30 = $users12m->where('registerDate', '>=', $start30d->timestamp)->count();

        $monthLabels = [];
        $monthCounts = [];
        $monthKeys = [];

        $cursor = $start12m->copy();
        for ($i = 0; $i < 12; $i++) {
            $key = $cursor->format('Y-m');
            $monthKeys[] = $key;
            $monthLabels[] = (new Verta($cursor))->format('%B %Y');
            $monthCounts[] = 0;
            $cursor->addMonth();
        }

        $users12m->each(function ($u) use (&$monthCounts, $monthKeys) {
            $ts = (int)$u->registerDate;
            $ym = Carbon::createFromTimestamp($ts)->format('Y-m');
            $idx = array_search($ym, $monthKeys, true);
            if ($idx !== false) $monthCounts[$idx]++;
        });

        $acts24h = User::query()
            ->whereNull('deleted')
            ->whereBetween('lastActivity', [$start24h->timestamp, $end24h->timestamp])
            ->get(['lastActivity']);

        $hourlyLabels = [];
        $hourlyCounts = [];
        $hourKeys = [];

        $hCursor = $start24h->copy();
        for ($i = 0; $i < 24; $i++) {
            $key = $hCursor->format('Y-m-d H:00:00');
            $hourKeys[] = $key;
            $hourlyLabels[] = $hCursor->format('H'); // 00..23
            $hourlyCounts[] = 0;
            $hCursor->addHour();
        }

        $acts24h->each(function ($u) use (&$hourlyCounts, $hourKeys) {
            $ts = (int)$u->lastActivity;
            $slot = Carbon::createFromTimestamp($ts)->minute(0)->second(0)->format('Y-m-d H:00:00');
            $idx = array_search($slot, $hourKeys, true);
            if ($idx !== false) $hourlyCounts[$idx]++;
        });

        return view('admin.dashboard', [
            'kpiToday' => $kpiToday,
            'kpi7' => $kpi7,
            'kpi30' => $kpi30,
            'hourlyLabels' => $hourlyLabels,
            'hourlyCounts' => $hourlyCounts,
            'monthLabels' => $monthLabels,
            'monthCounts' => $monthCounts,
        ]);
    }
}
