<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login &middot; Ajab Shahar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --brand:        #4f46e5;     /* indigo-600  */
            --brand-strong: #4338ca;     /* indigo-700  */
            --brand-soft:   #eef2ff;     /* indigo-50   */
            --ink:          #1e293b;     /* slate-800   */
            --ink-soft:     #475569;     /* slate-600   */
            --ink-mute:     #94a3b8;     /* slate-400   */
            --line:         #e2e8f0;     /* slate-200   */
            --bg-left:      #ffffff;
            --bg-right:     #0f172a;     /* slate-900   */
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-left);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
        }

        /* ---------------- LEFT — Form panel ---------------- */
        .auth-left {
            flex: 1 1 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            /* Soft tinted background so the white card on top pops out */
            background:
                radial-gradient(circle at 0% 0%,   rgba(79, 70, 229, 0.07), transparent 55%),
                radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.05), transparent 55%),
                #f8fafc;
        }
        /* The form itself is now a real card: white surface, subtle border + shadow */
        .auth-form-wrap {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 40px 38px 32px;
            box-shadow:
                0 1px 2px rgba(15, 23, 42, 0.04),
                0 12px 32px rgba(15, 23, 42, 0.08);
        }
        .brand-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 26px;
        }
        .brand-mark {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: var(--brand);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 22px;
            letter-spacing: -0.02em;
        }
        .brand-text { line-height: 1.15; }
        .brand-text .name { font-weight: 700; color: var(--ink); font-size: 16px; }
        .brand-text .sub  { font-weight: 600; color: var(--ink-mute); font-size: 11px; letter-spacing: 0.10em; text-transform: uppercase; }

        h1.auth-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }
        .auth-subtitle {
            color: var(--ink-soft);
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .alert {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 18px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 8px;
        }
        .field input[type="text"],
        .field input[type="password"] {
            width: 100%;
            padding: 13px 16px;
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            color: var(--ink);
            font-size: 14.5px;
            font-family: inherit;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .field input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }
        .field input::placeholder { color: var(--ink-mute); }

        .field-password .pw-wrap { position: relative; }
        .field-password .toggle-pwd {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--ink-mute); cursor: pointer;
            padding: 6px;
            font-size: 14px;
        }
        .field-password .toggle-pwd:hover { color: var(--ink); }

        .row-extra {
            display: flex; align-items: center; justify-content: space-between;
            margin: 6px 0 22px;
            font-size: 13.5px;
        }
        .remember {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--ink-soft); cursor: pointer; user-select: none;
        }
        .remember input {
            width: 16px; height: 16px;
            accent-color: var(--brand);
            cursor: pointer;
        }
        .forgot-link {
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
        }
        .forgot-link:hover { color: var(--brand-strong); text-decoration: underline; }

        button.signin-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: background .15s ease, transform .1s ease, box-shadow .15s ease;
        }
        button.signin-btn:hover  { background: var(--brand-strong); box-shadow: 0 8px 18px rgba(79, 70, 229, 0.28); }
        button.signin-btn:active { transform: translateY(1px); }
        button.signin-btn:disabled { opacity: 0.75; cursor: not-allowed; }

        .form-foot {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid var(--line);
            display: flex; align-items: flex-start; gap: 10px;
            color: var(--ink-soft);
            font-size: 12.5px;
            line-height: 1.5;
        }
        .form-foot i {
            color: var(--brand);
            font-size: 14px;
            margin-top: 2px;
        }

        /* ---------------- RIGHT — Branding panel ---------------- */
        .auth-right {
            flex: 1 1 0;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 55%, #1e1b4b 100%);
            color: #fff;
            padding: 60px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        /* Decorative subtle ring */
        .auth-right::before, .auth-right::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }
        .auth-right::before { width: 520px; height: 520px; right: -180px; top: -120px; }
        .auth-right::after  { width: 720px; height: 720px; right: -260px; bottom: -260px; border-color: rgba(255, 255, 255, 0.04); }

        .pill {
            display: inline-block;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.10em;
            text-transform: uppercase;
            color: #c7d2fe;
            margin-bottom: 22px;
            position: relative;
            z-index: 1;
        }
        .hero-title {
            font-size: 38px;
            font-weight: 700;
            line-height: 1.18;
            letter-spacing: -0.015em;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }
        .hero-title .accent { color: #a5b4fc; }
        .hero-lede {
            color: #cbd5e1;
            font-size: 15px;
            line-height: 1.6;
            max-width: 540px;
            margin-bottom: 36px;
            position: relative;
            z-index: 1;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            max-width: 620px;
            position: relative;
            z-index: 1;
        }
        .feature {
            display: flex; gap: 14px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            transition: background .15s ease, border-color .15s ease;
        }
        .feature:hover { background: rgba(255, 255, 255, 0.08); border-color: rgba(255, 255, 255, 0.14); }
        .feature-icon {
            flex: 0 0 40px;
            width: 40px; height: 40px;
            border-radius: 10px;
            background: rgba(165, 180, 252, 0.15);
            color: #c7d2fe;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }
        .feature-body .title {
            font-size: 14px;
            font-weight: 600;
            color: #f1f5f9;
            margin-bottom: 4px;
        }
        .feature-body .desc {
            font-size: 12.5px;
            color: #94a3b8;
            line-height: 1.45;
        }

        .tag-row {
            display: flex; gap: 10px; flex-wrap: wrap;
            margin-top: 24px;
            position: relative; z-index: 1;
        }
        .tag {
            padding: 7px 14px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: #cbd5e1;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 500;
        }

        /* ---------------- Responsive ---------------- */
        @media (max-width: 1024px) {
            body { flex-direction: column; }
            .auth-right { padding: 48px 36px; }
            .hero-title { font-size: 32px; }
            .feature-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .auth-left { padding: 28px 18px; }
            .auth-right { padding: 40px 22px; }
            .auth-form-wrap {
                padding: 30px 22px 24px;
                border-radius: 14px;
            }
            h1.auth-title { font-size: 22px; }
            .hero-title { font-size: 26px; }
        }
    </style>
</head>
<body>

    <!-- ============ LEFT — Login form ============ -->
    <section class="auth-left">
        <div class="auth-form-wrap">
            <div class="brand-row">
                <div class="brand-mark" aria-hidden="true">A</div>
                <div class="brand-text">
                    <div class="name">Ajab Shahar</div>
                    <div class="sub">Admin Panel</div>
                </div>
            </div>

            <h1 class="auth-title">Admin Login</h1>
            <p class="auth-subtitle">Secure access to the Ajab Shahar content management system.</p>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert" role="alert">
                    <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                    <span><?= htmlspecialchars((string) $this->session->flashdata('error')); ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('authenticate') ?>" method="POST" autocomplete="on" novalidate>
                <div class="field">
                    <label for="login_username">Username or Email</label>
                    <input
                        id="login_username"
                        type="text"
                        name="username"
                        placeholder="Enter username or email"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>

                <div class="field field-password">
                    <label for="login_password">Password</label>
                    <div class="pw-wrap">
                        <input
                            id="login_password"
                            type="password"
                            name="password"
                            placeholder="Enter password"
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

                <button type="submit" class="signin-btn">Sign In</button>
            </form>

            <div class="form-foot">
                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                <span>Your data is protected with enterprise-grade security and encryption.</span>
            </div>
        </div>
    </section>

    <!-- ============ RIGHT — Branding / info ============ -->
    <aside class="auth-right" aria-hidden="true">
        <span class="pill">Content Management</span>
        <h2 class="hero-title">
            Manage every piece of <span class="accent">Ajab Shahar</span> content from one place.
        </h2>
        <p class="hero-lede">
            Songs, couplets, reflections, films, people and more &mdash; all curated, organised and ready to publish through a single, dependable admin panel.
        </p>

        <div class="feature-grid">
            <div class="feature">
                <div class="feature-icon">S</div>
                <div class="feature-body">
                    <div class="title">Songs &amp; Couplets</div>
                    <div class="desc">Add lyrics, translations, glossary and audio.</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">R</div>
                <div class="feature-body">
                    <div class="title">Reflections</div>
                    <div class="desc">Interviews, essays and audio reflections.</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">F</div>
                <div class="feature-body">
                    <div class="title">Films &amp; Episodes</div>
                    <div class="desc">Organise episodes, posters and metadata.</div>
                </div>
            </div>
            <div class="feature">
                <div class="feature-icon">P</div>
                <div class="feature-body">
                    <div class="title">People &amp; Glossary</div>
                    <div class="desc">Singers, poets, keywords &mdash; all linked.</div>
                </div>
            </div>
        </div>

        <div class="tag-row">
            <span class="tag">Secure</span>
            <span class="tag">Fast workflows</span>
            <span class="tag">Real-time updates</span>
        </div>
    </aside>

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

        // Disable button on submit to prevent double-submit
        var form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function () {
                var btn = form.querySelector('.signin-btn');
                if (btn) { btn.disabled = true; btn.textContent = 'Signing in…'; }
            });
        }
    </script>

</body>
</html>
