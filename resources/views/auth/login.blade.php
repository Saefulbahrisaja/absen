<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        /* === STYLE DASAR === */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* === CARD LOGIN === */
        .login-card {
            width: 100%;
            max-width: 380px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            position: relative;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #1f2937;
            font-size: 1.5rem;
            font-weight: 600;
        }

        label {
            display: block;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            border-color: #4f46e5;
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        button {
            width: 100%;
            padding: 0.75rem;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }

        button:hover:not(:disabled) {
            background: #4338ca;
            transform: translateY(-1px);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error {
            color: #dc2626;
            text-align: center;
            font-size: 0.75rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
        }

        .remember {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            margin-bottom: 1rem;
            color: #6b7280;
        }

        .remember input {
            margin-right: 0.5rem;
            width: auto;
            flex-shrink: 0;
        }

        .remember label {
            margin: 0;
            cursor: pointer;
        }

        /* === LOADING OVERLAY === */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(4px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .loader {
            border: 4px solid #e5e7eb;
            border-top: 4px solid #4f46e5;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .animate-pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* === PROGRESS LOCK === */
        .progress-container {
            margin-top: 1rem;
            text-align: center;
        }

        .progress-bar-bg {
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-bar {
            height: 100%;
            width: 100%;
            background: #16a34a;
            transition: width 0.3s ease, background-color 0.3s ease;
            border-radius: 4px;
        }

        .progress-text {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* === RESPONSIVE === */
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem;
                margin: 0.5rem;
            }

            h2 {
                font-size: 1.25rem;
            }

            body {
                padding: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Overlay -->
    <div id="loading-overlay" class="overlay">
        <div style="text-align:center;">
            <div class="loader"></div>
            <p class="animate-pulse" style="color:#374151; margin-top:0.5rem; font-size:0.875rem;">Sedang masuk...</p>
        </div>
    </div>

    <div class="login-card">
        <h2>Login</h2>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        @if(session('locked_seconds'))
            <div class="progress-container" id="countdown-message">
                <div class="progress-bar-bg">
                    <div id="progress-bar" class="progress-bar"></div>
                </div>
                <div class="progress-text">
                    Terbuka dalam <span id="seconds-text">{{ session('locked_seconds') }}</span> detik...
                </div>
            </div>

            <script>
                const totalSeconds = {{ session('locked_seconds') }};
                let remaining = totalSeconds;
                const bar = document.getElementById('progress-bar');
                const secondsText = document.getElementById('seconds-text');

                function getColor(percent) {
                    if (percent > 66) return '#16a34a'; // hijau
                    if (percent > 33) return '#facc15'; // kuning
                    return '#ef4444'; // merah
                }

                const timer = setInterval(() => {
                    remaining--;
                    secondsText.textContent = remaining;
                    let percent = (remaining / totalSeconds) * 100;
                    bar.style.width = percent + "%";
                    bar.style.backgroundColor = getColor(percent);

                    if (remaining <= 0) {
                        clearInterval(timer);
                        document.getElementById('countdown-message').innerHTML = '<div class="progress-text">Memuat ulang halaman...</div>';
                        setTimeout(() => location.reload(), 1000);
                    }
                }, 1000);
            </script>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="{{ old('email') }}">
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <div class="remember">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember me</label>
            </div>

            <button type="submit">Login</button>
        </form>

        @if(session('locked_seconds'))
            <script>
                const form = document.querySelector('form');
                form.addEventListener('submit', e => e.preventDefault());
                const submitBtn = document.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.6";
                submitBtn.style.cursor = "not-allowed";
            </script>
        @endif

        <script>
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                @if(!session('locked_seconds'))
                    document.getElementById('loading-overlay').style.display = 'flex';
                @endif
            });
        </script>
    </div>
</body>
</html>
