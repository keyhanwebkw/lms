<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        [$startToday, $endToday] = [$now->copy()->startOfDay(), $now->copy()->endOfDay()];
        [$start7, $end7] = [$now->copy()->subDays(6)->startOfDay(), $endToday->copy()];
        [$start30, $end30] = [$now->copy()->subDays(29)->startOfDay(), $endToday->copy()];

        [$tS, $tE] = [$startToday->timestamp, $endToday->timestamp];
        [$wS, $wE] = [$start7->timestamp, $end7->timestamp];
        [$mS, $mE] = [$start30->timestamp, $end30->timestamp];

        $kpiToday = User::whereBetween('registerDate', [$tS, $tE])->count();
        $kpi7 = User::whereBetween('registerDate', [$wS, $wE])->count();
        $kpi30 = User::whereBetween('registerDate', [$mS, $mE])->count();

        $windows = [5, 15, 60, 240];
        $nowTs = Carbon::now()->timestamp;
        $active = [];
        foreach ($windows as $m) {
            $threshold = $nowTs - ($m * 60);
            $active["m{$m}"] = User::where('lastActivity', '>=', $threshold)->count();
        }

        $hourlyLabels = [];
        $hourlyCounts = [];
        $end = Carbon::now()->minute(59)->second(59);
        $start = $end->copy()->subHours(23)->minute(0)->second(0);

        $cursor = $start->copy();
        while ($cursor->lessThanOrEqualTo($end)) {
            $slotStart = $cursor->copy()->minute(0)->second(0);
            $slotEnd = $cursor->copy()->minute(59)->second(59);
            $sTs = $slotStart->timestamp;
            $eTs = $slotEnd->timestamp;

            $hourlyLabels[] = $slotStart->format('H'); // "00".."23"
            $hourlyCounts[] = User::whereBetween('lastActivity', [$sTs, $eTs])->count();

            $cursor->addHour();
        }

        $monthLabels = [];
        $monthCounts = [];
        $nowStart = Carbon::now()->startOfMonth();
        for ($i = 11; $i >= 0; $i--) {
            $ms = $nowStart->copy()->subMonths($i)->startOfMonth();
            $me = $ms->copy()->endOfMonth()->endOfDay();
            $msTs = $ms->timestamp;
            $meTs = $me->timestamp;

            $monthCounts[] = User::whereBetween('registerDate', [$msTs, $meTs])->count();
            $monthLabels[] = (new Verta($ms))->format('%B %Y');
        }

        return view('admin.dashboard', [
            'kpiToday' => $kpiToday,
            'kpi7' => $kpi7,
            'kpi30' => $kpi30,
            'active' => $active,
            'hourlyLabels' => $hourlyLabels,
            'hourlyCounts' => $hourlyCounts,
            'monthLabels' => $monthLabels,
            'monthCounts' => $monthCounts,
        ]);
    }
}
