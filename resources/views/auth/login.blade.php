<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0f172a;
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .input-field {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.2);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 text-slate-200">
    
    <div class="w-full max-w-5xl flex flex-col md:flex-row glass-panel rounded-3xl overflow-hidden shadow-2xl">
        
        <!-- Left Side: Branding / Visuals -->
        <div class="md:w-1/2 p-12 flex flex-col justify-between relative overflow-hidden bg-gradient-to-br from-brand-900/80 to-slate-900/80 border-b md:border-b-0 md:border-r border-white/10">
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-brand-500 rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">TransportDash</h1>
                </div>
                
                <h2 class="text-4xl font-bold text-white leading-tight mb-4">
                    Intelligent <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-cyan-400">Mobility</span> Analytics
                </h2>
                <p class="text-slate-300 text-lg max-w-sm">
                    Access comprehensive insights and real-time data for modern transportation networks.
                </p>
            </div>
            
            <div class="relative z-10 mt-12 hidden md:block">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://i.pravatar.cc/100?img=1" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://i.pravatar.cc/100?img=2" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-slate-800" src="https://i.pravatar.cc/100?img=3" alt="User">
                    <div class="w-10 h-10 rounded-full border-2 border-slate-800 bg-slate-800 flex items-center justify-center text-xs font-medium text-white">
                        +99
                    </div>
                </div>
                <p class="mt-3 text-sm text-slate-400">Join analytics professionals worldwide</p>
            </div>
            
            <!-- Abstract decorative elements -->
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-12 -right-12 w-64 h-64 bg-cyan-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="md:w-1/2 p-12 lg:px-16 flex flex-col justify-center bg-slate-900/50 relative z-10">
            <div class="w-full max-w-md mx-auto">
                <h3 class="text-3xl font-bold text-white mb-2">Welcome back</h3>
                <p class="text-slate-400 mb-8">Please enter your details to sign in.</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-400 font-medium bg-green-400/10 p-3 rounded-lg border border-green-400/20">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                class="input-field block w-full pl-10 pr-3 py-3 rounded-xl sm:text-sm" placeholder="admin@example.com">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-brand-400 hover:text-brand-300 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="input-field block w-full pl-10 pr-3 py-3 rounded-xl sm:text-sm" placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-brand-500 focus:ring-brand-500 focus:ring-offset-slate-900 transition-colors">
                        <label for="remember_me" class="ml-2 block text-sm text-slate-400">
                            Remember me for 30 days
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-brand-600 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-brand-500 transition-all transform hover:-translate-y-0.5">
                            Sign in to dashboard
                        </button>
                    </div>
                </form>
                
                @if (Route::has('register'))
                <p class="mt-8 text-center text-sm text-slate-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-brand-400 hover:text-brand-300 transition-colors">Sign up now</a>
                </p>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
