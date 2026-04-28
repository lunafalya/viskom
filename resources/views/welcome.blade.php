<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-primary": "#b2c5ff",
                        "on-surface": "#041b3c",
                        "on-secondary-container": "#fffbff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container": "#e8edff",
                        "background": "#f9f9ff",
                        "secondary": "#b6171e",
                        "error-container": "#ffdad6",
                        "on-secondary-fixed": "#410003",
                        "on-primary": "#ffffff",
                        "primary-fixed-dim": "#b2c5ff",
                        "surface-container-low": "#f1f3ff",
                        "on-tertiary": "#ffffff",
                        "secondary-fixed": "#ffdad6",
                        "on-error-container": "#93000a",
                        "on-primary-fixed": "#001848",
                        "surface-variant": "#d7e2ff",
                        "surface": "#f9f9ff",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed-variant": "#005235",
                        "surface-tint": "#0c56d0",
                        "surface-dim": "#cadaff",
                        "surface-container-high": "#e0e8ff",
                        "primary": "#003d9b",
                        "primary-fixed": "#dae2ff",
                        "error": "#ba1a1a",
                        "on-surface-variant": "#434654",
                        "tertiary-fixed-dim": "#71dba6",
                        "on-tertiary-fixed": "#002113",
                        "outline": "#737685",
                        "surface-bright": "#f9f9ff",
                        "surface-container-highest": "#d7e2ff",
                        "secondary-fixed-dim": "#ffb3ac",
                        "tertiary": "#004e32",
                        "secondary-container": "#da3433",
                        "on-primary-container": "#c4d2ff",
                        "inverse-on-surface": "#edf0ff",
                        "tertiary-fixed": "#8df7c1",
                        "tertiary-container": "#006844",
                        "on-error": "#ffffff",
                        "inverse-surface": "#1d3052",
                        "on-tertiary-container": "#7de7b2",
                        "on-background": "#041b3c",
                        "on-primary-fixed-variant": "#0040a2",
                        "primary-container": "#0052cc",
                        "outline-variant": "#c3c6d6",
                        "on-secondary-fixed-variant": "#930010"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin": "20px",
                        "sm": "8px",
                        "xs": "4px",
                        "gutter": "16px",
                        "lg": "24px",
                        "md": "16px",
                        "base": "8px",
                        "xl": "32px"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-lg": ["Inter"],
                        "body-md": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "500"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "label-lg": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen">
<!-- SideNavBar Component -->
<aside class="hidden lg:flex flex-col h-screen w-64 fixed left-0 top-0 bg-slate-50 dark:bg-slate-950 border-r border-slate-200 dark:border-slate-800 py-6 z-50">
<div class="px-6 mb-8">
<h1 class="text-lg font-black text-slate-900 dark:text-white">SafeDrive AI</h1>
<p class="text-[14px] leading-tight text-slate-500">Vigilance System Active</p>
</div>
<nav class="flex-1 px-3 space-y-1">
<!-- Dashboard (Active) -->
<a class="flex items-center gap-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 font-bold border-r-4 border-blue-700 p-3 transition-all cursor-pointer" href="#">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-lg text-label-lg">Dashboard</span>
</a>
<!-- History -->
<a class="flex items-center gap-3 text-slate-600 dark:text-slate-400 p-3 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all active:scale-[0.98] cursor-pointer" href="#">
<span class="material-symbols-outlined" data-icon="history">history</span>
<span class="font-label-lg text-label-lg">History</span>
</a>
<!-- Settings -->
<a class="flex items-center gap-3 text-slate-600 dark:text-slate-400 p-3 hover:bg-slate-100 dark:hover:bg-slate-900 transition-all active:scale-[0.98] cursor-pointer" href="#">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
<span class="font-label-lg text-label-lg">Settings</span>
</a>
</nav>
<div class="px-6 mt-auto">
<button class="w-full h-[56px] bg-primary text-on-primary font-label-lg text-label-lg rounded-xl shadow-sm hover:opacity-90 active:scale-[0.98] transition-all">
                Start Monitoring
            </button>
