<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login &middot; Ajab Shahar</title>

    <!-- Font + Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 20% 0%, rgba(99, 102, 241, 0.35), transparent 40%),
                radial-gradient(circle at 80% 100%, rgba(236, 72, 153, 0.30), transparent 40%),
                linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 24px;
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        /* Subtle animated blobs in the background */
        body::before, body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.6;
            z-index: 0;
            animation: float 18s ease-in-out infinite;
        }
        body::before {
            width: 360px; height: 360px;
            background: #6366f1;
            top: -120px; left: -120px;
        }
        body::after {
            width: 420px; height: 420px;
            background: #ec4899;
            bottom: -160px; right: -160px;
            animation-delay: -9s;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50%      { transform: translate(40px, -30px) scale(1.06); }
        }

        .login-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            padding: 40px 36px 32px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.45);
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 28px;
        }
        .brand-logo {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 14px;
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.45);
        }
        .brand-title {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }
        .brand-subtitle {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.65);
            margin-top: 4px;
        }

        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 18px;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.40);
            color: #fecaca;
        }
        .alert i { font-size: 15px; }

        .field {
            margin-bottom: 16px;
        }
        .field label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.80);
            margin-bottom: 6px;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap i.input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.50);
            font-size: 14px;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            color: #fff;
            font-size: 14.5px;
            font-family: inherit;
            transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
        }
        .input-wrap input::placeholder {
            color: rgba(255, 255, 255, 0.40);
        }
        .input-wrap input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(99, 102, 241, 0.65);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18);
        }
        .input-wrap .toggle-pwd {
            position: absolute;
            top: 50%; right: 12px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.55);
            font-size: 14px;
            padding: 4px;
        }
        .input-wrap .toggle-pwd:hover { color: #fff; }

        .row-extra {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 22px;
            font-size: 13px;
        }
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.75);
            cursor: pointer;
            user-select: none;
        }
        .remember input {
            width: 15px; height: 15px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        button.login-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            letter-spacing: 0.01em;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.40);
            transition: transform 0.12s ease, box-shadow 0.15s ease, filter 0.15s ease;
        }
        button.login-btn:hover  { filter: brightness(1.06); transform: translateY(-1px); box-shadow: 0 14px 28px rgba(99, 102, 241, 0.50); }
        button.login-btn:active { transform: translateY(0); }
        button.login-btn:disabled { opacity: 0.7; cursor: not-allowed; }

        .footer {
            text-align: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 24px;
        }

        @media (max-width: 480px) {
            .login-card { padding: 32px 24px 26px; border-radius: 16px; }
            .brand-logo { width: 56px; height: 56px; font-size: 24px; }
            .brand-title { font-size: 20px; }
        }
    </style>
</head>
<body>
    <div class="login-card" role="main">
        <div class="brand">
            <div class="brand-logo" aria-hidden="true">A</div>
            <div class="brand-title">Ajab Shahar Admin</div>
            <div class="brand-subtitle">Sign in to continue</div>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <span><?= htmlspecialchars((string) $this->session->flashdata('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php $vErrors = validation_errors(); if ($vErrors): ?>
            <div class="alert alert-error" role="alert">
                <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                <span><?= $vErrors; ?></span>
            </div>
        <?php endif; ?>

        <?= form_open('login', ['autocomplete' => 'on', 'novalidate' => 'novalidate']); ?>
            <div class="field">
                <label for="login_username">Username</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user input-icon" aria-hidden="true"></i>
                    <input
                        id="login_username"
                        type="text"
                        name="username"
                        value="<?= set_value('username'); ?>"
                        placeholder="Enter your username"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="field">
                <label for="login_password">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock input-icon" aria-hidden="true"></i>
                    <input
                        id="login_password"
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                    <button type="button" class="toggle-pwd" aria-label="Show password" data-target="login_password">
                        <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="row-extra">
                <label class="remember">
                    <input type="checkbox" name="remember" value="1">
                    Remember me
                </label>
            </div>

            <button type="submit" class="login-btn">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                Sign In
            </button>
        <?= form_close(); ?>

        <div class="footer">
            &copy; <?= date('Y') ?> Ajab Shahar &middot; Admin Panel
        </div>
    </div>

    <script>
        // Password show/hide toggle
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.toggle-pwd');
            if (!btn) return;
            var input = document.getElementById(btn.getAttribute('data-target'));
            if (!input) return;
            var isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            var icon = btn.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !isHidden);
                icon.classList.toggle('fa-eye-slash', isHidden);
            }
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });

        // Disable button on submit to prevent double-click
        document.querySelector('form').addEventListener('submit', function (e) {
            var btn = this.querySelector('.login-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Signing in...';
            }
        });
    </script>
</body>
</html>
