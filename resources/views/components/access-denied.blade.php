@props([
    'title' => 'Access Denied',
    'message' => 'You don\'t have permission to view this content.',
    'module' => null
])

<div class="flex items-center justify-center min-h-[400px]">
    <div class="text-center max-w-md">
        <div class="inline-block p-6 bg-red-50 rounded-full mb-6 animate-pulse">
            <svg class="w-20 h-20 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $title }}</h3>
        <p class="text-gray-600 mb-6">
            {{ $message }}
            <br>
            @if($module)
            <span class="text-sm text-gray-500 mt-2 inline-block">Module: <span class="font-semibold">{{ $module }}</span></span>
            @endif
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('drawings.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-lg transition shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Drawings
            </a>
            @if(auth()->user()->hasPermission('users', 'view'))
            <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                User Management
            </a>
            @endif
        </div>
        
        <!-- Contact Admin Info -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-xs text-gray-500 mb-2">Need access to this module?</p>
            <p class="text-sm text-gray-700">
                Contact your administrator to request permissions
            </p>
        </div>
    </div>
</div>