</div>
</aside>
<!-- Main Content Canvas -->
<main class="lg:ml-64 min-h-screen flex flex-col">
<!-- TopAppBar Component -->
<header class="sticky top-0 w-full flex justify-between items-center px-6 h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none z-40">
<div class="lg:hidden">
<span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">SafeDrive AI</span>
</div>
<div class="hidden lg:block">
<span class="font-headline-md text-headline-md text-on-surface">Dashboard Overview</span>
</div>
<div class="flex items-center gap-4">
<button class="p-2 text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer rounded-full">
<span class="material-symbols-outlined" data-icon="notifications">notifications</span>
</button>
<div class="h-8 w-8 rounded-full overflow-hidden border border-slate-200">
<img alt="Driver profile avatar" class="w-full h-full object-cover" data-alt="Close-up professional headshot of a mature male driver with a calm expression, wearing a neutral colored shirt, studio lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxpp6FSDxalNgKiCjY2FxJpruLGRycaZCEaAMW5PJpSro_8QPzMutaAkLQ-BtAPT58l_NeaacSfqelhX-Z6jWyOCdBfX6wyYh3-NZsxwHVXUJ6hW8AMEKGtyT9JnwLS1HaNLDm1lEVOP5Q22UIb_e4PE-gPpTDTj6zL3X5UDQy0rAK4mzfr-yKIn5_NU7JecMxb8oFTLQo98JznVylB1aMHdQSnMB5-VZErs8VC8oFB08Yz9vdY8ss8tilLMA2Tq8z1OFVwYrZdHPK"/>
</div>
</div>
</header>
<!-- Canvas Area -->
<section class="p-6 space-y-gutter">
<!-- Onboarding Carousel (Bento-inspired Featured Section) -->
<div class="relative w-full aspect-[21/9] md:aspect-[21/7] rounded-xl overflow-hidden bg-surface-container shadow-md group">
<div class="absolute inset-0 flex transition-transform duration-500 ease-out">
<!-- Slide 1 -->
<div class="relative w-full flex-shrink-0 h-full">
<img alt="Stay Awake, Stay Safe" class="absolute inset-0 w-full h-full object-cover" data-alt="Interior of a modern car at night with soft dashboard lighting, focused on a safe driving environment, calm atmosphere" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA4GQfgaLCvRBQyTqJto8pJC-QWsR-jK6wFM8RT9QQId5TKSJ8FKxJR76axOzuUflU8PBVwSlWtn1FhVX9inHKMltPNWCQn6lH7l195yRn75AS8s_Bc_Mv8C347mqgbJOvuymGYchBe6OuEdfXcM7JdtnrZJpzsuFmWltNiz-Lw6F6L6bNMy7wJ-uFpOtFrn2s-_8Ts49aqbvrX4ztTuBazBVl4WmfkcsnQqUMhUxOHBxPSYU8jNe9to6ZSH6mQD7RjU9HZjPwpItVl"/>
<div class="absolute inset-0 bg-gradient-to-r from-on-surface/80 via-on-surface/40 to-transparent flex flex-col justify-center px-12">
<span class="text-blue-400 font-label-lg text-label-lg uppercase tracking-widest mb-2">Tutorial 01</span>
<h2 class="font-headline-lg text-headline-lg text-white mb-4">Stay Awake, Stay Safe</h2>
<p class="text-white/80 font-body-md text-body-md max-w-md">Our AI monitors your eye movements and posture to detect signs of fatigue before they become dangerous.</p>
</div>
</div>
</div>
<!-- Carousel Indicators -->
<div class="absolute bottom-6 left-12 flex gap-2">
<div class="w-8 h-1 bg-white rounded-full"></div>
<div class="w-2 h-1 bg-white/40 rounded-full"></div>
<div class="w-2 h-1 bg-white/40 rounded-full"></div>
</div>
<!-- Navigation Arrows -->
<button class="absolute right-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center hover:bg-white/20 transition-all">
<span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
</button>
</div>
<!-- Dashboard Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-gutter">
<!-- System Control Card (Large) -->
<div class="md:col-span-2 lg:col-span-2 bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between min-h-[280px]">
<div>
<div class="flex items-center gap-2 mb-4">
<span class="h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
<span class="font-label-lg text-label-lg text-slate-600">System Ready</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-2">Driver Monitoring</h3>
<p class="font-body-md text-body-md text-on-surface-variant max-w-sm">Position your device to ensure your face is fully visible in the camera frame.</p>
</div>
<div class="flex gap-4">
<button class="flex-1 h-14 bg-primary text-on-primary rounded-xl font-label-lg text-label-lg shadow-md hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
<span class="material-symbols-outlined" data-icon="videocam">videocam</span>
                            Start Detection
                        </button>
<button class="w-14 h-14 bg-surface-container text-primary rounded-xl flex items-center justify-center hover:bg-surface-container-high transition-colors">
<span class="material-symbols-outlined" data-icon="settings">settings</span>
</button>
</div>
</div>
<!-- Status Widget 1: Alert Level -->
<div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex flex-col">
<div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center mb-4">
<span class="material-symbols-outlined" data-icon="emergency_home">emergency_home</span>
</div>
<span class="font-label-lg text-label-lg text-slate-500">Sensitivity</span>
<div class="flex items-end justify-between mt-auto">
<span class="font-headline-md text-headline-md text-on-surface">High</span>
<span class="text-emerald-600 font-label-sm text-label-sm pb-1 flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]" data-icon="check_circle">check_circle</span> Optimized
                        </span>
