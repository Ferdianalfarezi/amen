@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h3>
            <p class="text-gray-600 mt-1">{{ auth()->user()->departemen }} Department</p>
        </div>
        @if(auth()->user()->hasPermission('drawings', 'create'))
        <a href="{{ route('drawings.create') }}" class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg flex items-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New 3D Model
        </a>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Total 3D Models</p>
                <p class="text-2xl font-bold text-gray-900">{{ $drawings->total() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">My Models</p>
                <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->drawings->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-600 text-sm">Department</p>
                <p class="text-xl font-bold text-gray-900">{{ auth()->user()->departemen }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Drawings Grid -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-bold text-gray-900">Recent 3D Models</h2>
    </div>

    @if($drawings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
            @foreach($drawings as $drawing)
                <div class="group">
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300">

                        <!-- Thumbnail (SAMA PERSIS SEPERTI DRAWING MANAGEMENT) -->
                        <div class="relative h-48 bg-gray-100 overflow-hidden cursor-pointer"
                             onclick="window.location='{{ route('drawings.show', $drawing->id) }}'">
                            
                            @if($drawing->files2d->count() > 0)
                                <img src="{{ asset('storage/' . $drawing->files2d->first()->file_path) }}" 
                                     alt="{{ $drawing->nama }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif

                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-1 truncate">
                                {{ $drawing->nama }}
                            </h3>

                            <!-- User info -->
                            <div class="text-sm text-gray-600 mb-3">
                                

                                @if($drawing->tahun_project || $drawing->project)
                                <div class="text-xs text-gray-500 space-y-1 mt-2">
                                    @if($drawing->tahun_project)
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-800">Tahun:</span>
                                            <span class="ml-1">{{ $drawing->tahun_project }}</span>
                                        </div>
                                    @endif

                                    @if($drawing->project)
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-800">Project:</span>
                                            <span class="ml-1">{{ $drawing->project }}</span>
                                        </div>
                                    @endif

                                    @if($drawing->customer)
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-800">Customer:</span>
                                            <span class="ml-1">{{ $drawing->customer}}</span>
                                        </div>
                                    @endif

                                    @if($drawing->departemen)
                                        <div class="flex items-center">
                                            <span class="font-medium text-gray-800">Departement:</span>
                                            <span class="ml-1">{{ $drawing->departemen }}</span>
                                        </div>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                <span class="flex items-center" title="Photos">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $drawing->files2d->count() }}
                                </span>

                                <span class="flex items-center" title="3D Files">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    {{ $drawing->files3d->count() }}
                                </span>

                                <span class="flex items-center" title="Total Files">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $drawing->total_files_count }}
                                </span>
                            </div>

                            <div class="flex gap-2">
                                @if(auth()->user()->hasPermission('drawings', 'view'))
                                <a href="{{ route('drawings.show', $drawing->id) }}" 
                                   class="flex-1 text-center bg-gray-600 hover:bg-gray-200 text-white text-sm py-2 rounded transition">
                                    View
                                </a>
                                @endif

                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $drawings->links() }}
        </div>

    @else
        <div class="p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No 3D Models Yet</h3>
            <p class="text-gray-500 mb-4">Start by creating your first 3D model</p>

            @if(auth()->user()->hasPermission('drawings', 'create'))
            <a href="{{ route('drawings.create') }}" 
               class="inline-flex items-center bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Model
            </a>
            @endif
        </div>
    @endif
</div>
@endsection
