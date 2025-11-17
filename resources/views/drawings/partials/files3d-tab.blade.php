<!-- resources/views/drawings/partials/files3d-tab.blade.php -->

<!-- Access Denied View -->
@if(!auth()->user()->hasPermission('files_3d', 'view'))
    <div class="flex items-center justify-center min-h-[400px]">
        <div class="text-center max-w-md mx-auto p-9">
            <!-- Icon Lock -->
            <div class="inline-block p-6 bg-red-100 rounded-full mb-6">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            
            <!-- Title -->
            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                Akses Ditolak
            </h3>
            
            <!-- Message -->
            <p class="text-gray-600 mb-2">
                Anda tidak memiliki akses untuk melihat <span class="font-semibold text-gray-900">Files 3D</span>
            </p>
            <p class="text-sm text-gray-500">
                Silakan hubungi IT Department untuk mendapatkan akses ke halaman ini.
            </p>
            
        </div>
    </div>
@else
<div class="space-y-4">
    <!-- Upload Button -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">3D Files ({{ $files->count() }})</h3>
        @if (auth()->user()->role !== 'user')
            <button 
                class="upload-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition shadow-sm"
                data-modal-id="uploadModal3D">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Upload 3D File
            </button>
        @endif
    </div>

    @if($files->count() > 0)
        <!-- File Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($files as $file)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                    <!-- File Preview/Thumbnail - CLICKABLE -->
                    <div class="relative h-48 bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden cursor-pointer preview-btn"
                         data-file-url="{{ asset('storage/' . $file->file_path) }}"
                         data-file-type="{{ strtolower($file->tipe_file) }}"
                         data-file-name="{{ $file->nama }}">
                        @php
                            $fileExtension = strtolower($file->tipe_file);
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
                            $model3DExtensions = ['stl', 'obj', 'glb', 'gltf', 'fbx', '3ds'];
                            $cadExtensions = ['igs', 'iges', 'step', 'stp'];
                        @endphp
                        
                        @if(in_array($fileExtension, $imageExtensions))
                            <!-- Image Preview -->
                            <img src="{{ asset('storage/' . $file->file_path) }}" 
                                 alt="{{ $file->nama }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 pointer-events-none">
                        @elseif(in_array($fileExtension, $model3DExtensions))
                            <!-- 3D Model Preview -->
                            <model-viewer 
                                src="{{ asset('storage/' . $file->file_path) }}" 
                                alt="{{ $file->nama }}"
                                auto-rotate
                                camera-controls
                                interaction-prompt="none"
                                loading="lazy"
                                style="width: 100%; height: 100%; pointer-events: none;"
                                class="bg-transparent">
                            </model-viewer>
                        @elseif(in_array($fileExtension, $cadExtensions))
                            <!-- CAD File Preview (IGS, STEP, etc.) -->
                            <div class="flex flex-col items-center justify-center h-full pointer-events-none p-4 text-center">
                                <svg class="w-16 h-16 text-blue-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-xs font-medium text-blue-700 bg-blue-100 px-2 py-1 rounded">
                                    {{ strtoupper($fileExtension) }} CAD
                                </span>
                                <p class="text-xs text-gray-500 mt-2">Click to view in 3D Viewer</p>
                            </div>
                        @else
                            <!-- Fallback Icon -->
                            <div class="flex items-center justify-center h-full pointer-events-none">
                                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Overlay on hover -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100 pointer-events-none">
                            <div class="text-center">
                                <span class="text-white font-semibold text-sm bg-black bg-opacity-50 px-4 py-2 rounded-full">
                                    Click to Preview
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- File Info -->
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-900 truncate mb-2 text-base" title="{{ $file->nama }}">
                            {{ $file->nama }}
                        </h4>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded font-medium">
                                {{ strtoupper($file->tipe_file) }}
                            </span>
                            <span>{{ number_format($file->ukuran / 1024, 2) }} KB</span>
                        </div>
                        @if($file->deskripsi)
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $file->deskripsi }}</p>
                        @endif

                        <!-- Action Buttons - Download & Delete Only -->
                        <div class="grid grid-cols-3 gap-2">
                            <!-- Download Button -->
                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                               download="{{ $file->nama }}"
                               class="download-btn col-span-2 bg-black hover:bg-green-600 text-white px-3 py-2.5 rounded-lg flex items-center justify-center transition-all shadow-sm hover:shadow-md text-sm font-medium"
                               title="Download {{ $file->nama }}">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>

                            <!-- Delete Button -->
                            <button 
                                onclick="deleteFile('{{ route('drawings.deleteFile3D', $file->id) }}', '{{ $file->nama }}')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2.5 rounded-lg flex items-center justify-center transition-all shadow-sm hover:shadow-md"
                                title="Delete {{ $file->nama }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300">
            <div class="inline-block p-6 bg-white rounded-full shadow-md mb-4">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada 3D Files</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Upload file 3D pertama Anda untuk memulai. Mendukung format STL, OBJ, GLB, GLTF, FBX, 3DS, IGS, STEP.</p>
            @if (auth()->user()->role !== 'user')
                <button 
                    class="upload-btn bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg inline-flex items-center transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                    data-modal-id="uploadModal3D">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload 3D File
                </button>
            @endif
        </div>
    @endif