</div>
</div>
<!-- Status Widget 2: Session Stats -->
<div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 flex flex-col">
<div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-700 flex items-center justify-center mb-4">
<span class="material-symbols-outlined" data-icon="timer">timer</span>
</div>
<span class="font-label-lg text-label-lg text-slate-500">Active Session</span>
<div class="flex items-end justify-between mt-auto">
<span class="font-headline-md text-headline-md text-on-surface">0.0<span class="text-body-md text-slate-400">h</span></span>
<span class="text-slate-400 font-label-sm text-label-sm pb-1">No data</span>
</div>
</div>
<!-- Summary Chart Area (Simulated) -->
<div class="md:col-span-3 lg:col-span-3 bg-white rounded-xl p-6 shadow-sm border border-slate-100">
<div class="flex items-center justify-between mb-8">
<div>
<h4 class="font-label-lg text-label-lg text-on-surface">Recent Performance</h4>
<p class="font-label-sm text-label-sm text-slate-500">Vigilance score over the last 7 days</p>
</div>
<select class="bg-slate-50 border-none text-label-sm font-label-sm rounded-lg focus:ring-0">
<option>Last 7 Days</option>
<option>Last 30 Days</option>
</select>
</div>
<!-- Simplified Visual Chart Placeholder -->
<div class="h-48 flex items-end justify-between gap-2 px-4">
<div class="w-full bg-slate-100 rounded-t-lg h-[40%] transition-all hover:bg-blue-200"></div>
<div class="w-full bg-slate-100 rounded-t-lg h-[60%] transition-all hover:bg-blue-200"></div>
<div class="w-full bg-slate-100 rounded-t-lg h-[85%] transition-all hover:bg-blue-200"></div>
<div class="w-full bg-slate-100 rounded-t-lg h-[70%] transition-all hover:bg-blue-200"></div>
<div class="w-full bg-slate-100 rounded-t-lg h-[95%] transition-all hover:bg-blue-200"></div>
<div class="w-full bg-slate-100 rounded-t-lg h-[50%] transition-all hover:bg-blue-200"></div>
<div class="w-full bg-blue-600 rounded-t-lg h-[80%] transition-all"></div>
</div>
<div class="flex justify-between mt-4 text-label-sm font-label-sm text-slate-400">
<span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span>
</div>
</div>
<!-- Side Widget: Quick Tips -->
<div class="bg-surface-container rounded-xl p-6 flex flex-col">
<h4 class="font-label-lg text-label-lg text-on-surface-variant mb-4">Quick Tip</h4>
<div class="bg-white/50 p-4 rounded-lg mb-4">
<p class="font-body-md text-body-md text-on-surface italic">"Adjust your rearview mirror to avoid glare that might interfere with detection sensors."</p>
</div>
<a class="mt-auto text-primary font-label-lg text-label-lg flex items-center gap-1 group" href="#">
                        Read more safety tips
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform" data-icon="arrow_forward">arrow_forward</span>
</a>
</div>
</div>
</section>
<!-- Bottom Section (History Feed) -->
<section class="px-6 pb-6">
<div class="flex items-center justify-between mb-4">
<h3 class="font-headline-md text-headline-md text-on-surface">Recent Session History</h3>
<button class="text-primary font-label-lg text-label-lg hover:underline transition-all">View All</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
<!-- History Card 1 -->
<div class="bg-white rounded-xl shadow-sm border-l-4 border-emerald-500 p-4 flex items-center justify-between hover:shadow-md transition-all cursor-pointer">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
<span class="material-symbols-outlined" data-icon="verified">verified</span>
</div>
<div>
<p class="font-label-lg text-label-lg text-on-surface">Evening Drive</p>
<p class="font-label-sm text-label-sm text-slate-500">Oct 24, 2023 • 1h 45m</p>
</div>
</div>
<div class="text-right">
<p class="font-label-lg text-label-lg text-emerald-600">Safe</p>
<p class="font-label-sm text-label-sm text-slate-400">Score: 98/100</p>
</div>
</div>
<!-- History Card 2 -->
<div class="bg-white rounded-xl shadow-sm border-l-4 border-secondary p-4 flex items-center justify-between hover:shadow-md transition-all cursor-pointer">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-lg bg-error-container text-secondary flex items-center justify-center">
<span class="material-symbols-outlined" data-icon="warning">warning</span>
</div>
<div>
<p class="font-label-lg text-label-lg text-on-surface">Morning Commute</p>
<p class="font-label-sm text-label-sm text-slate-500">Oct 23, 2023 • 45m</p>
</div>
</div>
<div class="text-right">
<p class="font-label-lg text-label-lg text-secondary">Attention Needed</p>
<p class="font-label-sm text-label-sm text-slate-400">2 Micro-sleeps detected</p>
</div>
</div>
</div>
</section>
</main>
</body></html>