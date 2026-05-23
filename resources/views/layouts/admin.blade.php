<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyCode')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50:'#f0f9ff', 100:'#e0f2fe', 500:'#0ea5e9', 600:'#0284c7', 700:'#0369a1' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-56 bg-white border-r border-gray-200 flex flex-col flex-shrink-0">
        <div class="px-4 py-4 border-b border-gray-100">
            <a href="/" class="flex items-center gap-2">
                <div class="w-7 h-7 bg-brand-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69.01-.03.01-.14-.07-.2-.08-.06-.2-.04-.28-.02-.12.02-1.96 1.25-5.54 3.69-.52.36-1 .53-1.42.52-.47-.01-1.37-.26-2.03-.48-.82-.27-1.47-.42-1.42-.88.03-.25.38-.51 1.07-.78 4.19-1.82 6.98-3.02 8.38-3.61 3.99-1.66 4.82-1.95 5.36-1.96.12 0 .38.03.55.18.14.13.18.3.2.43-.02.07-.02.13-.03.2z"/></svg>
                </div>
                <span class="font-semibold text-gray-900 text-sm">MyCode</span>
            </a>
        </div>

        <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider px-3 py-2">Main</p>
            <a href="{{ route('dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 font-medium' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('bots.index') }}" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('bots.*') ? 'bg-brand-50 text-brand-700 font-medium' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Bots
            </a>
            <a href="{{ route('clients.index') }}" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('clients.*') ? 'bg-brand-50 text-brand-700 font-medium' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Clients
            </a>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider px-3 py-2 mt-4">Settings</p>
            <a href="{{ route('integrations.index') }}" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('integrations.*') ? 'bg-brand-50 text-brand-700 font-medium' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                Integrations
            </a>
            <a href="{{ route('company.settings') }}" class="nav-link flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition-colors {{ request()->routeIs('company.*') ? 'bg-brand-50 text-brand-700 font-medium' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Company
            </a>
        </nav>

        <div class="px-3 py-3 border-t border-gray-100">
            <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-50 cursor-pointer group">
                <div class="w-7 h-7 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 text-xs font-semibold">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->company->name }}</p>
                </div>
                <button onclick="toggleProfileMenu()" class="text-gray-400 group-hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
            </div>
            <div id="profileMenu" class="hidden mt-2 space-y-1 border-t border-gray-100 pt-2">
                <a href="#" class="block text-xs text-gray-600 px-2 py-1.5 rounded hover:bg-gray-50">Settings</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block w-full">
                    @csrf
                    <button type="submit" class="block w-full text-left text-xs text-red-600 px-2 py-1.5 rounded hover:bg-red-50">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <main class="flex-1 overflow-y-auto">
        @yield('content')
    </main>
</div>

<script>
    function toggleProfileMenu() {
        document.getElementById('profileMenu').classList.toggle('hidden');
    }
</script>
</body>
</html>
