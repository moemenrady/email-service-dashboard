<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول | email-service-dashboard — مؤمن تيم</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-cairo antialiased selection:bg-emerald-500 selection:text-white">
    <div id="login-view" class="login-view">
        <canvas id="bg-canvas" aria-hidden="true"></canvas>
        <div class="login-vignette" aria-hidden="true"></div>

        <div class="login-content">
            <!-- Brand -->
            <div class="login-brand">
                <div class="logo-badge" style="width: 48px; height: 48px;"></div>
                <div class="text-right">
                    <span class="text-white font-extrabold text-xl tracking-wide block leading-none">
                        MOEMEN <span class="text-primary-400">TEAM</span>
                    </span>
                    <span class="text-[11px] font-semibold text-lightSage tracking-widest block mt-1">
                        لوحة إدارة البريد الإلكتروني • email-service-dashboard
                    </span>
                </div>
            </div>

            <!-- Hero Message with animated text -->
            <div class="text-center px-2">
                <h1 class="text-xl sm:text-2xl font-bold text-white leading-relaxed">
                    منظومة إدارة البريد السحابي
                </h1>
                <p id="typewriter-text" class="text-xs sm:text-sm text-lightSage opacity-90 mt-1 min-h-[22px]">
                    فريق مؤمن للتطوير البرمجي • الشريك التقني لبناء وتأمين الأعمال
                </p>
            </div>

            <!-- Login Form Card -->
            <form method="POST" action="{{ route('login') }}" class="login-card" novalidate>
                @csrf

                @if (session('status'))
                    <div class="p-3 bg-emerald-500/20 border border-emerald-500/40 rounded-xl text-emerald-300 text-xs">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3 bg-rose-500/20 border border-rose-500/40 rounded-xl text-rose-300 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="field text-right">
                    <label for="email" class="text-right">البريد الإلكتروني / Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@company.com" dir="ltr" class="text-left" />
                </div>

                <div class="field text-right">
                    <div class="flex justify-between items-center">
                        <label for="password">كلمة المرور / Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] text-primary-400 hover:underline">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required placeholder="••••••••" dir="ltr" class="text-left" />
                </div>

                <div class="flex items-center justify-between text-xs text-lightSage">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-black/40 border-white/20 text-emerald-600 focus:ring-emerald-500">
                        <span>تذكرني</span>
                    </label>
                </div>

                <button type="submit" class="btn-moemen-primary mt-1">
                    <span>تسجيل الدخول</span>
                    <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>

            <footer class="text-center text-[11px] text-gray-400">
                فريق مؤمن للتطوير البرمجي © {{ date('Y') }} • Moemen Team
            </footer>
        </div>
    </div>

    <!-- Particle canvas script -->
    <script>

        // Background particles
        (function() {
            const canvas = document.getElementById("bg-canvas");
            if (!canvas) return;
            const ctx = canvas.getContext("2d");
            if (!ctx) return;

            let w = 0, h = 0;
            const points = [];
            const count = 35;

            function resize() {
                w = canvas.width = window.innerWidth;
                h = canvas.height = window.innerHeight;
            }
            window.addEventListener("resize", resize);
            resize();

            for (let i = 0; i < count; i++) {
                points.push({
                    x: Math.random() * w,
                    y: Math.random() * h,
                    vx: (Math.random() - 0.5) * 0.6,
                    vy: (Math.random() - 0.5) * 0.6,
                });
            }

            function draw() {
                ctx.clearRect(0, 0, w, h);
                ctx.fillStyle = "rgba(167, 183, 167, 0.5)";
                ctx.strokeStyle = "rgba(80, 96, 85, 0.2)";

                for (let i = 0; i < count; i++) {
                    const p = points[i];
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0 || p.x > w) p.vx *= -1;
                    if (p.y < 0 || p.y > h) p.vy *= -1;

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, 2, 0, Math.PI * 2);
                    ctx.fill();

                    for (let j = i + 1; j < count; j++) {
                        const p2 = points[j];
                        const dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                        if (dist < 120) {
                            ctx.beginPath();
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.stroke();
                        }
                    }
                }
                requestAnimationFrame(draw);
            }
            draw();
        })();
    </script>
</body>
</html>
