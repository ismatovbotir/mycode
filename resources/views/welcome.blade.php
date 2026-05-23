<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <!-- SEO -->
    <title data-i18n="meta.title">MyCode — автоматические уведомления клиентов в Telegram</title>
    <meta name="description"
        content="SaaS для бизнеса в СНГ: автоматические уведомления клиентов через Telegram при событиях из МойСклад — отгрузка, оплата, возврат. 4 языка, безлимит ботов, настройка за 2 минуты." />
    <meta name="keywords"
        content="MyCode, МойСклад, Telegram бот, уведомления клиентов, CRM Узбекистан, МойСклад интеграция, webhook Telegram, уведомления отгрузка, SaaS СНГ, Telegram автоматизация" />
    <meta name="author" content="MyCode" />
    <meta name="robots" content="index, follow, max-image-preview:large" />
    <meta name="theme-color" content="#0284c7" />
    <link rel="canonical" href="https://mycode.uz/" />

    <!-- Hreflang -->
    <link rel="alternate" hreflang="uz" href="https://mycode.uz/?lang=uz" />
    <link rel="alternate" hreflang="kaa" href="https://mycode.uz/?lang=kaa" />
    <link rel="alternate" hreflang="tg" href="https://mycode.uz/?lang=tj" />
    <link rel="alternate" hreflang="kk" href="https://mycode.uz/?lang=kk" />
    <link rel="alternate" hreflang="ru" href="https://mycode.uz/?lang=ru" />
    <link rel="alternate" hreflang="x-default" href="https://mycode.uz/" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="MyCode" />
    <meta property="og:locale" content="uz_UZ" />
    <meta property="og:locale:alternate" content="kaa" />
    <meta property="og:locale:alternate" content="tg_TJ" />
    <meta property="og:locale:alternate" content="kk_KZ" />
    <meta property="og:locale:alternate" content="ru_RU" />
    <meta property="og:url" content="https://mycode.uz/" />
    <meta property="og:title" content="MyCode · уведомления клиентов в Telegram" />
    <meta property="og:description"
        content="Подключите Telegram-бота к МойСклад и автоматически уведомляйте клиентов на их языке. Бесплатный тариф." />
    <meta property="og:image" content="https://mycode.uz/og.png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="MyCode — уведомления для клиентов через Telegram" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="MyCode · уведомления клиентов в Telegram" />
    <meta name="twitter:description"
        content="Подключите Telegram-бота к МойСклад и автоматически уведомляйте клиентов на их языке." />
    <meta name="twitter:image" content="https://mycode.uz/og.png" />
    <meta name="twitter:site" content="telegramcrm_uz" />

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}" />
    <meta name="theme-color" content="#0284c7" />

    <!-- Schema.org -->
    <script type="application/ld+json">
{
  "context": "https://schema.org",
  "type": "SoftwareApplication",
  "name": "MyCode",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Web",
  "description": "SaaS для автоматических уведомлений клиентов через Telegram, интеграция с МойСклад.",
  "url": "https://mycode.uz/",
  "inLanguage": ["uz","kk","kz","tj","ru"],
  "offers": [
    { "type":"Offer", "name":"Старт",      "price":"0",  "priceCurrency":"UZS" },
    { "type":"Offer", "name":"Бизнес",     "price":"29", "priceCurrency":"UZS" },
    { "type":"Offer", "name":"Корпоратив", "price":"99", "priceCurrency":"UZS" }
  ],
  "aggregateRating": { "type":"AggregateRating", "ratingValue":"4.9", "reviewCount":"128" }
}
</script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985'
                        },
                        accent: {
                            500: '#f97316',
                            600: '#ea580c'
                        },
                        ink: {
                            900: '#0f172a',
                            700: '#334155',
                            500: '#64748b',
                            300: '#cbd5e1',
                            200: '#e2e8f0',
                            100: '#f1f5f9',
                            50: '#f8fafc'
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    },
                    boxShadow: {
                        card: '0 1px 2px rgba(15,23,42,.04), 0 8px 24px -8px rgba(15,23,42,.08)',
                        cardLg: '0 4px 10px rgba(15,23,42,.04), 0 24px 60px -24px rgba(2,132,199,.25)',
                        phone: '0 30px 80px -20px rgba(2,132,199,.35), 0 10px 30px -10px rgba(15,23,42,.25)',
                    },
                }
            }
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', 'ui-sans-serif', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #0f172a;
        }

        /* Reveal on scroll */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .8s cubic-bezier(.2, .7, .2, 1), transform .8s cubic-bezier(.2, .7, .2, 1);
        }

        .reveal.in {
            opacity: 1;
            transform: none;
        }

        .reveal-stagger>* {
            opacity: 0;
            transform: translateY(16px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .reveal-stagger.in>* {
            opacity: 1;
            transform: none;
        }

        .reveal-stagger.in>*:nth-child(1) {
            transition-delay: .04s
        }

        .reveal-stagger.in>*:nth-child(2) {
            transition-delay: .10s
        }

        .reveal-stagger.in>*:nth-child(3) {
            transition-delay: .16s
        }

        .reveal-stagger.in>*:nth-child(4) {
            transition-delay: .22s
        }

        .reveal-stagger.in>*:nth-child(5) {
            transition-delay: .28s
        }

        .reveal-stagger.in>*:nth-child(6) {
            transition-delay: .34s
        }

        .hero-grid {
            background-image:
                radial-gradient(60% 60% at 78% 25%, rgba(2, 132, 199, .10) 0%, rgba(2, 132, 199, 0) 60%),
                radial-gradient(50% 50% at 12% 80%, rgba(249, 115, 22, .08) 0%, rgba(249, 115, 22, 0) 60%),
                linear-gradient(to bottom, #ffffff, #f8fafc 60%, #ffffff);
        }

        .hero-grid::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(to right, rgba(15, 23, 42, .04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, .04) 1px, transparent 1px);
            background-size: 56px 56px;
            -webkit-mask-image: radial-gradient(60% 60% at 50% 30%, #000 30%, transparent 75%);
            mask-image: radial-gradient(60% 60% at 50% 30%, #000 30%, transparent 75%);
            pointer-events: none;
        }

        /* Phone */
        .phone {
            width: 300px;
            height: 600px;
            border-radius: 44px;
            background: linear-gradient(180deg, #0f172a, #1e293b);
            padding: 12px;
            position: relative;
        }

        .phone-screen {
            width: 100%;
            height: 100%;
            border-radius: 32px;
            overflow: hidden;
            position: relative;
            background: linear-gradient(180deg, #cbe6f7 0%, #e6f1fb 30%, #f1f5f9 100%);
        }

        .phone-notch {
            position: absolute;
            top: 14px;
            left: 50%;
            transform: translateX(-50%);
            width: 110px;
            height: 26px;
            background: #0f172a;
            border-radius: 14px;
            z-index: 5;
        }

        /* Telegram chat */
        .chat-bg {
            background-color: #cbe6f7;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .5) 0, transparent 12%),
                radial-gradient(circle at 70% 60%, rgba(255, 255, 255, .45) 0, transparent 14%),
                radial-gradient(circle at 40% 85%, rgba(255, 255, 255, .4) 0, transparent 12%),
                linear-gradient(180deg, #cbe6f7, #a7d3ee);
        }

        .bubble {
            max-width: 78%;
            padding: 9px 12px 7px;
            border-radius: 14px;
            font-size: 13px;
            line-height: 1.35;
            position: relative;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .06);
        }

        .bubble-in {
            background: #ffffff;
            color: #0f172a;
            border-bottom-left-radius: 4px;
            align-self: flex-start;
        }

        .bubble-out {
            background: #effdde;
            color: #0f172a;
            border-bottom-right-radius: 4px;
            align-self: flex-end;
        }

        .bubble .time {
            font-size: 10px;
            color: #64748b;
            margin-left: 8px;
        }

        .bubble .check {
            color: #34a3da;
            font-size: 11px;
            margin-left: 2px;
        }

        .bubble-enter {
            animation: pop .35s cubic-bezier(.2, .8, .25, 1.2);
        }

        @keyframes pop {
            0% {
                opacity: 0;
                transform: translateY(8px) scale(.96)
            }

            100% {
                opacity: 1;
                transform: none
            }
        }

        .typing {
            display: inline-flex;
            gap: 3px;
            align-items: center;
            padding: 2px 0;
        }

        .typing span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #94a3b8;
            animation: bounce 1.2s infinite ease-in-out;
        }

        .typing span:nth-child(2) {
            animation-delay: .15s
        }

        .typing span:nth-child(3) {
            animation-delay: .30s
        }

        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: translateY(0);
                opacity: .5
            }

            40% {
                transform: translateY(-4px);
                opacity: 1
            }
        }

        .tg-btn {
            background: #fff;
            color: #0284c7;
            font-weight: 600;
            font-size: 12px;
            padding: 8px 10px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 1px 0 rgba(0, 0, 0, .04);
        }

        /* Steps */
        .step-card {
            transition: all .4s ease;
        }

        .step-card.active {
            transform: translateY(-4px);
            border-color: #0284c7;
            box-shadow: 0 1px 2px rgba(2, 132, 199, .05), 0 24px 50px -18px rgba(2, 132, 199, .35);
        }

        .step-card.active .step-num {
            background: #0284c7;
            color: #fff;
        }

        .step-card.active .step-bar {
            background: #0284c7;
        }

        .step-bar {
            transition: background .4s ease;
        }

        /* FAQ */
        .faq-item[open] .faq-icon {
            transform: rotate(45deg);
        }

        .faq-icon {
            transition: transform .25s ease;
        }

        summary {
            list-style: none;
            cursor: pointer;
        }

        summary::-webkit-details-marker {
            display: none
        }

        /* Pricing popular */
        .popular-glow {
            position: relative;
        }

        .popular-glow::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            padding: 2px;
            background: linear-gradient(135deg, #0284c7, #0ea5e9 40%, #f97316);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .nav-blur {
            backdrop-filter: saturate(180%) blur(10px);
            -webkit-backdrop-filter: saturate(180%) blur(10px);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-6px)
            }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }

        .connector {
            background-image: linear-gradient(90deg, #cbd5e1 50%, transparent 50%);
            background-size: 10px 2px;
            background-repeat: repeat-x;
            background-position: center;
            height: 2px;
        }

        .mock-input {
            background: #0b1220;
            border: 1px solid #1e293b;
            color: #e2e8f0;
        }

        .mock-label {
            color: #94a3b8;
            font-size: 11px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* Lang switcher */
        .lang-btn {
            display: flex;
            align-items: center;
            gap: .5rem;
            width: 100%;
            padding: .55rem .75rem;
            border-radius: .6rem;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
        }

        .lang-btn:hover {
            background: #f1f5f9;
        }

        .lang-btn.is-active {
            background: #e0f2fe;
            color: #0284c7;
            font-weight: 600;
        }

        .lang-btn.is-active::after {
            content: '✓';
            margin-left: auto;
            color: #0284c7;
        }
    </style>
</head>

<body class="bg-white text-ink-900">

    <!-- =================== NAVBAR =================== -->
    <header class="fixed top-0 inset-x-0 z-50 nav-blur bg-white/75 border-b border-ink-200/70">
        <nav class="max-w-7xl mx-auto px-5 lg:px-8 h-16 flex items-center justify-between gap-2">
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <span class="w-9 h-9 rounded-xl bg-brand-600 grid place-items-center shadow-card">
                    <svg viewBox="0 0 24 24" class="w-5 h-5 text-white" fill="currentColor">
                        <path
                            d="M21.5 3.5L2.7 11.2c-.9.4-.9 1.6.1 1.9l4.6 1.4 1.7 5.4c.3.9 1.4 1.1 2 .3l2.4-3 4.7 3.5c.8.6 2 .1 2.1-.9L22.9 4.6c.1-.9-.7-1.5-1.4-1.1zM9.7 14.6l8.2-7-6.4 7.5-.1 3.2-1.7-3.7z" />
                    </svg>
                </span>
                <div class="leading-tight">
                    <div class="font-bold text-[15px] tracking-tight">MyCode</div>
                    <div class="text-[10px] text-ink-500 -mt-0.5" data-i18n="nav.sub">для МойСклад</div>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-ink-700">
                <a href="#how" class="hover:text-brand-600 transition" data-i18n="nav.how">Как работает</a>
                <a href="#features" class="hover:text-brand-600 transition" data-i18n="nav.features">Возможности</a>
                <a href="#demo" class="hover:text-brand-600 transition" data-i18n="nav.demo">Демо</a>
                <a href="#integration" class="hover:text-brand-600 transition"
                    data-i18n="nav.integration">Интеграция</a>
                <a href="#pricing" class="hover:text-brand-600 transition" data-i18n="nav.pricing">Тарифы</a>
                <a href="#faq" class="hover:text-brand-600 transition" data-i18n="nav.faq">FAQ</a>
            </div>

            <div class="flex items-center gap-2">

                <!-- LANG SWITCHER -->
                <div class="relative">
                    <button id="lang-toggle" type="button" aria-label="Language" aria-haspopup="menu"
                        aria-expanded="false"
                        class="inline-flex items-center gap-1.5 text-[13px] font-medium text-ink-700 hover:text-brand-600 hover:bg-ink-100 px-2.5 py-2 rounded-xl border border-transparent hover:border-ink-200 transition">
                        <span id="lang-current" class="inline-flex items-center gap-1.5"><span
                                class="text-base leading-none">🇺🇿</span><span
                                class="font-semibold tracking-wide">UZ</span></span>
                        <svg viewBox="0 0 20 20" class="w-3 h-3 text-ink-500" fill="currentColor">
                            <path d="M5.5 7.5l4.5 4.5 4.5-4.5H5.5z" />
                        </svg>
                    </button>
                    <div id="lang-menu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white border border-ink-200 rounded-xl shadow-cardLg p-1.5 z-50">
                        <div class="px-3 pt-2 pb-1 text-[10px] uppercase tracking-[0.14em] text-ink-500 font-semibold"
                            data-i18n="nav.lang">Язык</div>
                        <button class="lang-btn" data-lang-btn="uz"><span class="text-lg leading-none">🇺🇿</span>
                            O'zbek</button>
                        <button class="lang-btn" data-lang-btn="kaa"><span class="text-lg leading-none">🏳️</span>
                            Qaraqalpaq</button>
                        <button class="lang-btn" data-lang-btn="tj"><span class="text-lg leading-none">🇹🇯</span>
                            Тоҷикӣ</button>
                        <button class="lang-btn" data-lang-btn="kk"><span class="text-lg leading-none">🇰🇿</span>
                            Қазақша</button>
                        <button class="lang-btn" data-lang-btn="ru"><span class="text-lg leading-none">🇷🇺</span>
                            Русский</button>
                    </div>
                </div>

                @auth
                    <a href="{{ auth()->user()->isSuperAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                        class="hidden sm:inline-flex text-sm font-medium text-ink-700 hover:text-brand-600 px-3 py-2">
                        {{ auth()->user()->isSuperAdmin() ? 'Админ панель' : 'Кабинет' }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="hidden sm:inline-flex text-sm font-medium text-ink-700 hover:text-brand-600 px-3 py-2">Выход</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="hidden sm:inline-flex text-sm font-medium text-ink-700 hover:text-brand-600 px-3 py-2"
                        data-i18n="nav.signin">Войти</a>
                @endauth

                @auth
                    <a href="{{ auth()->user()->isSuperAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold bg-ink-900 text-white px-4 py-2.5 rounded-xl hover:bg-brand-600 transition">
                        <span>{{ auth()->user()->isSuperAdmin() ? 'Админ' : 'В кабинет' }}</span>
                        <svg viewBox="0 0 20 20" class="w-3.5 h-3.5" fill="currentColor">
                            <path d="M7 5l5 5-5 5V5z" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-1.5 text-sm font-semibold bg-ink-900 text-white px-4 py-2.5 rounded-xl hover:bg-brand-600 transition">
                        <span data-i18n="nav.start">Начать</span>
                        <svg viewBox="0 0 20 20" class="w-3.5 h-3.5" fill="currentColor">
                            <path d="M7 5l5 5-5 5V5z" />
                        </svg>
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- =================== HERO =================== -->
    <section id="top" class="relative overflow-hidden hero-grid pt-28 lg:pt-32 pb-20 lg:pb-28">
        <div class="relative max-w-7xl mx-auto px-5 lg:px-8 grid lg:grid-cols-12 gap-10 lg:gap-6 items-center">

            <div class="lg:col-span-7">
                <div
                    class="reveal inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-ink-200 shadow-card text-xs font-medium text-ink-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span data-i18n="hero.badge">Уже работает с МойСклад · вебхуки в реальном времени</span>
                </div>

                <h1
                    class="reveal mt-5 text-4xl sm:text-5xl lg:text-[68px] font-extrabold tracking-tight leading-[1.02]">
                    <span data-i18n="hero.title1">Уведомляйте клиентов</span><br />
                    <span data-i18n="hero.title2">через</span> <span class="relative inline-block">
                        <span class="text-brand-600" data-i18n="hero.titleAccent">Telegram</span>
                        <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 200 12"
                            preserveAspectRatio="none">
                            <path d="M2 8 C 60 2, 140 2, 198 8" stroke="#f97316" stroke-width="3"
                                stroke-linecap="round" fill="none" />
                        </svg>
                    </span>
                    <span data-i18n="hero.title3">— автоматически</span>
                </h1>

                <p class="reveal mt-7 text-lg lg:text-xl text-ink-700 max-w-2xl leading-relaxed"
                    data-i18n="hero.subtitle">
                    Клиенты не знают, где их заказ. Менеджеры тратят часы на звонки и сообщения «уточнить статус».
                    Подключите бота за 2 минуты — и каждое событие из МойСклад превратится в уведомление в Telegram. На
                    языке клиента.
                </p>

                <div class="reveal mt-9 flex flex-col sm:flex-row gap-3">
                    @auth
                        <a href="{{ auth()->user()->isSuperAdmin() ? route('admin.dashboard') : route('dashboard') }}"
                            class="group inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-4 rounded-2xl shadow-cardLg transition">
                            <span>{{ auth()->user()->isSuperAdmin() ? 'Админ панель' : 'В кабинет' }}</span>
                            <svg viewBox="0 0 20 20" class="w-4 h-4 group-hover:translate-x-0.5 transition"
                                fill="currentColor">
                                <path
                                    d="M7.05 4.55a1 1 0 0 1 1.4 0l4.95 4.95a1 1 0 0 1 0 1.4l-4.95 4.95a1 1 0 1 1-1.4-1.4L10.59 11H4a1 1 0 1 1 0-2h6.59L7.05 5.95a1 1 0 0 1 0-1.4z" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="group inline-flex items-center justify-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-semibold px-6 py-4 rounded-2xl shadow-cardLg transition">
                            <span data-i18n="hero.ctaPrimary">Начать бесплатно</span>
                            <svg viewBox="0 0 20 20" class="w-4 h-4 group-hover:translate-x-0.5 transition"
                                fill="currentColor">
                                <path
                                    d="M7.05 4.55a1 1 0 0 1 1.4 0l4.95 4.95a1 1 0 0 1 0 1.4l-4.95 4.95a1 1 0 1 1-1.4-1.4L10.59 11H4a1 1 0 1 1 0-2h6.59L7.05 5.95a1 1 0 0 1 0-1.4z" />
                            </svg>
                        </a>
                    @endauth
                    <a href="#demo"
                        class="inline-flex items-center justify-center gap-2 bg-white hover:bg-ink-50 text-ink-900 font-semibold px-6 py-4 rounded-2xl border border-ink-200 shadow-card transition">
                        <svg viewBox="0 0 20 20" class="w-4 h-4 text-brand-600" fill="currentColor">
                            <path d="M6 4l10 6-10 6V4z" />
                        </svg>
                        <span data-i18n="hero.ctaSecondary">Смотреть демо</span>
                    </a>
                </div>

                <div class="reveal mt-10 flex flex-wrap items-center gap-x-8 gap-y-4 text-sm text-ink-500">
                    <div class="flex items-center gap-2"><span class="text-emerald-500">✓</span> <span
                            data-i18n="hero.bullet1">Без программирования</span></div>
                    <div class="flex items-center gap-2"><span class="text-emerald-500">✓</span> <span
                            data-i18n="hero.bullet2">Настройка за 2 минуты</span></div>
                    <div class="flex items-center gap-2"><span class="text-emerald-500">✓</span> <span
                            data-i18n="hero.bullet3">4 языка из коробки</span></div>
                </div>
            </div>

            <!-- PHONE MOCKUP -->
            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                <div class="relative reveal">
                    <div
                        class="float absolute -left-12 top-16 z-20 hidden sm:flex items-center gap-2 bg-white px-3 py-2 rounded-xl shadow-card border border-ink-200">
                        <span class="w-8 h-8 rounded-lg bg-accent-500/10 grid place-items-center text-accent-500">
                            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                                <path
                                    d="M3 7l9-4 9 4v10l-9 4-9-4V7zm9-1.8L5.6 7 12 9.8 18.4 7 12 5.2zM5 9.1V16l6 2.7v-6.8L5 9.1zm14 0l-6 3v6.8L19 16V9.1z" />
                            </svg>
                        </span>
                        <div class="text-xs">
                            <div class="font-semibold" data-i18n="hero.chipMsTitle">МойСклад</div>
                            <div class="text-ink-500" data-i18n="hero.chipMsSub">отгрузка #1234</div>
                        </div>
                    </div>
                    <div
                        class="absolute -right-6 bottom-24 z-20 hidden sm:flex items-center gap-2 bg-ink-900 text-white px-3 py-2 rounded-xl shadow-card">
                        <span class="w-7 h-7 rounded-lg bg-brand-600 grid place-items-center">
                            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                                <path
                                    d="M21.5 3.5L2.7 11.2c-.9.4-.9 1.6.1 1.9l4.6 1.4 1.7 5.4c.3.9 1.4 1.1 2 .3l2.4-3 4.7 3.5c.8.6 2 .1 2.1-.9L22.9 4.6c.1-.9-.7-1.5-1.4-1.1z" />
                            </svg>
                        </span>
                        <div class="text-xs">
                            <div class="font-semibold" data-i18n="hero.chipFastTitle">~1.4 c</div>
                            <div class="text-ink-300" data-i18n="hero.chipFastSub">до доставки</div>
                        </div>
                    </div>

                    <div class="phone shadow-phone">
                        <div class="phone-notch"></div>
                        <div class="phone-screen chat-bg">
                            <div
                                class="absolute top-0 inset-x-0 pt-9 pb-2 px-3 bg-white/95 backdrop-blur border-b border-ink-200 z-10 flex items-center gap-2">
                                <button class="text-brand-600 text-sm">‹</button>
                                <div
                                    class="w-8 h-8 rounded-full bg-brand-600 grid place-items-center text-white text-xs font-bold">
                                    SH</div>
                                <div class="leading-tight flex-1">
                                    <div class="text-[13px] font-semibold" data-i18n="hero.chatHeader">ShopBot ·
                                        Уведомления</div>
                                    <div class="text-[10px] text-ink-500" data-i18n="hero.chatStatus">в сети</div>
                                </div>
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-ink-500" fill="currentColor">
                                    <path
                                        d="M12 6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4z" />
                                </svg>
                            </div>

                            <div class="absolute inset-x-0 bottom-0 top-20 px-3 pb-3 flex flex-col gap-2 justify-end">
                                <div class="text-center"><span
                                        class="text-[10px] text-ink-700 bg-white/70 rounded-full px-2 py-0.5"
                                        data-i18n="hero.chatToday">сегодня</span></div>

                                <div class="bubble bubble-in" data-i18n-html="hero.chatMsg1">
                                    Здравствуйте, Анвар! 👋<br />Ваш заказ <b>#1234</b> принят в работу.<span
                                        class="time">10:24</span>
                                </div>

                                <div class="bubble bubble-in" data-i18n-html="hero.chatMsg2">
                                    💳 Оплата <b>650 000 сум</b> зачислена. Спасибо!<span class="time">11:02</span>
                                </div>

                                <div class="bubble bubble-in"
                                    style="background:linear-gradient(180deg,#ffffff,#f8fcff)">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="w-7 h-7 rounded-lg bg-accent-500/10 grid place-items-center text-accent-500">📦</span>
                                        <b class="text-[13px]" data-i18n="hero.chatMsg3Title">Заказ #1234 отгружен</b>
                                    </div>
                                    <span data-i18n-html="hero.chatMsg3Body">Курьер заберёт сегодня до
                                        18:00.<br />Трек: <span class="text-brand-600">UZ-887214</span></span>
                                    <span class="time">14:31 <span class="check">✓✓</span></span>
                                </div>

                                <div class="text-center mt-1">
                                    <span
                                        class="text-[10px] text-ink-700 bg-white/70 rounded-full px-2 py-0.5 inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <span data-i18n="hero.chatSentVia">отправлено через MyCode</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOGO STRIP -->
        <div class="relative max-w-7xl mx-auto px-5 lg:px-8 mt-20 lg:mt-28">
            <div class="text-center text-xs uppercase tracking-[0.2em] text-ink-500 font-medium"
                data-i18n="hero.logos">Используют команды из</div>
            <div class="mt-6 flex flex-wrap items-center justify-center gap-x-10 gap-y-4 text-ink-500">
                <div class="text-base font-semibold opacity-70 hover:opacity-100 transition">🇺🇿 Tashkent Bazar</div>
                <div class="text-base font-semibold opacity-70 hover:opacity-100 transition">Korzinka.ru</div>
                <div class="text-base font-semibold opacity-70 hover:opacity-100 transition">Optomshop</div>
                <div class="text-base font-semibold opacity-70 hover:opacity-100 transition">Buyum.uz</div>
                <div class="text-base font-semibold opacity-70 hover:opacity-100 transition">Almaty Wholesale</div>
                <div class="text-base font-semibold opacity-70 hover:opacity-100 transition">Dushanbe Retail</div>
            </div>
        </div>
    </section>

    <!-- =================== HOW IT WORKS =================== -->
    <section id="how" class="py-24 lg:py-32 bg-ink-50">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="max-w-2xl reveal">
                <div class="text-sm font-semibold text-brand-600 uppercase tracking-wider" data-i18n="how.kicker">Как
                    работает</div>
                <h2 class="mt-3 text-4xl lg:text-5xl font-extrabold tracking-tight" data-i18n="how.title">Три шага.
                    Готово за 2 минуты.</h2>
                <p class="mt-4 text-lg text-ink-500" data-i18n="how.sub">Не нужен разработчик, не нужны интеграторы.
                    Всё через интерфейс.</p>
            </div>

            <div class="mt-14 grid md:grid-cols-3 gap-5 lg:gap-6">
                <!-- Step 1 -->
                <div class="step-card relative bg-white border border-ink-200 rounded-3xl p-7 shadow-card"
                    data-step="1">
                    <div class="step-bar absolute top-0 left-7 right-7 h-1 rounded-b-full bg-ink-200"></div>
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="step-num w-10 h-10 rounded-xl bg-ink-100 text-ink-700 font-bold grid place-items-center transition">
                            01</div>
                        <span class="text-xs font-medium text-ink-500" data-i18n="how.s1Time">~30 сек</span>
                    </div>
                    <h3 class="text-xl font-bold tracking-tight" data-i18n="how.s1Title">Подключите бота</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n-html="how.s1Desc">Вставьте
                        токен от <span class="font-mono text-ink-700">BotFather</span> и настройте
                        приветствие на
                        четырёх языках.</p>
                    <div
                        class="mt-5 rounded-xl border border-ink-200 bg-ink-50 p-3 font-mono text-[11px] text-ink-700">
                        <div class="text-[10px] text-ink-500 mb-1" data-i18n="how.s1FieldLabel">BOT TOKEN</div>
                        <div class="flex items-center gap-2">
                            <span class="text-brand-600">7458291042</span>:<span class="text-ink-900">AAEx…UkPq</span>
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        </div>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="step-card relative bg-white border border-ink-200 rounded-3xl p-7 shadow-card"
                    data-step="2">
                    <div class="step-bar absolute top-0 left-7 right-7 h-1 rounded-b-full bg-ink-200"></div>
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="step-num w-10 h-10 rounded-xl bg-ink-100 text-ink-700 font-bold grid place-items-center transition">
                            02</div>
                        <span class="text-xs font-medium text-ink-500" data-i18n="how.s2Time">~30 сек</span>
                    </div>
                    <h3 class="text-xl font-bold tracking-tight" data-i18n="how.s2Title">Настройте МойСклад</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n="how.s2Desc">Скопируйте URL и
                        секрет, вставьте в настройки вебхуков МойСклад. Готово.</p>
                    <div class="mt-5 rounded-xl border border-ink-200 bg-ink-50 p-3 text-[11px]">
                        <div class="text-[10px] text-ink-500 mb-1 uppercase" data-i18n="how.s2UrlLabel">WEBHOOK URL
                        </div>
                        <div class="font-mono text-ink-700 truncate">https://api.mycode.uz/<span
                                class="text-accent-500">hooks/8a2f</span></div>
                        <div class="mt-2 text-[10px] text-ink-500 uppercase" data-i18n="how.s2SecretLabel">SECRET
                        </div>
                        <div class="font-mono text-ink-700">••••••••<span class="text-ink-900">7d4c2</span></div>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="step-card relative bg-white border border-ink-200 rounded-3xl p-7 shadow-card"
                    data-step="3">
                    <div class="step-bar absolute top-0 left-7 right-7 h-1 rounded-b-full bg-ink-200"></div>
                    <div class="flex items-center justify-between mb-5">
                        <div
                            class="step-num w-10 h-10 rounded-xl bg-ink-100 text-ink-700 font-bold grid place-items-center transition">
                            03</div>
                        <span class="text-xs font-medium text-ink-500" data-i18n="how.s3Time">далее —
                            автоматически</span>
                    </div>
                    <h3 class="text-xl font-bold tracking-tight" data-i18n="how.s3Title">Клиенты получают уведомления
                    </h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n="how.s3Desc">Каждое событие в
                        МойСклад превращается в сообщение в Telegram. Без участия менеджеров.</p>
                    <div
                        class="mt-5 rounded-xl border border-ink-200 bg-gradient-to-br from-brand-50 to-white p-3 text-[12px]">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-7 h-7 rounded-lg bg-brand-600 grid place-items-center text-white text-xs">✓</span>
                            <div>
                                <div class="font-semibold" data-i18n="how.s3Delivered">Доставлено</div>
                                <div class="text-ink-500 text-[10px]" data-i18n="how.s3DeliveredSub">1 432 сообщения
                                    за сегодня</div>
                            </div>
                            <div class="ml-auto text-right">
                                <div class="text-brand-600 font-bold">99.8%</div>
                                <div class="text-[10px] text-ink-500" data-i18n="how.s3Metric">deliverability</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =================== FEATURES =================== -->
    <section id="features" class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 reveal">
                <div class="max-w-2xl">
                    <div class="text-sm font-semibold text-brand-600 uppercase tracking-wider"
                        data-i18n="feat.kicker">Возможности</div>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-extrabold tracking-tight" data-i18n="feat.title">Всё,
                        что нужно для отдела продаж</h2>
                </div>
                <p class="text-ink-500 text-lg max-w-md" data-i18n="feat.sub">От первого «/start» до VIP-рассылок —
                    единый инструмент для коммуникаций с клиентом.</p>
            </div>

            <div class="reveal-stagger mt-14 grid sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                <div
                    class="group bg-white border border-ink-200 rounded-3xl p-7 shadow-card hover:shadow-cardLg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-brand-50 grid place-items-center mb-5 group-hover:bg-brand-600 transition">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-brand-600 group-hover:text-white transition"
                            fill="currentColor">
                            <path
                                d="M12 2a3 3 0 0 1 3 3v2h1a4 4 0 0 1 4 4v3h1a1 1 0 1 1 0 2h-1v3a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4v-3H3a1 1 0 1 1 0-2h1v-3a4 4 0 0 1 4-4h1V5a3 3 0 0 1 3-3zm-3 9a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H9zm0 3a1.25 1.25 0 1 1 0 2.5A1.25 1.25 0 0 1 9 14zm6 0a1.25 1.25 0 1 1 0 2.5A1.25 1.25 0 0 1 15 14z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold tracking-tight" data-i18n="feat.f1T">Несколько ботов</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n="feat.f1D">Создавайте
                        отдельного бота под каждое направление — розница, опт, b2b. Один аккаунт, без ограничений.</p>
                </div>
                <div
                    class="group bg-white border border-ink-200 rounded-3xl p-7 shadow-card hover:shadow-cardLg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-brand-50 grid place-items-center mb-5 group-hover:bg-brand-600 transition">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-brand-600 group-hover:text-white transition"
                            fill="currentColor">
                            <path
                                d="M12 2a10 10 0 1 1 0 20 10 10 0 0 1 0-20zm6.93 9h-3.04c-.15-2.31-.74-4.4-1.6-5.92A8.03 8.03 0 0 1 18.93 11zM12 4c.96 0 2.27 2.78 2.45 7h-4.9C9.73 6.78 11.04 4 12 4zM5.07 11A8.03 8.03 0 0 1 9.71 5.08c-.86 1.52-1.45 3.6-1.6 5.92H5.07zm0 2h3.04c.15 2.31.74 4.4 1.6 5.92A8.03 8.03 0 0 1 5.07 13zM12 20c-.96 0-2.27-2.78-2.45-7h4.9c-.18 4.22-1.49 7-2.45 7zm2.29-.08c.86-1.52 1.45-3.6 1.6-5.92h3.04a8.03 8.03 0 0 1-4.64 5.92z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold tracking-tight" data-i18n="feat.f2T">4 языка</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n="feat.f2D">uz · ru · tj · kk —
                        каждый клиент получает сообщение на родном языке. Система определяет автоматически.</p>
                    <div class="mt-4 flex gap-1.5 text-[11px] font-mono">
                        <span class="px-2 py-0.5 rounded-md bg-ink-100 text-ink-700">uz</span>
                        <span class="px-2 py-0.5 rounded-md bg-ink-100 text-ink-700">ru</span>
                        <span class="px-2 py-0.5 rounded-md bg-ink-100 text-ink-700">tj</span>
                        <span class="px-2 py-0.5 rounded-md bg-ink-100 text-ink-700">kk</span>
                    </div>
                </div>
                <div
                    class="group bg-white border border-ink-200 rounded-3xl p-7 shadow-card hover:shadow-cardLg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-accent-500/10 grid place-items-center mb-5 group-hover:bg-accent-500 transition">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-accent-500 group-hover:text-white transition"
                            fill="currentColor">
                            <path d="M13 2L3 14h7v8l10-12h-7V2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold tracking-tight" data-i18n="feat.f3T">Мгновенно</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n-html="feat.f3D">От события в
                        МойСклад до сообщения в Telegram — <span class="font-semibold text-ink-900">меньше 2
                            секунд</span>. Очередь, ретраи, гарантия доставки.</p>
                </div>
                <div
                    class="group bg-white border border-ink-200 rounded-3xl p-7 shadow-card hover:shadow-cardLg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-brand-50 grid place-items-center mb-5 group-hover:bg-brand-600 transition">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-brand-600 group-hover:text-white transition"
                            fill="currentColor">
                            <path
                                d="M9 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm8 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM2 19a7 7 0 0 1 14 0v1H2v-1zm15 0v1h5v-1a5 5 0 0 0-7.55-4.3A8.96 8.96 0 0 1 17 19z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold tracking-tight" data-i18n="feat.f4T">Группы и рассылки</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n="feat.f4D">Сегментируйте
                        VIP-клиентов и оптовиков. Поздравления, акции, опросники — точечно по группам.</p>
                </div>
                <div
                    class="group bg-white border border-ink-200 rounded-3xl p-7 shadow-card hover:shadow-cardLg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-accent-500/10 grid place-items-center mb-5 group-hover:bg-accent-500 transition">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-accent-500 group-hover:text-white transition"
                            fill="currentColor">
                            <path d="M3 4h13l4 4v12H3V4zm2 2v12h13V9h-4V6H5zm3 3h4v2H8V9zm0 4h8v2H8v-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold tracking-tight" data-i18n="feat.f5T">Все операции МойСклад</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n="feat.f5D">Отгрузка, оплата
                        (приход/расход), возврат, поступление товара — гибкие шаблоны под каждое событие.</p>
                </div>
                <div
                    class="group bg-white border border-ink-200 rounded-3xl p-7 shadow-card hover:shadow-cardLg hover:-translate-y-1 transition-all duration-300">
                    <div
                        class="w-12 h-12 rounded-2xl bg-brand-50 grid place-items-center mb-5 group-hover:bg-brand-600 transition">
                        <svg viewBox="0 0 24 24" class="w-6 h-6 text-brand-600 group-hover:text-white transition"
                            fill="currentColor">
                            <path
                                d="M12 1l9 4v6c0 5.5-3.8 10.7-9 12-5.2-1.3-9-6.5-9-12V5l9-4zm0 6a2 2 0 0 0-1 3.7V14h2v-3.3A2 2 0 0 0 12 7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold tracking-tight" data-i18n="feat.f6T">Безопасно</h3>
                    <p class="mt-2 text-ink-500 text-[15px] leading-relaxed" data-i18n="feat.f6D">Токены ботов и
                        API-ключи шифруются. Вебхуки защищены секретом и проверкой подписи.</p>
                </div>
            </div>

            <!-- Templates snippet -->
            <div
                class="reveal mt-12 bg-ink-900 text-ink-100 rounded-3xl p-6 lg:p-8 grid lg:grid-cols-5 gap-6 items-center">
                <div class="lg:col-span-2">
                    <div class="text-sm font-semibold text-brand-500 uppercase tracking-wider"
                        data-i18n="feat.tplKicker">Шаблоны</div>
                    <h3 class="mt-2 text-2xl font-bold tracking-tight" data-i18n="feat.tplTitle">Свой шаблон под
                        каждое событие</h3>
                    <p class="mt-3 text-ink-300 text-[15px]" data-i18n="feat.tplDesc">Перетаскивайте переменные из
                        МойСклад — заказ, сумма, дата, имя клиента. Никакого кода.</p>
                </div>
                <div
                    class="lg:col-span-3 bg-[#0b1220] border border-ink-700/60 rounded-2xl p-5 font-mono text-[13px] leading-relaxed">
                    <div class="text-ink-500 text-[11px] mb-2" data-i18n="feat.tplComment">// шаблон «отгрузка»</div>
                    <div data-i18n-tpl="feat.tplLine1">📦 Здравствуйте, <span
                            class="text-accent-500">{customer_name}</span>!</div>
                    <div data-i18n-tpl="feat.tplLine2">Ваш заказ <span class="text-accent-500">#{order_number}</span>
                        отгружен <span class="text-accent-500">{date}</span>.</div>
                    <div data-i18n-tpl="feat.tplLine3">Сумма к оплате: <span class="text-accent-500">{amount}</span>
                        сум.</div>
                    <div data-i18n-tpl="feat.tplLine4">Трек номер: <span class="text-brand-500">{tracking}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =================== DEMO =================== -->
    <section id="demo" class="py-24 lg:py-32 bg-ink-900 text-white overflow-hidden relative">
        <div class="absolute inset-0 opacity-30"
            style="background-image: radial-gradient(60% 60% at 20% 30%, rgba(2,132,199,.5) 0%, transparent 60%), radial-gradient(50% 50% at 85% 75%, rgba(249,115,22,.35) 0%, transparent 60%);">
        </div>
        <div class="relative max-w-7xl mx-auto px-5 lg:px-8 grid lg:grid-cols-2 gap-14 items-center">
            <div class="reveal">
                <div class="text-sm font-semibold text-brand-500 uppercase tracking-wider" data-i18n="demo.kicker">
                    Живое демо</div>
                <h2 class="mt-3 text-4xl lg:text-5xl font-extrabold tracking-tight" data-i18n="demo.title">Как клиент
                    подключается к боту</h2>
                <p class="mt-5 text-lg text-ink-300 leading-relaxed" data-i18n="demo.sub">Простой флоу: четыре экрана
                    — и клиент в базе, с языком, именем и номером. Дальше всё автоматически.</p>

                <ol class="mt-8 space-y-4">
                    <li class="flex gap-3"><span
                            class="w-7 h-7 shrink-0 rounded-lg bg-white/10 text-white font-bold text-sm grid place-items-center">1</span>
                        <div>
                            <div class="font-semibold" data-i18n="demo.step1T">/start</div>
                            <div class="text-ink-300 text-sm" data-i18n="demo.step1D">Бот предлагает выбрать язык
                                интерфейса.</div>
                        </div>
                    </li>
                    <li class="flex gap-3"><span
                            class="w-7 h-7 shrink-0 rounded-lg bg-white/10 text-white font-bold text-sm grid place-items-center">2</span>
                        <div>
                            <div class="font-semibold" data-i18n="demo.step2T">Имя и фамилия</div>
                            <div class="text-ink-300 text-sm" data-i18n="demo.step2D">Сохраняются в карточку клиента в
                                МойСклад.</div>
                        </div>
                    </li>
                    <li class="flex gap-3"><span
                            class="w-7 h-7 shrink-0 rounded-lg bg-white/10 text-white font-bold text-sm grid place-items-center">3</span>
                        <div>
                            <div class="font-semibold" data-i18n="demo.step3T">Номер телефона</div>
                            <div class="text-ink-300 text-sm" data-i18n="demo.step3D">Через нативный share-contact
                                Telegram.</div>
                        </div>
                    </li>
                    <li class="flex gap-3"><span
                            class="w-7 h-7 shrink-0 rounded-lg bg-white/10 text-white font-bold text-sm grid place-items-center">4</span>
                        <div>
                            <div class="font-semibold" data-i18n="demo.step4T">Подключён</div>
                            <div class="text-ink-300 text-sm" data-i18n="demo.step4D">Первое уведомление приходит
                                автоматически.</div>
                        </div>
                    </li>
                </ol>

                <div class="mt-8 flex items-center gap-3 text-sm text-ink-300">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span data-i18n="demo.autoplay">Демо проигрывается автоматически</span>
                    </span>
                </div>
            </div>

            <!-- Demo phone -->
            <div class="reveal justify-self-center lg:justify-self-end">
                <div class="phone shadow-phone">
                    <div class="phone-notch"></div>
                    <div class="phone-screen chat-bg">
                        <div
                            class="absolute top-0 inset-x-0 pt-9 pb-2 px-3 bg-white/95 backdrop-blur border-b border-ink-200 z-10 flex items-center gap-2">
                            <button class="text-brand-600 text-sm">‹</button>
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 grid place-items-center text-white text-xs font-bold">
                                TC</div>
                            <div class="leading-tight flex-1">
                                <div class="text-[13px] font-semibold" data-i18n="demo.chatHeaderTitle">MyCode
                                    Demo</div>
                                <div class="text-[10px] text-ink-500"><span id="demo-status"
                                        data-i18n="demo.chatStatusTyping">печатает…</span></div>
                            </div>
                        </div>
                        <div id="demo-chat"
                            class="absolute inset-x-0 bottom-0 top-20 px-3 pb-3 flex flex-col gap-1.5 justify-end overflow-hidden">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =================== INTEGRATION =================== -->
    <section id="integration" class="py-24 lg:py-32 bg-ink-50">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="reveal flex items-center justify-center gap-4 sm:gap-8">
                <div class="bg-white border border-ink-200 rounded-2xl px-5 py-4 shadow-card flex items-center gap-3">
                    <span
                        class="w-10 h-10 rounded-xl bg-accent-500 grid place-items-center text-white font-bold">МС</span>
                    <div>
                        <div class="font-bold tracking-tight leading-tight" data-i18n="integ.msTagLeft">МойСклад</div>
                        <div class="text-[11px] text-ink-500" data-i18n="integ.msTagLeftSub">webhooks</div>
                    </div>
                </div>
                <div class="hidden sm:flex flex-1 max-w-[180px] items-center gap-1">
                    <div class="connector flex-1"></div>
                    <div class="w-6 h-6 rounded-full bg-brand-600 grid place-items-center text-white text-[10px]">→
                    </div>
                    <div class="connector flex-1"></div>
                </div>
                <div class="bg-white border border-ink-200 rounded-2xl px-5 py-4 shadow-card flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-brand-600 grid place-items-center text-white">
                        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor">
                            <path
                                d="M21.5 3.5L2.7 11.2c-.9.4-.9 1.6.1 1.9l4.6 1.4 1.7 5.4c.3.9 1.4 1.1 2 .3l2.4-3 4.7 3.5c.8.6 2 .1 2.1-.9L22.9 4.6c.1-.9-.7-1.5-1.4-1.1z" />
                        </svg>
                    </span>
                    <div>
                        <div class="font-bold tracking-tight leading-tight" data-i18n="integ.msTagRight">Telegram
                        </div>
                        <div class="text-[11px] text-ink-500" data-i18n="integ.msTagRightSub">клиент</div>
                    </div>
                </div>
            </div>

            <div class="mt-12 grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div class="reveal">
                    <div class="text-sm font-semibold text-accent-500 uppercase tracking-wider"
                        data-i18n="integ.kicker">Интеграция</div>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-extrabold tracking-tight">
                        <span data-i18n="integ.titleA">Главная интеграция —</span><br />
                        <span class="text-accent-500" data-i18n="integ.titleB">МойСклад</span>
                    </h2>
                    <p class="mt-5 text-lg text-ink-500 leading-relaxed" data-i18n="integ.sub">Настройка занимает 2
                        минуты — просто вставьте URL и секретный ключ в раздел «Уведомления → Вебхуки» в МойСклад.
                        Поддерживаются все ключевые типы операций.</p>

                    <div class="mt-7 grid grid-cols-2 gap-3 max-w-md">
                        <div class="bg-white border border-ink-200 rounded-xl px-4 py-3 text-sm">
                            <div class="font-semibold" data-i18n="integ.opShip">Отгрузка</div>
                            <code class="text-[11px] text-ink-500 font-mono">demand</code>
                        </div>
                        <div class="bg-white border border-ink-200 rounded-xl px-4 py-3 text-sm">
                            <div class="font-semibold" data-i18n="integ.opPay">Оплата</div>
                            <code class="text-[11px] text-ink-500 font-mono">paymentin/out</code>
                        </div>
                        <div class="bg-white border border-ink-200 rounded-xl px-4 py-3 text-sm">
                            <div class="font-semibold" data-i18n="integ.opSupply">Приход товара</div>
                            <code class="text-[11px] text-ink-500 font-mono">supply</code>
                        </div>
                        <div class="bg-white border border-ink-200 rounded-xl px-4 py-3 text-sm">
                            <div class="font-semibold" data-i18n="integ.opReturn">Возврат</div>
                            <code class="text-[11px] text-ink-500 font-mono">salesreturn</code>
                        </div>
                    </div>
                </div>

                <!-- Mock webhook form -->
                <div class="reveal">
                    <div class="bg-ink-900 rounded-3xl p-6 lg:p-8 shadow-cardLg border border-ink-700/60">
                        <div class="flex items-center justify-between pb-4 border-b border-ink-700/60">
                            <div class="flex items-center gap-2">
                                <span
                                    class="w-8 h-8 rounded-lg bg-accent-500 grid place-items-center text-white text-xs font-bold">МС</span>
                                <div class="text-white">
                                    <div class="text-sm font-semibold" data-i18n="integ.formTitle">МойСклад →
                                        Настройки</div>
                                    <div class="text-[11px] text-ink-300" data-i18n="integ.formSub">Новый вебхук</div>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                            </div>
                        </div>
                        <div class="mt-5 space-y-4">
                            <div>
                                <div class="mock-label mb-1.5" data-i18n="integ.formUrl">URL вебхука</div>
                                <div
                                    class="mock-input rounded-lg px-3 py-2.5 font-mono text-sm flex items-center justify-between">
                                    <span>https://api.mycode.uz/hooks/8a2f1c</span>
                                    <span class="text-emerald-400 text-xs">✓</span>
                                </div>
                            </div>
                            <div>
                                <div class="mock-label mb-1.5" data-i18n="integ.formSecret">Секретный ключ</div>
                                <div
                                    class="mock-input rounded-lg px-3 py-2.5 font-mono text-sm flex items-center justify-between">
                                    <span>•••••••••••••••• 7d4c2</span>
                                    <button class="text-brand-500 text-xs hover:underline"
                                        data-i18n="integ.formShow">показать</button>
                                </div>
                            </div>
                            <div>
                                <div class="mock-label mb-1.5" data-i18n="integ.formEvent">Тип события</div>
                                <div class="flex flex-wrap gap-1.5">
                                    <span
                                        class="px-2.5 py-1 rounded-md bg-accent-500/15 text-accent-500 text-xs font-medium">demand</span>
                                    <span
                                        class="px-2.5 py-1 rounded-md bg-accent-500/15 text-accent-500 text-xs font-medium">paymentin</span>
                                    <span
                                        class="px-2.5 py-1 rounded-md bg-accent-500/15 text-accent-500 text-xs font-medium">paymentout</span>
                                    <span
                                        class="px-2.5 py-1 rounded-md bg-accent-500/15 text-accent-500 text-xs font-medium">supply</span>
                                    <span
                                        class="px-2.5 py-1 rounded-md bg-accent-500/15 text-accent-500 text-xs font-medium">salesreturn</span>
                                </div>
                            </div>
                            <div class="pt-2 flex items-center justify-between">
                                <div class="text-ink-300 text-xs"><span data-i18n="integ.formAction">Действие:</span>
                                    <span class="text-white" data-i18n="integ.formActionVal">создано /
                                        обновлено</span>
                                </div>
                                <button
                                    class="bg-accent-500 hover:bg-accent-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition"
                                    data-i18n="integ.formSave">Сохранить</button>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 text-center text-sm text-ink-500" data-i18n="integ.note">Это копия формы в МойСклад
                        — данные вставляются автоматически из панели MyCode</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =================== PRICING =================== -->
    <section id="pricing" class="py-24 lg:py-32">
        <div class="max-w-7xl mx-auto px-5 lg:px-8">
            <div class="text-center max-w-2xl mx-auto reveal">
                <div class="text-sm font-semibold text-brand-600 uppercase tracking-wider" data-i18n="price.kicker">
                    Тарифы</div>
                <h2 class="mt-3 text-4xl lg:text-5xl font-extrabold tracking-tight" data-i18n="price.title">Простые
                    цены. Без скрытых платежей.</h2>
                <p class="mt-4 text-lg text-ink-500" data-i18n="price.sub">Начните бесплатно. Платите, только когда
                    вырастете.</p>
            </div>

            <div class="reveal-stagger mt-14 grid md:grid-cols-3 gap-5 lg:gap-6 items-stretch">
                <!-- Старт -->
                <div class="relative bg-white border border-ink-200 rounded-3xl p-8 shadow-card flex flex-col">
                    <div class="text-xs font-semibold text-ink-500 uppercase tracking-wider" data-i18n="price.p1Name">
                        Старт</div>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-5xl font-extrabold tracking-tight">$0</span>
                        <span class="text-ink-500" data-i18n="price.perMonth">/мес</span>
                    </div>
                    <p class="mt-2 text-ink-500 text-sm" data-i18n="price.p1Sub">Чтобы попробовать на маленьком
                        потоке.</p>
                    <ul class="mt-6 space-y-2.5 text-sm flex-1">
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span data-i18n="price.p1F1">1
                                Telegram-бот</span></li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span data-i18n="price.p1F2">До
                                500 клиентов</span></li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span data-i18n="price.p1F3">4
                                языка</span></li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span
                                data-i18n="price.p1F4">Базовые шаблоны</span></li>
                        <li class="flex gap-2 text-ink-300"><span>—</span> <span data-i18n="price.p1F5">Группы и
                                рассылки</span></li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="mt-7 block text-center bg-ink-100 hover:bg-ink-200 text-ink-900 font-semibold py-3 rounded-xl transition"
                        data-i18n="price.p1Cta">Начать бесплатно</a>
                </div>

                <!-- Бизнес -->
                <div class="popular-glow relative bg-white rounded-3xl p-8 shadow-cardLg flex flex-col">
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-gradient-to-r from-brand-600 to-accent-500 text-white text-[11px] font-bold tracking-wider uppercase"
                        data-i18n="price.popular">Популярный</div>
                    <div class="text-xs font-semibold text-brand-600 uppercase tracking-wider"
                        data-i18n="price.p2Name">Бизнес</div>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-5xl font-extrabold tracking-tight">$29</span>
                        <span class="text-ink-500" data-i18n="price.perMonth">/мес</span>
                    </div>
                    <p class="mt-2 text-ink-500 text-sm" data-i18n="price.p2Sub">Для растущих интернет-магазинов и
                        оптовых складов.</p>
                    <ul class="mt-6 space-y-2.5 text-sm flex-1">
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span data-i18n="price.p2F1">До
                                5 ботов</span></li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span data-i18n="price.p2F2">До
                                5 000 клиентов</span></li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span
                                data-i18n="price.p2F3">Группы и рассылки</span></li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span
                                data-i18n="price.p2F4">Кастомные шаблоны</span></li>
                        <li class="flex gap-2"><span class="text-brand-600">✓</span> <span
                                data-i18n="price.p2F5">Приоритетная поддержка</span></li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="mt-7 block text-center bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-xl transition shadow-card"
                        data-i18n="price.p2Cta">Выбрать Бизнес</a>
                </div>

                <!-- Корпоратив -->
                <div class="relative bg-ink-900 text-white rounded-3xl p-8 shadow-card flex flex-col">
                    <div class="text-xs font-semibold text-accent-500 uppercase tracking-wider"
                        data-i18n="price.p3Name">Корпоратив</div>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-5xl font-extrabold tracking-tight">$99</span>
                        <span class="text-ink-300" data-i18n="price.perMonth">/мес</span>
                    </div>
                    <p class="mt-2 text-ink-300 text-sm" data-i18n="price.p3Sub">Для сетей и компаний с большим
                        клиентским потоком.</p>
                    <ul class="mt-6 space-y-2.5 text-sm flex-1">
                        <li class="flex gap-2"><span class="text-accent-500">✓</span> <span
                                data-i18n="price.p3F1">Безлимит ботов</span></li>
                        <li class="flex gap-2"><span class="text-accent-500">✓</span> <span
                                data-i18n="price.p3F2">Безлимит клиентов</span></li>
                        <li class="flex gap-2"><span class="text-accent-500">✓</span> <span
                                data-i18n="price.p3F3">SLA 99.95%</span></li>
                        <li class="flex gap-2"><span class="text-accent-500">✓</span> <span
                                data-i18n="price.p3F4">Выделенный менеджер</span></li>
                        <li class="flex gap-2"><span class="text-accent-500">✓</span> <span
                                data-i18n="price.p3F5">Кастомная разработка</span></li>
                    </ul>
                    <a href="{{ route('register') }}"
                        class="mt-7 block text-center bg-white hover:bg-ink-100 text-ink-900 font-semibold py-3 rounded-xl transition"
                        data-i18n="price.p3Cta">Связаться с нами</a>
                </div>
            </div>
        </div>
    </section>

    <!-- =================== FAQ =================== -->
    <section id="faq" class="py-24 lg:py-32 bg-ink-50">
        <div class="max-w-4xl mx-auto px-5 lg:px-8">
            <div class="text-center reveal">
                <div class="text-sm font-semibold text-brand-600 uppercase tracking-wider" data-i18n="faq.kicker">
                    Частые вопросы</div>
                <h2 class="mt-3 text-4xl lg:text-5xl font-extrabold tracking-tight" data-i18n="faq.title">Коротко о
                    главном</h2>
            </div>

            <div class="reveal-stagger mt-12 space-y-3">
                <details
                    class="faq-item group bg-white border border-ink-200 rounded-2xl px-6 py-5 shadow-card open:shadow-cardLg">
                    <summary class="flex items-center justify-between gap-4">
                        <span class="font-semibold text-lg tracking-tight" data-i18n="faq.q1">Нужно ли знать
                            программирование?</span>
                        <span
                            class="faq-icon w-8 h-8 shrink-0 rounded-full bg-ink-100 grid place-items-center text-ink-700 text-xl leading-none">+</span>
                    </summary>
                    <p class="mt-3 text-ink-500 leading-relaxed" data-i18n="faq.a1">Нет. Вся настройка делается через
                        веб-интерфейс: подключение бота, шаблоны, группы. Если умеете пользоваться МойСклад — справитесь
                        и здесь.</p>
                </details>
                <details
                    class="faq-item group bg-white border border-ink-200 rounded-2xl px-6 py-5 shadow-card open:shadow-cardLg">
                    <summary class="flex items-center justify-between gap-4">
                        <span class="font-semibold text-lg tracking-tight" data-i18n="faq.q2">Работает ли без
                            МойСклад?</span>
                        <span
                            class="faq-icon w-8 h-8 shrink-0 rounded-full bg-ink-100 grid place-items-center text-ink-700 text-xl leading-none">+</span>
                    </summary>
                    <p class="mt-3 text-ink-500 leading-relaxed" data-i18n="faq.a2">Пока нет — МойСклад это наш первый
                        и приоритетный коннектор. В ближайших релизах добавим Bitrix24 и 1С. Подпишитесь на обновления,
                        чтобы узнать первыми.</p>
                </details>
                <details
                    class="faq-item group bg-white border border-ink-200 rounded-2xl px-6 py-5 shadow-card open:shadow-cardLg">
                    <summary class="flex items-center justify-between gap-4">
                        <span class="font-semibold text-lg tracking-tight" data-i18n="faq.q3">Клиент получит
                            уведомление на своём языке?</span>
                        <span
                            class="faq-icon w-8 h-8 shrink-0 rounded-full bg-ink-100 grid place-items-center text-ink-700 text-xl leading-none">+</span>
                    </summary>
                    <p class="mt-3 text-ink-500 leading-relaxed" data-i18n-html="faq.a3">Да. При регистрации через
                        <span class="font-mono text-ink-900">/start</span> клиент выбирает язык, и все дальнейшие
                        сообщения приходят на нём. Поддерживаются O'zbek, Русский, Тоҷикӣ и Қарақалпақ.
                    </p>
                </details>
                <details
                    class="faq-item group bg-white border border-ink-200 rounded-2xl px-6 py-5 shadow-card open:shadow-cardLg">
                    <summary class="flex items-center justify-between gap-4">
                        <span class="font-semibold text-lg tracking-tight" data-i18n="faq.q4">Сколько ботов можно
                            подключить?</span>
                        <span
                            class="faq-icon w-8 h-8 shrink-0 rounded-full bg-ink-100 grid place-items-center text-ink-700 text-xl leading-none">+</span>
                    </summary>
                    <p class="mt-3 text-ink-500 leading-relaxed" data-i18n="faq.a4">На тарифе «Старт» — 1 бот, на
                        «Бизнес» — до 5, на «Корпоратив» — без ограничений. Каждый бот живёт в одной компании и имеет
                        свои настройки и базу клиентов.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- =================== CTA FOOTER =================== -->
    <section id="cta" class="relative overflow-hidden bg-ink-900 text-white py-24 lg:py-32">
        <div class="absolute inset-0 opacity-50"
            style="background-image: radial-gradient(50% 50% at 20% 30%, rgba(2,132,199,.6) 0%, transparent 60%), radial-gradient(40% 50% at 85% 70%, rgba(249,115,22,.45) 0%, transparent 60%);">
        </div>
        <div class="relative max-w-5xl mx-auto px-5 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">
            <div class="reveal">
                <h2 class="text-4xl lg:text-5xl font-extrabold tracking-tight leading-[1.05]">
                    <span data-i18n="cta.title1">Начните уведомлять</span><br />
                    <span data-i18n="cta.title2">клиентов сегодня.</span>
                </h2>
                <p class="mt-5 text-lg text-ink-300 max-w-md" data-i18n="cta.sub">Регистрация за 30 секунд. 14 дней
                    «Бизнес» бесплатно. Карты не нужны.</p>

                <div class="mt-8 flex items-center gap-4 text-sm">
                    <a href="#" class="flex items-center gap-2 text-ink-300 hover:text-white transition">
                        <span class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 grid place-items-center">
                            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                                <path
                                    d="M21.5 3.5L2.7 11.2c-.9.4-.9 1.6.1 1.9l4.6 1.4 1.7 5.4c.3.9 1.4 1.1 2 .3l2.4-3 4.7 3.5c.8.6 2 .1 2.1-.9L22.9 4.6c.1-.9-.7-1.5-1.4-1.1z" />
                            </svg>
                        </span>
                        telegramcrm_uz
                    </a>
                    <a href="#" class="flex items-center gap-2 text-ink-300 hover:text-white transition">
                        <span class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 grid place-items-center">
                            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                                <path d="M3 5h18v14H3V5zm2 2v.5l7 4.5 7-4.5V7H5zm0 2.9V17h14V9.9l-7 4.5-7-4.5z" />
                            </svg>
                        </span>
                        <span class="__cf_email__"
                            data-cfemail="2a424f4646456a5e4d495847045f50">[email&#160;protected]</span>
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="reveal bg-white/5 backdrop-blur border border-white/10 rounded-3xl p-6 lg:p-8">
                <form id="cta-form" class="space-y-4">
                    <div>
                        <label class="text-xs font-medium text-ink-300 uppercase tracking-wider"
                            data-i18n="cta.formName">Имя</label>
                        <input
                            class="mt-1.5 w-full bg-ink-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-ink-500 focus:outline-none focus:border-brand-500"
                            data-i18n-attr="placeholder:cta.formNamePh" placeholder="Анвар Каримов" />
                    </div>
                    <div>
                        <label class="text-xs font-medium text-ink-300 uppercase tracking-wider"
                            data-i18n="cta.formContact">Telegram или email</label>
                        <input
                            class="mt-1.5 w-full bg-ink-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-ink-500 focus:outline-none focus:border-brand-500"
                            data-i18n-attr="placeholder:cta.formContactPh" placeholder="anvar или anvarmail.uz" />
                    </div>
                    <div>
                        <label class="text-xs font-medium text-ink-300 uppercase tracking-wider"
                            data-i18n="cta.formCompany">Компания</label>
                        <input
                            class="mt-1.5 w-full bg-ink-900/60 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-ink-500 focus:outline-none focus:border-brand-500"
                            data-i18n-attr="placeholder:cta.formCompanyPh" placeholder="ООО Магазин" />
                    </div>
                    <a href="{{ route('register') }}"
                        class="block w-full text-center bg-brand-600 hover:bg-brand-700 transition text-white font-semibold py-3.5 rounded-xl shadow-cardLg"
                        data-i18n="cta.formBtn">Получить доступ →</a>
                    <p class="text-center text-xs text-ink-500" data-i18n="cta.formSpam">Никакого спама — только
                        полезные обновления продукта.</p>
                </form>
            </div>
        </div>

        <div
            class="relative max-w-7xl mx-auto px-5 lg:px-8 mt-20 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-ink-500">
            <div class="flex items-center gap-2.5">
                <span class="w-7 h-7 rounded-lg bg-brand-600 grid place-items-center">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="currentColor">
                        <path
                            d="M21.5 3.5L2.7 11.2c-.9.4-.9 1.6.1 1.9l4.6 1.4 1.7 5.4c.3.9 1.4 1.1 2 .3l2.4-3 4.7 3.5c.8.6 2 .1 2.1-.9L22.9 4.6c.1-.9-.7-1.5-1.4-1.1z" />
                    </svg>
                </span>
                <span class="font-semibold text-ink-300">MyCode</span>
                <span data-i18n="cta.footerCopy">© 2026 · Ташкент</span>
            </div>
            <div class="flex gap-6">
                <a href="#" class="hover:text-white transition" data-i18n="cta.footerDocs">Документация</a>
                <a href="#" class="hover:text-white transition" data-i18n="cta.footerPolicy">Политика</a>
                <a href="#" class="hover:text-white transition" data-i18n="cta.footerStatus">Статус</a>
            </div>
        </div>
    </section>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="i18n.js"></script>
    <script>
        /* ----- Reveal on scroll ----- */
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('in');
                    io.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -40px 0px'
        });
        document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));

        /* ----- Active step highlight on scroll ----- */
        const stepCards = document.querySelectorAll('.step-card');
        const stepIO = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting && e.intersectionRatio > 0.5) {
                    stepCards.forEach(c => c.classList.remove('active'));
                    e.target.classList.add('active');
                }
            });
        }, {
            threshold: [0.5, 0.75]
        });
        stepCards.forEach(c => stepIO.observe(c));
        const howSection = document.getElementById('how');
        let stepCycle = null;
        const howIO = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting && window.innerWidth >= 768) {
                    let i = 0;
                    stepCards.forEach(c => c.classList.remove('active'));
                    stepCards[0]?.classList.add('active');
                    if (stepCycle) clearInterval(stepCycle);
                    stepCycle = setInterval(() => {
                        i = (i + 1) % stepCards.length;
                        stepCards.forEach(c => c.classList.remove('active'));
                        stepCards[i].classList.add('active');
                    }, 2400);
                } else if (stepCycle) {
                    clearInterval(stepCycle);
                    stepCycle = null;
                }
            });
        }, {
            threshold: 0.3
        });
        howIO.observe(howSection);

        /* ----- Template snippet: re-render variables in accent color on lang change ----- */
        function renderTemplateLines(lang) {
            const dict = (window.TGCRM_I18N && window.TGCRM_I18N.dict[lang]) || (window.TGCRM_I18N && window.TGCRM_I18N.dict
                .ru);
            if (!dict) return;
            document.querySelectorAll('[data-i18n-tpl]').forEach((el) => {
                const key = el.getAttribute('data-i18n-tpl');
                const path = key.split('.');
                let val = dict;
                for (const k of path) val = val?.[k];
                if (!val) return;
                // Wrap {placeholders} in accent spans; if line is the last one (tracking), color brand
                const isTracking = key.endsWith('Line4');
                const colorClass = isTracking ? 'text-brand-500' : 'text-accent-500';
                el.innerHTML = val.replace(/\{(\w+)\}/g, `<span class="${colorClass}">{$1}</span>`);
            });
        }
        window.addEventListener('langchange', (e) => renderTemplateLines(e.detail.lang));
        // initial render after i18n applies
        setTimeout(() => renderTemplateLines((window.TGCRM_I18N && window.TGCRM_I18N.current) || 'uz'), 0);

        /* ----- CTA form submit ----- */
        document.getElementById('cta-form')?.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = document.getElementById('cta-btn');
            const lang = (window.TGCRM_I18N && window.TGCRM_I18N.current) || 'uz';
            const dict = window.TGCRM_I18N?.dict?.[lang];
            btn.textContent = dict?.cta?.formBtnSent || '✓ Отправлено';
            btn.classList.add('bg-emerald-500');
            btn.removeAttribute('data-i18n');
        });

        /* ----- Telegram demo chat (auto-loop, language-aware) ----- */
        const chat = document.getElementById('demo-chat');
        const statusEl = document.getElementById('demo-status');

        function buildScript(d) {
            // d = demo namespace from i18n
            return [{
                    from: 'bot',
                    html: d.msgWelcome,
                    delay: 900
                },
                {
                    from: 'bot',
                    html: `
        <div class="grid grid-cols-2 gap-1.5 mt-2">
          <div class="tg-btn">🇺🇿 O'zbek</div>
          <div class="tg-btn">🏳️ Qaraqalpaq</div>
          <div class="tg-btn">🇹🇯 Тоҷикӣ</div>
          <div class="tg-btn">🇰🇿 Қазақша</div>
          <div class="tg-btn col-span-2">🇷🇺 Русский</div>
        </div>`,
                    bare: true,
                    delay: 700
                },
                {
                    from: 'user',
                    html: d.msgLangPick,
                    delay: 1400
                },
                {
                    from: 'bot',
                    html: d.msgAskName,
                    delay: 1000
                },
                {
                    from: 'user',
                    html: d.msgUserName,
                    delay: 1500
                },
                {
                    from: 'bot',
                    html: d.msgAskPhone,
                    delay: 1100
                },
                {
                    from: 'bot',
                    html: `<div class="tg-btn mt-2 inline-block">${d.msgSharePhone}</div>`,
                    bare: true,
                    delay: 700
                },
                {
                    from: 'user',
                    html: d.msgPhone,
                    delay: 1500
                },
                {
                    from: 'bot',
                    html: d.msgConnected,
                    delay: 1100
                },
                {
                    from: 'bot',
                    html: `
        <div class="flex items-center gap-2 mb-1">
          <span class="w-7 h-7 rounded-lg bg-accent-500/10 grid place-items-center text-accent-500">📦</span>
          <b>${d.msgShippedTitle}</b>
        </div>
        ${d.msgShippedBody}`,
                    accent: true,
                    delay: 1600
                },
            ];
        }

        function makeBubble(item) {
            const wrap = document.createElement('div');
            wrap.className = `bubble bubble-enter ${item.from==='user' ? 'bubble-out' : 'bubble-in'}`;
            if (item.accent) wrap.style.background = 'linear-gradient(180deg,#ffffff,#fff7ed)';
            if (item.bare) {
                wrap.style.background = 'transparent';
                wrap.style.boxShadow = 'none';
                wrap.style.padding = '0';
                wrap.style.maxWidth = '90%';
            }
            const t = new Date();
            const hh = String(t.getHours()).padStart(2, '0');
            const mm = String(t.getMinutes()).padStart(2, '0');
            wrap.innerHTML = item.html + (item.bare ? '' :
                `<span class="time">${hh}:${mm}${item.from==='user' ? ' <span class="check">✓✓</span>' : ''}</span>`);
            return wrap;
        }

        function makeTyping() {
            const w = document.createElement('div');
            w.className = 'bubble bubble-in bubble-enter';
            w.innerHTML = '<div class="typing"><span></span><span></span><span></span></div>';
            return w;
        }

        function sleep(ms) {
            return new Promise(r => setTimeout(r, ms));
        }

        let demoSeq = 0; // increments on each lang change to abort old loop
        async function runDemo() {
            const mySeq = ++demoSeq;
            const lang = (window.TGCRM_I18N && window.TGCRM_I18N.current) || 'uz';
            const dict = window.TGCRM_I18N?.dict?.[lang]?.demo || window.TGCRM_I18N?.dict?.uz?.demo;
            if (!dict) return;
            const SCRIPT = buildScript(dict);
            while (mySeq === demoSeq) {
                chat.innerHTML = '';
                for (const item of SCRIPT) {
                    if (mySeq !== demoSeq) return;
                    if (item.from === 'bot' && !item.bare) {
                        if (statusEl) statusEl.textContent = dict.chatStatusTyping;
                        const typing = makeTyping();
                        chat.appendChild(typing);
                        await sleep(600);
                        if (mySeq !== demoSeq) return;
                        typing.remove();
                    } else {
                        if (statusEl) statusEl.textContent = dict.chatStatusOnline;
                    }
                    const b = makeBubble(item);
                    chat.appendChild(b);
                    while (chat.children.length > 8) chat.firstElementChild.remove();
                    await sleep(item.delay || 800);
                }
                if (statusEl) statusEl.textContent = dict.chatStatusOnline;
                await sleep(2600);
            }
        }

        // Start initial demo after i18n initial apply
        setTimeout(runDemo, 50);
        window.addEventListener('langchange', () => {
            runDemo();
        });
    </script>
</body>

</html>