</div>

<!-- Upload Modal -->
<div id="uploadModal3D" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4" onclick="if(event.target === this) closeModal('uploadModal3D')">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Upload 3D File</h3>
            <button onclick="closeModal('uploadModal3D')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="upload3DForm" action="{{ route('drawings.uploadFile3D', $drawing) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <!-- File Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">File 3D *</label>
                    <div class="relative">
                        <input 
                            type="file" 
                            name="file" 
                            id="file3DInput"
                            required
                            accept=".stl,.obj,.glb,.gltf,.fbx,.3ds,.igs,.iges,.step,.stp"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="font-medium">Supported formats:</span> GLB, IGS, IGES, STEP, STL
                        <br>
                        <span class="font-medium">Max size:</span> 100MB
                    </p>
                </div>

                <!-- Nama File -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama File</label>
                    <input 
                        type="text" 
                        name="nama" 
                        placeholder="Nama file (opsional)"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
                        rows="3"
                        placeholder="Deskripsi file (opsional)"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"></textarea>
                </div>

                <!-- Progress Bar -->
                <div id="upload3DProgress" class="hidden">
                    <div class="mb-2 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Uploading...</span>
                        <span id="upload3DPercent" class="text-sm font-bold text-blue-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden shadow-inner">
                        <div id="upload3DBar" 
                             class="h-3 rounded-full" 
                             style="width: 0%; background: linear-gradient(90deg, #3B82F6 0%, #2563EB 100%) !important; transition: width 0.3s ease-out;"></div>
                    </div>
                    <p id="upload3DStatus" class="text-xs text-gray-500 mt-2">Preparing upload...</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button" 
                        onclick="closeModal('uploadModal3D')"
                        id="cancel3DBtn"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-lg transition font-medium">
                        Batal
                    </button>
                    <button 
                        type="submit"
                        id="submit3DBtn"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg transition font-medium shadow-md hover:shadow-lg">
                        Upload
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- 3D Viewer Modal untuk preview file -->
<div id="viewerModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-[100] items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-6xl h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900" id="viewerTitle">3D Viewer</h3>
            <button onclick="closeViewer()" class="text-gray-400 hover:text-gray-600 transition p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Viewer Container -->
        <div class="flex-1 relative">
            <div id="viewerContainer" class="w-full h-full">
                <!-- OCC WASM Viewer akan dimuat di sini -->
                <div id="occViewer" class="w-full h-full bg-gray-100 flex items-center justify-center">
                    <div class="text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Loading 3D Viewer...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styling untuk model-viewer thumbnail */
model-viewer {
    --poster-color: transparent;
}

/* Animasi untuk card hover */
.group:hover {
    transform: translateY(-4px);
}

/* Loading state untuk model-viewer */
model-viewer::part(default-progress-bar) {
    height: 3px;
    background-color: #c6c6c6;
}

/* Thumbnail clickable styling */
.preview-btn {
    transition: all 0.3s ease;
}

.preview-btn:hover {
    cursor: pointer;
}

.preview-btn:active {
    transform: scale(0.98);
}

/* OCC Viewer styling */
#occViewer canvas {
    width: 100% !important;
    height: 100% !important;
    display: block;
}

/* Progress bar animation */
@keyframes pulse-progress {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

#upload3DBar {
    animation: pulse-progress 2s ease-in-out infinite;
}
</style>
@endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- Load OCC WASM dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/occ-wasm@latest/dist/occ-wasm.js"></script>

<script>
// Variabel global untuk OCC viewer
let occViewer = null;

// Handle preview button clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('.preview-btn')) {
        const previewBtn = e.target.closest('.preview-btn');
        const fileUrl = previewBtn.dataset.fileUrl;
        const fileType = previewBtn.dataset.fileType;
        const fileName = previewBtn.dataset.fileName;
        
        openViewer(fileUrl, fileType, fileName);
    }
});

