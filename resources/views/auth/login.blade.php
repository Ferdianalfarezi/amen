<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AMEN') }} - Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-100 to-gray-200">
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-2xl overflow-hidden sm:rounded-2xl">
            <!-- Logo -->
            <div class="text-center mb-8">
                
                <h1 class="text-4xl font-bold text-gray-900"><i>AMEN</i></h1>
                <p class="text-gray-600 mt-2">3D Model Viewer System</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 font-medium text-sm text-red-600 bg-red-50 p-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username -->
                <div>
                    <label for="username" class="block font-medium text-sm text-gray-700 mb-2">Username</label>
                    <input id="username" 
                           class="block mt-1 w-full px-4 py-3 border border-gray-300 focus:border-gray-900 focus:ring focus:ring-gray-900 focus:ring-opacity-50 rounded-lg shadow-sm transition" 
                           type="text" 
                           name="username" 
                           value="{{ old('username') }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           placeholder="Enter your username">
                    @error('username')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-medium text-sm text-gray-700 mb-2">Password</label>
                    <input id="password" 
                           class="block mt-1 w-full px-4 py-3 border border-gray-300 focus:border-gray-900 focus:ring focus:ring-gray-900 focus:ring-opacity-50 rounded-lg shadow-sm transition" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" 
                               type="checkbox" 
                               class="rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-900" 
                               name="remember">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-4 rounded-lg transition duration-150 ease-in-out transform hover:scale-[1.02] active:scale-[0.98] shadow-lg">
                        Login
                    </button>
                </div>
            </form>

            
        </div>

        <div class="mt-6 text-center text-sm text-gray-600">
            <p>&copy; {{ date('Y') }} AMEN System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>