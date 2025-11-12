@extends('layouts.app')
@section('page-title', 'Drawing Management')

@section('content')
<div class="mb-6">

    <div class="flex items-center justify-between gap-3">

    <!-- Title -->
    <h3 class="text-2xl font-bold text-gray-900 whitespace-nowrap">
        All Drawing
    </h3>

    <!-- RIGHT GROUP -->
    <div class="flex items-center gap-3">

        <form action="{{ route('drawings.index') }}" method="GET" class="flex items-center gap-2">

        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search drawings..."
            class="w-64 pl-4 pr-4 py-2.5 border border-gray-300 rounded-lg
                    focus:ring-2 focus:ring-gray-900 focus:border-transparent">

        @if(request()->has('search') && request('search'))
            <!-- Clear -->
            <a href="{{ route('drawings.index') }}"
            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2.5 rounded-lg transition flex items-center justify-center">
                <!-- X icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        @else
            <!-- Search -->
            <button type="submit"
                class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-3 rounded-lg transition flex items-center justify-center">
                <!-- Search icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 6.65a7.5 7.5 0 010 10.6z" />
                </svg>
            </button>
        @endif

    </form>
        <!-- New Drawing Button -->
        @if(auth()->user()->hasPermission('drawings', 'create'))
            <a href="{{ route('drawings.create') }}" 
               class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg 
                      flex items-center transition whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>
                New Drawing
            </a>
        @endif

    </div>

</div>


    <!-- Search Results Info -->
    @if(request()->has('search') && request('search'))
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <span class="text-blue-700">
                    Showing results for: <strong>"{{ request('search') }}"</strong>
                    @if($drawings->total() > 0)
                        - Found {{ $drawings->total() }} {{ Str::plural('drawing', $drawings->total()) }}
                    @endif
                </span>
            </div>
        </div>
    @endif
</div>

<div class="bg-white rounded-lg shadow">
    @if($drawings->count() > 0)

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
            @foreach($drawings as $drawing)
                <div class="group">
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300">
                        
                        <!-- Thumbnail -->
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
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0..
                                        </path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-1 truncate">
                                {{ $drawing->nama }}
                            </h3>

                            <div class="text-sm text-gray-600 mb-3 space-y-1">
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
                                        <span class="ml-1">{{ $drawing->customer }}</span>
                                    </div>
                                @endif

                                @if($drawing->departemen)
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-800">Departemen:</span>
                                        <span class="ml-1">{{ $drawing->departemen }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                <span class="flex items-center" title="2D Photos">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 00-2-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2z" />
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
                                    class="flex-1 text-center bg-gray-600 hover:bg-gray-700 text-white text-sm py-2 rounded transition">
                                        View
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('drawings', 'update'))
                                    <a href="{{ route('drawings.edit', $drawing->id) }}"
                                    class="flex-1 text-center bg-yellow-600 hover:bg-blue-700 text-white text-sm py-2 rounded transition">
                                        Edit
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('drawings', 'delete'))
                                    <form action="{{ route('drawings.destroy', $drawing->id) }}" 
                                        method="POST" class="flex-1"
                                        onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded transition">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $drawings->appends(request()->query())->links() }}
        </div>

    @else
        <div class="p-12 text-center">
            @if(request()->has('search') && request('search'))
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Results Found</h3>
                <p class="text-gray-500 mb-4">No drawings found for "<strong>{{ request('search') }}</strong>"</p>
                <a href="{{ route('drawings.index') }}" 
                   class="inline-flex items-center bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg transition">
                    View All Drawings
                </a>
            @else
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2..
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Drawings Yet</h3>
                <p class="text-gray-500 mb-4">Start by creating your first drawing</p>

                @if(auth()->user()->hasPermission('drawings', 'create'))
                <a href="{{ route('drawings.create') }}"
                   class="inline-flex items-center bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Create New Drawing
                </a>
                @endif
            @endif
        </div>
    @endif
</div>
@endsection