// Fungsi untuk membuka viewer
function openViewer(fileUrl, fileType, fileName) {
    const viewerModal = document.getElementById('viewerModal');
    const viewerTitle = document.getElementById('viewerTitle');
    const occViewer = document.getElementById('occViewer');
    
    // Set judul
    viewerTitle.textContent = `3D Viewer - ${fileName}`;
    
    // Tampilkan modal
    viewerModal.classList.remove('hidden');
    viewerModal.classList.add('flex');
    
    // Handle berdasarkan tipe file
    const cadExtensions = ['igs', 'iges', 'step', 'stp'];
    
    if (cadExtensions.includes(fileType.toLowerCase())) {
        // Gunakan OCC WASM untuk file CAD
        loadOCCViewer(fileUrl, fileName);
    } else {
        // Gunakan model-viewer untuk format 3D biasa
        loadModelViewer(fileUrl, fileName);
    }
}

// Fungsi untuk menutup viewer
function closeViewer() {
    const viewerModal = document.getElementById('viewerModal');
    viewerModal.classList.add('hidden');
    viewerModal.classList.remove('flex');
    
    // Cleanup
    const viewerContainer = document.getElementById('viewerContainer');
    viewerContainer.innerHTML = `
        <div id="occViewer" class="w-full h-full bg-gray-100 flex items-center justify-center">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading 3D Viewer...</p>
            </div>
        </div>
    `;
    
    if (occViewer) {
        occViewer = null;
    }
}

// Fungsi untuk load OCC WASM Viewer
async function loadOCCViewer(fileUrl, fileName) {
    const occViewerDiv = document.getElementById('occViewer');
    
    try {
        // Tampilkan loading
        occViewerDiv.innerHTML = `
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading CAD file: ${fileName}</p>
                <p class="text-sm text-gray-500 mt-2">This may take a few moments...</p>
            </div>
        `;
        
        // Inisialisasi OCC WASM
        if (typeof occ === 'undefined') {
            throw new Error('OCC WASM library not loaded');
        }
        
        // Buat canvas untuk OCC
        occViewerDiv.innerHTML = '<canvas id="occCanvas" class="w-full h-full"></canvas>';
        
        // Inisialisasi OCC viewer
        const canvas = document.getElementById('occCanvas');
        occViewer = await occ.init(canvas);
        
        // Load file CAD
        const response = await fetch(fileUrl);
        const fileBuffer = await response.arrayBuffer();
        
        // Konversi file berdasarkan ekstensi
        if (fileName.toLowerCase().endsWith('.igs') || fileName.toLowerCase().endsWith('.iges')) {
            await occViewer.loadIGES(fileBuffer);
        } else if (fileName.toLowerCase().endsWith('.step') || fileName.toLowerCase().endsWith('.stp')) {
            await occViewer.loadSTEP(fileBuffer);
        }
        
        // Fit view
        occViewer.fitAll();
        
    } catch (error) {
        console.error('Error loading CAD file:', error);
        occViewerDiv.innerHTML = `
            <div class="text-center text-red-600 p-8">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h3 class="text-lg font-semibold mb-2">Error Loading File</h3>
                <p class="text-sm">Failed to load CAD file: ${error.message}</p>
                <button onclick="closeViewer()" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    Close
                </button>
            </div>
        `;
    }
}

// Fungsi untuk load Model Viewer (format 3D biasa)
function loadModelViewer(fileUrl, fileName) {
    const viewerContainer = document.getElementById('viewerContainer');
    
    viewerContainer.innerHTML = `
        <model-viewer 
            src="${fileUrl}"
            alt="${fileName}"
            auto-rotate
            camera-controls
            shadow-intensity="1"
            environment-image="neutral"
            style="width: 100%; height: 100%;"
            class="bg-gray-100">
            <div class="progress-bar hide" slot="progress-bar">
                <div class="update-bar"></div>
            </div>
        </model-viewer>
    `;
}

// Preview filename when selected
document.getElementById('file3DInput')?.addEventListener('change', function(e) {
    if (e.target.files.length > 0) {
        const fileName = e.target.files[0].name;
        const nameInput = document.querySelector('input[name="nama"]');
        if (nameInput && !nameInput.value) {
            // Auto-fill name field with filename (without extension)
            nameInput.value = fileName.replace(/\.[^/.]+$/, "");
        }
    }
});

// Fungsi utility untuk modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

// Handle escape key untuk menutup viewer
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewer();
    }
});
</script>