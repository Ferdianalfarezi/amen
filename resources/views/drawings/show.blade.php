    @extends('layouts.app')

    @section('page-title', 'Drawing Management')

    @section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Drawing -->
        <div class="bg-white rounded-xl shadow-md mb-6 p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $drawing->nama }}</h2>
                    <p class="text-gray-600 mt-2">{{ $drawing->deskripsi }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <p><strong class="text-gray-700">Customer:</strong> {{ $drawing->customer }}</p>
                            <p><strong class="text-gray-700">Project:</strong> {{ $drawing->project }}</p>
                        </div>
                        <div>
                            <p><strong class="text-gray-700">Tahun:</strong> {{ $drawing->tahun_project }}</p>
                            <p><strong class="text-gray-700">Departemen:</strong> {{ $drawing->departemen }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0 flex gap-3">
                    <a href="{{ route('drawings.edit', $drawing) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('drawings.destroy', $drawing) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center transition" onclick="return confirm('Yakin hapus drawing ini?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabs -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <!-- Tab Header - Horizontal Scrollable -->
    <div class="border-b border-gray-200 overflow-x-auto">
        <ul class="flex flex-nowrap text-sm font-medium text-center text-gray-500 whitespace-nowrap" 
            id="fileTabs" role="tablist">
            
            <!-- Tab 1: 2D Files (Default Active) -->
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center px-4 py-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 tab-link active" 
                        data-tab="files2d" 
                        data-url="{{ route('drawings.files2d', $drawing) }}"
                        id="files2d-tab" 
                        type="button" 
                        role="tab"
                        aria-selected="true">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Drawing
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $drawing->files2d->count() }}</span>
                </button>
            </li>

            <!-- Tab 2: 3D Files (TANPA class active) -->
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center px-4 py-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 tab-link" 
                        data-tab="files3d" 
                        data-url="{{ route('drawings.files3d', $drawing) }}"
                        id="files3d-tab" 
                        type="button" 
                        role="tab"
                        aria-selected="false">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    3D Files
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $drawing->files3d->count() }}</span>
                </button>
            </li>

            <!-- Tab 3: Sample Parts -->
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center px-4 py-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 tab-link" 
                        data-tab="sample-parts" 
                        data-url="{{ route('drawings.sampleParts', $drawing) }}"
                        id="sample-parts-tab" 
                        type="button" 
                        role="tab"
                        aria-selected="false">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z" />
                    </svg>
                    Sample Parts
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $drawing->sampleParts->count() }}</span>
                </button>
            </li>

            <!-- Tab 4: Quality -->
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center px-4 py-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 tab-link" 
                        data-tab="quality" 
                        data-url="{{ route('drawings.qualities', $drawing) }}"
                        id="quality-tab" 
                        type="button" 
                        role="tab"
                        aria-selected="false">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Quality
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $drawing->qualities->count() }}</span>
                </button>
            </li>

            <!-- Tab 5: Setup Procedure -->
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center px-4 py-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 tab-link" 
                        data-tab="setup" 
                        data-url="{{ route('drawings.setupProcedures', $drawing) }}"
                        id="setup-tab" 
                        type="button" 
                        role="tab"
                        aria-selected="false">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                    Setup Procedure
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $drawing->setupProcedures->count() }}</span>
                </button>
            </li>

            <!-- Tab 6: Quotes -->
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center px-4 py-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 tab-link" 
                        data-tab="quotes" 
                        data-url="{{ route('drawings.quotes', $drawing) }}"
                        id="quotes-tab" 
                        type="button" 
                        role="tab"
                        aria-selected="false">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Quotes
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $drawing->quotes->count() }}</span>
                </button>
            </li>

            <!-- Tab 7: Work Instructions -->
            <li class="mr-2" role="presentation">
                <button class="inline-flex items-center px-4 py-3 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 tab-link" 
                        data-tab="work-instructions" 
                        data-url="{{ route('drawings.workInstructions', $drawing) }}"
                        id="work-instructions-tab" 
                        type="button" 
                        role="tab"
                        aria-selected="false">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Work Instructions
                    <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $drawing->workInstructions->count() }}</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
        <div class="tab-content" id="fileTabsContent">
            
            <!-- 2D Files (Default Visible) -->
            <div class="tab-pane block" id="files2d" role="tabpanel" aria-labelledby="files2d-tab">
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Loading 2D Files...</p>
                </div>
            </div>

            <!-- 3D Files -->
            <div class="tab-pane hidden" id="files3d" role="tabpanel" aria-labelledby="files3d-tab">
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Loading 3D Files...</p>
                </div>
            </div>

            <!-- Sample Parts -->
            <div class="tab-pane hidden" id="sample-parts" role="tabpanel" aria-labelledby="sample-parts-tab">
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Loading Sample Parts...</p>
                </div>
            </div>

            <!-- Quality -->
            <div class="tab-pane hidden" id="quality" role="tabpanel" aria-labelledby="quality-tab">
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Loading Quality Files...</p>
                </div>
            </div>

            <!-- Setup Procedure -->
            <div class="tab-pane hidden" id="setup" role="tabpanel" aria-labelledby="setup-tab">
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Loading Setup Procedures...</p>
                </div>
            </div>

            <!-- Quotes -->
            <div class="tab-pane hidden" id="quotes" role="tabpanel" aria-labelledby="quotes-tab">
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Loading Quotes...</p>
                </div>
            </div>

            <!-- Work Instructions -->
            <div class="tab-pane hidden" id="work-instructions" role="tabpanel" aria-labelledby="work-instructions-tab">
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">Loading Work Instructions...</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal untuk Preview Photo -->
    <div id="modalPhoto" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4">
        <div class="relative max-w-7xl max-h-full">
            <button onclick="closeModal('modalPhoto')" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <img id="modalPhotoImage" src="" alt="Preview" class="max-w-full max-h-screen rounded-lg">
        </div>
    </div>

    <!-- Modal untuk Preview Video -->
    <div id="modalVideo" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4">
        <div class="relative max-w-7xl max-h-full">
            <button onclick="closeModal('modalVideo')" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <video id="modalVideoPlayer" controls class="max-w-full max-h-screen rounded-lg">
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <!-- Modal untuk Preview 3D -->
    <div id="modal3D" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4" onclick="if(event.target === this) closeModal('modal3D')">
        <div class="relative bg-white rounded-lg p-4 shadow-2xl" style="width: 90vw; max-width: 1000px; height: 90vh; max-height: 800px;">
            <button onclick="closeModal('modal3D'); event.stopPropagation();" class="absolute -top-3 -right-3 text-white bg-red-500 hover:bg-red-600 z-[60] rounded-full p-2 shadow-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div id="model3DContainer" style="width: 100%; height: 100%;">
                <!-- Konten akan diisi oleh JS: model-viewer atau iframe ShareCAD -->
            </div>
        </div>
    </div>

    <!-- Modal untuk Preview Dokumen -->
    <div id="modalDocument" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-2xl" style="width: 90vw; max-width: 1200px; height: 90vh; max-height: 800px;">
            <button onclick="closeModal('modalDocument')" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 z-10 bg-white rounded-full p-2 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div id="documentContainer" class="w-full h-full rounded-lg overflow-hidden">
                <!-- Konten dokumen akan diisi oleh JS -->
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .nav-tabs .tab-link {
            @apply px-4 py-3 text-gray-500 font-medium flex items-center gap-2 transition-colors;
        }

        .nav-tabs .tab-link.active {
            @apply text-blue-600 border-b-2 border-blue-600;
        }

        .tab-pane {
            @apply hidden;
        }

        .tab-pane.active {
            @apply block;
        }

        /* Prevent modal content from shrinking */
        #modal3D {
            pointer-events: auto;
        }
        
        /* Ensure model-viewer captures all interactions */
        model-viewer {
            display: block;
            position: relative;
            outline: none;
            -webkit-tap-highlight-color: transparent;
            width: 100%;
            height: 100%;
        }
        
        /* Prevent text selection during 3D interaction */
        model-viewer * {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://unpkg.com/@google/model-viewer@3.4.0/dist/model-viewer.min.js" type="module"></script>
    <script>
        // ===================================================================
        // TAB SYSTEM
        // ===================================================================
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-link');
            
            // Load first tab on page load
            const firstTab = tabs[0];
            if (firstTab) {
                loadTabContent(firstTab.dataset.tab, firstTab.dataset.url);
            }

            // Tab click event
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active', 'text-blue-600', 'border-blue-600');
                        t.classList.add('text-gray-500', 'border-transparent');
                    });
                    
                    // Add active class to clicked tab
                    this.classList.add('active', 'text-blue-600', 'border-blue-600');
                    this.classList.remove('text-gray-500', 'border-transparent');
                    
                    // Load tab content
                    loadTabContent(this.dataset.tab, this.dataset.url);
                });
            });
        });

        // ===================================================================
        // AJAX TAB CONTENT LOADER
        // ===================================================================
        function loadTabContent(tabId, url) {
            const contentArea = document.getElementById(tabId);
            
            if (!contentArea) return;
            
            // Show loading state
            contentArea.classList.remove('hidden');
            contentArea.classList.add('active');
            
            // Hide other tabs
            document.querySelectorAll('.tab-pane').forEach(pane => {
                if (pane.id !== tabId) {
                    pane.classList.remove('active');
                    pane.classList.add('hidden');
                }
            });
            
            // Fetch content
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                contentArea.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading tab:', error);
                contentArea.innerHTML = `
                    <div class="text-center py-12">
                        <p class="text-red-500">Error loading content. Please try again.</p>
                    </div>
                `;
            });
        }

        // ===================================================================
        // MODAL FUNCTIONS - DITAMBAHKAN DOKUMEN
        // ===================================================================
        function openPhotoModal(imageUrl) {
            const modal = document.getElementById('modalPhoto');
            const img = document.getElementById('modalPhotoImage');
            if (modal && img) {
                img.src = imageUrl;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function openVideoModal(videoUrl) {
            const modal = document.getElementById('modalVideo');
            const video = document.getElementById('modalVideoPlayer');
            if (modal && video) {
                video.querySelector('source').src = videoUrl;
                video.load();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function open3DModal(modelUrl, fileType) {
            const modal = document.getElementById('modal3D');
            const container = document.getElementById('model3DContainer');
            
            if (modal && container) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                if (fileType.toLowerCase() === 'igs' || fileType.toLowerCase() === 'iges') {
                    // Pakai ShareCAD iframe untuk .igs
                    container.innerHTML = `
                        <iframe 
                            src="https://sharecad.org/cadframe/load?url=${encodeURIComponent(modelUrl)}" 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            scrolling="no"
                            class="rounded-lg">
                        </iframe>
                    `;
                } else {
                    // Pakai model-viewer untuk .glb, .stl, dll.
                    container.innerHTML = `
                        <model-viewer 
                            id="modal3DViewer"
                            src="${modelUrl}" 
                            alt="3D Model" 
                            auto-rotate 
                            camera-controls 
                            style="width: 100%; height: 100%;"
                            class="rounded-lg">
                        </model-viewer>
                    `;
                }
            }
        }

        function openDocumentModal(documentUrl, fileType, fileName) {
        const modal = document.getElementById('modalDocument');
        const container = document.getElementById('documentContainer');

        if (modal && container) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Ambil relative path dari storage (contoh: files_3d/part1.xlsx)
            const relativePath = documentUrl.replace(/^.*storage\//, '');
            const previewUrl = `/preview/${relativePath}`;

            container.innerHTML = `
                <div class="w-full h-full flex flex-col">
                    <div class="flex-1">
                        <iframe 
                            src="${previewUrl}" 
                            width="100%" 
                            height="100%" 
                            frameborder="0"
                            class="rounded-lg">
                        </iframe>
                    </div>
                    <div class="bg-gray-100 px-4 py-3 border-t border-gray-200 flex justify-between items-center">
                        <span class="text-sm text-gray-600">Preview dokumen</span>
                        <a href="${documentUrl}" 
                        download="${fileName}"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center text-sm transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            `;
        }
    }

        function openUploadModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        // Global Close Modal Function
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                
                // Reset video if closing video modal
                if (modalId === 'modalVideo') {
                    const videoPlayer = document.getElementById('modalVideoPlayer');
                    if (videoPlayer) {
                        videoPlayer.pause();
                        videoPlayer.currentTime = 0;
                        videoPlayer.querySelector('source').src = '';
                    }
                }
                
                // Reset image if closing photo modal
                if (modalId === 'modalPhoto') {
                    const img = document.getElementById('modalPhotoImage');
                    if (img) {
                        img.src = '';
                    }
                }
                
                // Reset 3D model if closing 3D modal
                if (modalId === 'modal3D') {
                    const container = document.getElementById('model3DContainer');
                    if (container) {
                        container.innerHTML = '';
                    }
                }
                
                // Reset document if closing document modal
                if (modalId === 'modalDocument') {
                    const container = document.getElementById('documentContainer');
                    if (container) {
                        container.innerHTML = '';
                    }
                }
            }
        }

        // ===================================================================
        // FILE OPERATIONS
        // ===================================================================
        function downloadFile(fileUrl, fileName) {
            const link = document.createElement('a');
            link.href = fileUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Global Delete File Function dengan notification konsisten
        function deleteFile(url, fileName) {
            Swal.fire({
                title: 'Hapus File?',
                html: `Yakin ingin menghapus <strong>"${fileName}"</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus file',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close loading
                            Swal.close();
                            
                            // Show consistent success notification
                            showSuccessNotification(data.message);
                            
                            // Reload the active tab
                            const activeTab = document.querySelector('.tab-link.active');
                            if (activeTab) {
                                loadTabContent(activeTab.dataset.tab, activeTab.dataset.url);
                            }
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Gagal menghapus file',
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus file',
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    });
                }
            });
        }

        // Reusable success notification function
        function showSuccessNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-black text-white px-6 py-3 rounded-lg shadow-lg z-[100] transform transition-transform duration-300 translate-x-0';
            notification.style.cssText = `
                animation: slideInRight 0.3s ease-out;
            `;
            
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Add CSS for animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // ===================================================================
        // UPLOAD HANDLER (if using AJAX upload)
        // ===================================================================
        function handleFileUpload(formId, uploadUrl) {
            const form = document.getElementById(formId);
            if (!form) return;

            const formData = new FormData(form);

            fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Close modal
                    const modal = form.closest('.modal');
                    if (modal) {
                        closeModal(modal.id);
                    }
                    // Reset form
                    form.reset();
                    // Reload the active tab
                    const activeTab = document.querySelector('.tab-link.active');
                    if (activeTab) {
                        loadTabContent(activeTab.dataset.tab, activeTab.dataset.url);
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat upload file');
            });
        }

        // ===================================================================
        // KEYBOARD SHORTCUTS
        // ===================================================================
        // Close modals on Escape key
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal('modal3D');
                closeModal('modalPhoto');
                closeModal('modalVideo');
                closeModal('modalDocument');
                // Close any upload modals
                document.querySelectorAll('[id^="uploadModal"]').forEach(modal => {
                    if (modal.classList.contains('flex')) {
                        closeModal(modal.id);
                    }
                });
            }
        });

        // ===================================================================
        // EVENT DELEGATION FOR DYNAMICALLY LOADED CONTENT - DIPERBARUI
        // ===================================================================
        document.addEventListener('click', function(e) {
            // Preview button handler
            if (e.target.closest('.preview-btn')) {
                const btn = e.target.closest('.preview-btn');
                const fileUrl = btn.dataset.fileUrl;
                const fileType = btn.dataset.fileType;
                const fileName = btn.dataset.fileName;
                
                if (!fileUrl) return;
                
                // Determine file type and open appropriate modal
                const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                const videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
                const model3DExtensions = ['stl', 'obj', 'glb', 'gltf', 'fbx', '3ds'];
                const cadExtensions = ['igs', 'iges', 'step', 'stp'];
                const documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
                
                if (imageExtensions.includes(fileType.toLowerCase())) {
                    openPhotoModal(fileUrl);
                } else if (videoExtensions.includes(fileType.toLowerCase())) {
                    openVideoModal(fileUrl);
                } else if (model3DExtensions.includes(fileType.toLowerCase()) || cadExtensions.includes(fileType.toLowerCase())) {
                    open3DModal(fileUrl, fileType);
                } else if (fileType.toLowerCase() === 'pdf') {
                    // Khusus PDF pakai modal tersendiri atau embed langsung
                    openDocumentModal(fileUrl, fileType, fileName);
                } else if (documentExtensions.includes(fileType.toLowerCase())) {
                    openDocumentModal(fileUrl, fileType, fileName);
                } else {
                    // Untuk file type lain, buka modal document dengan fallback
                    openDocumentModal(fileUrl, fileType, fileName);
                }
            }
            
            // Upload button handler
            if (e.target.closest('.upload-btn')) {
                const btn = e.target.closest('.upload-btn');
                const modalId = btn.dataset.modalId;
                if (modalId) {
                    openUploadModal(modalId);
                }
            }
        });

        // Click outside modal to close
        document.addEventListener('click', function(e) {
            if (e.target.id === 'modalPhoto') {
                closeModal('modalPhoto');
            }
            if (e.target.id === 'modalVideo') {
                closeModal('modalVideo');
            }
            if (e.target.id === 'modalDocument') {
                closeModal('modalDocument');
            }
            // modal3D menggunakan onclick inline di backdrop
        }, true);

        // Error handling untuk model-viewer
        document.addEventListener('DOMContentLoaded', function() {
            const modelViewer = document.querySelector('#modal3DViewer');
            if (modelViewer) {
                modelViewer.addEventListener('error', () => {
                    alert('Gagal memuat model 3D. Pastikan format file didukung.');
                });
            }
        });
    </script>
    @endpush
    @endsection