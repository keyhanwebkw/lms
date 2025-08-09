@extends('subsystem::layouts.app')

@section('content')
    <style>
        /* کارت شیشه‌ای پررنگ (اپلی) */
        .glass {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(255, 255, 255, .28), rgba(255, 255, 255, .14));
            border: 1px solid rgba(255, 255, 255, .45);
            box-shadow: 0 28px 56px rgba(0, 0, 0, .22), inset 0 1px 0 rgba(255, 255, 255, .6);
            backdrop-filter: saturate(180%) blur(22px);
            -webkit-backdrop-filter: saturate(180%) blur(22px);
        }

        .glass .card-header {
            background: linear-gradient(180deg, rgba(255, 255, 255, .25), rgba(255, 255, 255, .10));
            border-bottom: 1px solid rgba(255, 255, 255, .35);
        }

        .glass .card-body {
            position: relative;
        }

        /* لایه خیلی لطیف برای خوانایی روی گلس */
        .glass .card-body::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(255, 255, 255, .05), transparent 30%, transparent 70%, rgba(255, 255, 255, .05));
        }

        /* کارت KPI کمی ساده‌تر ولی همچنان گلس */
        .glass-soft {
            background: linear-gradient(135deg, rgba(255, 255, 255, .30), rgba(255, 255, 255, .16));
            border: 1px solid rgba(255, 255, 255, .38);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 18px;
            box-shadow: 0 18px 36px rgba(0, 0, 0, .16);
        }

        /* KPI ها */
        .metric {
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .metric .label {
            color: #334155;
            font-size: .9rem;
            margin-bottom: .25rem;
            opacity: .9
        }

        .metric .value {
            font-size: 1.7rem;
            font-weight: 800;
            color: #0f172a
        }

        .metric .sub {
            font-size: .85rem;
            color: #6b7280
        }

        .metric .icon {
            opacity: .95
        }
    </style>

    <div class="row g-3">
        <div class="col-xl-4 col-md-6">
            <div class="glass-soft">
                <div class="metric">
                    <div>
                        <div class="label">ثبت‌نام امروز</div>
                        <div class="value">{{ number_format($kpiToday) }}</div>
                    </div>
                    <i class="fa-solid fa-user-plus fa-xl icon text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="glass-soft">
                <div class="metric">
                    <div>
                        <div class="label">ثبت‌نام ۷ روز اخیر</div>
                        <div class="value">{{ number_format($kpi7) }}</div>
                    </div>
                    <i class="fa-solid fa-calendar-week fa-xl icon text-info"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="glass-soft">
                <div class="metric">
                    <div>
                        <div class="label">ثبت‌نام ۳۰ روز اخیر</div>
                        <div class="value">{{ number_format($kpi30) }}</div>
                    </div>
                    <i class="fa-solid fa-calendar-days fa-xl icon text-indigo"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <!-- چارت اصلی: حضور ساعتی 24 ساعت اخیر -->
        <div class="col-xl-8">
            <div class="card glass">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">حضور ساعتی کاربران (۲۴ ساعت اخیر)</h5>
                </div>
                <div class="card-body" style="height: 460px;">
                    <canvas id="hourlyPresence"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card glass">
                <div class="card-header">
                    <h6 class="mb-0">ثبت‌نام ۱۲ ماه اخیر</h6>
                </div>
                <div class="card-body" style="height: 460px;">
                    <canvas id="monthlySignup"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/chart.js') }}"></script>
    <script>
        (function () {
            const hourlyLabels = @json($hourlyLabels ?? []);
            const hourlyCounts = @json($hourlyCounts ?? []);
            const monthLabels = @json($monthLabels ?? []);
            const monthCounts = @json($monthCounts ?? []);
            const fa = (n) => Number(n ?? 0).toLocaleString('fa-IR');

            const el1 = document.getElementById('hourlyPresence');
            if (el1) {
                const ctx1 = el1.getContext('2d');

                const fillGrad = ctx1.createLinearGradient(0, 0, 0, el1.height);
                fillGrad.addColorStop(0, 'rgba(59,130,246,.35)');   // آبی
                fillGrad.addColorStop(0.6, 'rgba(99,102,241,.20)'); // ایندیگو
                fillGrad.addColorStop(1, 'rgba(59,130,246,0)');

                const shadowLine = {
                    id: 'shadowLine',
                    beforeDatasetsDraw(chart) {
                        const {ctx} = chart;
                        const meta = chart.getDatasetMeta(0);
                        if (!meta || !meta.data) return;
                        ctx.save();
                        ctx.shadowColor = 'rgba(59,130,246,.45)';
                        ctx.shadowBlur = 12;
                        ctx.lineWidth = 3;
                        ctx.strokeStyle = 'rgba(59,130,246,.95)';
                        ctx.beginPath();
                        meta.data.forEach((pt, i) => {
                            i === 0 ? ctx.moveTo(pt.x, pt.y) : ctx.lineTo(pt.x, pt.y)
                        });
                        ctx.stroke();
                        ctx.restore();
                    }
                };

                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: hourlyLabels,
                        datasets: [{
                            label: 'حضور ساعتی',
                            data: hourlyCounts,
                            borderColor: 'rgba(59,130,246,0)',
                            pointBackgroundColor: 'rgba(59,130,246,1)',
                            pointBorderColor: 'rgba(255,255,255,.85)',
                            pointBorderWidth: 1.5,
                            pointRadius: 3,
                            pointHoverRadius: 4,
                            tension: 0.35,
                            fill: true,
                            backgroundColor: fillGrad
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, locale: 'fa-IR',
                        plugins: {
                            legend: {display: false},
                            tooltip: {
                                backgroundColor: 'rgba(17,24,39,.9)',
                                borderColor: 'rgba(255,255,255,.18)', borderWidth: 1, padding: 10,
                                titleColor: '#fff', bodyColor: '#e5e7eb',
                                callbacks: {
                                    title: (items) => 'ساعت ' + (items?.[0]?.label ?? ''),
                                    label: (ctx) => ' ' + fa(ctx.parsed.y ?? 0) + ' نفر'
                                },
                                mode: 'index', intersect: false
                            }
                        },
                        scales: {
                            x: {grid: {display: false}, ticks: {font: {family: 'Vazir'}}},
                            y: {
                                beginAtZero: true,
                                grid: {color: 'rgba(0,0,0,.08)', drawBorder: false},
                                ticks: {callback: (v) => fa(v)}
                            }
                        },
                        interaction: {mode: 'index', intersect: false}
                    },
                    plugins: [shadowLine]
                });
            }

            const el2 = document.getElementById('monthlySignup');
            if (el2) {
                const ctx2 = el2.getContext('2d');
                const maxVal = Math.max.apply(null, monthCounts.length ? monthCounts : [0]);
                const maxIdx = monthCounts.indexOf(maxVal);
                const barBg = monthCounts.map((v, i) => i === maxIdx ? 'rgba(236,72,153,.30)' : 'rgba(56,189,248,.22)');
                const barBorder = monthCounts.map((v, i) => i === maxIdx ? 'rgba(236,72,153,.95)' : 'rgba(56,189,248,.9)');

                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: monthLabels,
                        datasets: [{
                            label: 'ثبت‌نام',
                            data: monthCounts,
                            backgroundColor: barBg,
                            borderColor: barBorder,
                            borderWidth: 1.5,
                            borderRadius: 10,
                            maxBarThickness: 34,
                            categoryPercentage: .72,
                            barPercentage: .9
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, locale: 'fa-IR',
                        plugins: {
                            legend: {display: false},
                            tooltip: {callbacks: {label: (c) => ' ' + fa(c.parsed.y ?? 0) + ' نفر'}}
                        },
                        scales: {
                            x: {grid: {display: false}, ticks: {font: {family: 'Vazir'}}},
                            y: {
                                beginAtZero: true,
                                grid: {color: 'rgba(0,0,0,.08)', drawBorder: false},
                                ticks: {callback: (v) => fa(v)}
                            }
                        }
                    }
                });
            }
        })();
    </script>
@endsection
