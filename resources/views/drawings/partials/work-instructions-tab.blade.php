@if(!auth()->user()->hasPermission('work_instructions', 'view'))
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
                Anda tidak memiliki akses untuk melihat <span class="font-semibold text-gray-900">Work Instructions</span>
            </p>
            <p class="text-sm text-gray-500">
                Silakan hubungi IT Department untuk mendapatkan akses ke halaman ini.
            </p>
            
        </div>
    </div>
@else

<div class="space-y-4">
    <!-- Header & Upload Button -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Work Instructions ({{ $files->count() }})</h3>
        @if (auth()->user()->role !== 'user')
            <button 
                class="upload-btn bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg flex items-center transition shadow-sm"
                data-modal-id="uploadModalWorkInstruction">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Upload Work Instruction
            </button>
        @endif
    </div>

    @if($files->count() > 0)
        <!-- File Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($files as $file)
                @php
                    $extension = strtolower(pathinfo($file->nama, PATHINFO_EXTENSION));
                    if (!$extension && $file->mime_type) {
                        $mimeMap = [
                            'image/jpeg' => 'jpg', 'image/jpg' => 'jpg', 'image/png' => 'png',
                            'image/gif' => 'gif', 'image/webp' => 'webp',
                            'video/mp4' => 'mp4', 'video/avi' => 'avi', 'video/mov' => 'mov', 'video/webm' => 'webm',
                            'application/pdf' => 'pdf', 
                            'application/vnd.ms-powerpoint' => 'ppt',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
                            'application/msword' => 'doc',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
                            'application/vnd.ms-excel' => 'xls',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx'
                        ];
                        $extension = $mimeMap[$file->mime_type] ?? '';
                    }

                    $typeLabel = '';
                    $iconColor = 'text-gray-400';
                    $bgColor = 'bg-gray-50';
                    $iconPath = '';

                    switch ($extension) {
                        case 'jpg': 
                        case 'jpeg': 
                        case 'png': 
                        case 'gif': 
                        case 'webp':
                            $typeLabel = 'Image'; 
                            $iconColor = 'text-blue-500'; 
                            $bgColor = 'bg-blue-50';
                            $iconPath = 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
                            break;
                            
                        case 'mp4': 
                        case 'avi': 
                        case 'mov': 
                        case 'webm': 
                        case 'mkv':
                            $typeLabel = 'Video'; 
                            $iconColor = 'text-purple-500';
                            $bgColor = 'bg-purple-50';
                            $iconPath = 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z';
                            break;
                            
                        case 'pdf':
                            $typeLabel = 'PDF'; 
                            $iconColor = 'text-red-500';
                            $bgColor = 'bg-red-50';
                            $iconPath = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z';
                            break;
                            
                        case 'doc': 
                        case 'docx':
                            $typeLabel = 'Word'; 
                            $iconColor = 'text-blue-600';
                            $bgColor = 'bg-blue-50';
                            $iconPath = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
                            break;
                            
                        case 'xls': 
                        case 'xlsx':
                            $typeLabel = 'Excel'; 
                            $iconColor = 'text-green-600';
                            $bgColor = 'bg-green-50';
                            $iconPath = 'M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z';
                            break;
                            
                        case 'ppt': 
                        case 'pptx':
                            $typeLabel = 'PowerPoint'; 
                            $iconColor = 'text-orange-600';
                            $bgColor = 'bg-orange-100';
                            $iconPath = 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01';
                            break;
                            
                        default:
                            $typeLabel = strtoupper($extension ?: 'File'); 
                            $iconColor = 'text-gray-500';
                            $bgColor = 'bg-gray-50';
                            $iconPath = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';
                            break;
                    }
                @endphp

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                    <div 
                        class="relative h-48 bg-gray-100 cursor-pointer preview-btn"
                        data-file-url="{{ asset('storage/' . $file->file_path) }}"
                        data-file-type="{{ $extension }}"
                        data-file-name="{{ $file->nama }}"
                        data-file-id="{{ $file->id }}"
                        data-mime-type="{{ $file->mime_type ?? '' }}">
                        
                        @if($file->isImage())
                            {{-- Tampilkan gambar langsung --}}
                            <img src="{{ asset('storage/' . $file->file_path) }}" 
                                 alt="{{ $file->nama }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                 
                        @elseif($file->isVideo())
                            @if($file->hasThumbnail())
                                {{-- Tampilkan thumbnail video dengan play button --}}
                                <div class="relative w-full h-full">
                                    <img src="{{ $file->thumbnail_url }}" 
                                         alt="{{ $file->nama }}" 
                                         class="w-full h-full object-cover">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="bg-black bg-opacity-60 rounded-full p-4 group-hover:bg-opacity-80 transition">
                                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Fallback: tampilkan video element --}}
                                <video class="w-full h-full object-cover opacity-90 group-hover:opacity-100">
                                    <source src="{{ asset('storage/' . $file->file_path) }}" type="{{ $file->mime_type }}">
                                </video>
                            @endif
                            
                        @else
                            @if($file->hasThumbnail())
                                {{-- Tampilkan thumbnail dokumen (PDF, PPT, DOC, XLS) --}}
                                <div class="relative w-full h-full">
                                    <img src="{{ $file->thumbnail_url }}" 
                                         alt="{{ $file->nama }}" 
                                         class="w-full h-full object-contain bg-white">
                                    {{-- Badge tipe file di pojok --}}
                                    <div class="absolute top-2 right-2 px-2 py-1 {{ str_replace('text-', 'bg-', $iconColor) }} text-white text-xs font-bold rounded shadow">
                                        {{ strtoupper($extension) }}
                                    </div>
                                </div>
                            @else
                                {{-- Fallback: tampilkan icon jika thumbnail gagal --}}
                                <div class="flex flex-col items-center justify-center h-full {{ $bgColor }}">
                                    <svg class="w-16 h-16 {{ $iconColor }} mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                                    </svg>
                                    <span class="text-sm font-semibold {{ $iconColor }}">{{ $typeLabel }}</span>
                                    <span class="text-xs text-gray-500 mt-1">{{ strtoupper($extension) }}</span>
                                </div>
                            @endif
                        @endif

                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="text-white bg-black bg-opacity-50 px-3 py-1 rounded text-sm font-medium pointer-events-none">Click to Preview</span>
                        </div>
                    </div>

                    <div class="p-4">
                        <h4 class="font-semibold text-gray-900 truncate mb-2 text-base" title="{{ $file->nama }}">{{ $file->nama }}</h4>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                            <span>{{ number_format($file->ukuran / 1024, 2) }} KB</span>
                            <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 rounded-full text-gray-700 font-medium">{{ strtoupper($extension) }}</span>
                        </div>
                        @if($file->deskripsi)
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $file->deskripsi }}</p>
                        @endif

                        <div class="grid grid-cols-3 gap-2">
                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                               download="{{ $file->nama }}"
                               class="col-span-2 bg-black hover:bg-green-600 text-white px-3 py-2.5 rounded-lg flex items-center justify-center transition-all shadow-sm hover:shadow-md text-sm font-medium"
                               title="Download {{ $file->nama }}">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </a>
                            <button 
                                onclick="deleteFile('{{ route('drawings.deleteWorkInstruction', $file->id) }}', '{{ $file->nama }}')"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-2.5 rounded-lg flex items-center justify-center transition-all text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum ada Work Instruction</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Upload work instruction pertama Anda untuk panduan kerja.</p>
            <button 
                class="upload-btn bg-gray-800 hover:bg-gray-900 text-white px-8 py-3 rounded-lg inline-flex items-center transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                data-modal-id="uploadModalWorkInstruction">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Upload Work Instruction
            </button>
        </div>
    @endif
</div>

<!-- Upload Modal untuk Work Instruction -->
<div id="uploadModalWorkInstruction" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" onclick="if(event.target === this) closeModal('uploadModalWorkInstruction')">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Upload Work Instruction</h3>
            <button onclick="closeModal('uploadModalWorkInstruction')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="uploadWorkInstructionForm" action="{{ route('drawings.uploadWorkInstruction', $drawing) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <!-- File Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">File *</label>
                    <div class="relative">
                        <input 
                            type="file" 
                            name="file" 
                            id="fileWorkInstructionInput"
                            required
                            accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-800 hover:file:bg-gray-100">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="font-medium">Supported:</span> Gambar, Video, PDF, Word, Excel, PowerPoint
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
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
                        rows="3"
                        placeholder="Deskripsi work instruction (opsional)"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-800 focus:border-gray-800 transition resize-none"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4">
                    <button 
                        type="button" 
                        onclick="closeModal('uploadModalWorkInstruction')"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-lg transition font-medium">
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-lg transition font-medium shadow-md hover:shadow-lg">
                        Upload
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-[100] flex items-center justify-center p-4">
    <div class="relative max-w-5xl w-full">
        <button onclick="closeModal('previewModal')" class="absolute top-4 right-4 text-white bg-black bg-opacity-50 p-2 rounded-full hover:bg-opacity-70 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div id="previewContent" class="rounded-xl overflow-hidden shadow-2xl bg-black"></div>
    </div>
</div>

<style>
    .group:hover { transform: translateY(-4px); }
    .preview-btn:hover { cursor: pointer; }
    .preview-btn:active { transform: scale(0.98); }
</style>

@endif

<script>
    // Buka modal upload
    document.querySelectorAll('.upload-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = document.getElementById(btn.dataset.modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        });
    });

    // Tutup modal
    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (id === 'previewModal') {
                document.getElementById('previewContent').innerHTML = '';
                // Reset slide variables
                window.currentSlides = null;
                window.currentSlideIndex = 0;
            }
        }
    }

    // Preview file - FULL UPDATE
    document.addEventListener('click', function(e) {
        if (e.target.closest('.preview-btn')) {
            const btn = e.target.closest('.preview-btn');
            const url = btn.dataset.fileUrl;
            const ext = btn.dataset.fileType.toLowerCase();
            const mime = btn.dataset.mimeType;
            const name = btn.dataset.fileName;
            const fileId = btn.dataset.fileId;
            const content = document.getElementById('previewContent');

            const imageExt = ['jpg','jpeg','png','gif','webp'];
            const videoExt = ['mp4','avi','mov','webm','mkv'];
            
            if (imageExt.includes(ext) || mime.startsWith('image/')) {
                // Preview gambar
                content.innerHTML = `<img src="${url}" alt="${name}" class="w-full max-h-[80vh] object-contain mx-auto">`;
            } 
            else if (videoExt.includes(ext) || mime.startsWith('video/')) {
                // Preview video
                content.innerHTML = `<video controls autoplay class="w-full max-h-[80vh] mx-auto">
                    <source src="${url}" type="${mime}">
                    Your browser does not support the video tag.
                </video>`;
            } 
            else if (ext === 'pdf' || mime === 'application/pdf') {
                // Preview PDF
                content.innerHTML = `<iframe src="${url}" class="w-full h-[80vh]" frameborder="0"></iframe>`;
            } 
            else if (ext === 'ppt' || ext === 'pptx') {
                // Preview PPT sebagai slide show
                content.innerHTML = `
                    <div class="bg-white h-[80vh] flex flex-col">
                        <div class="p-3 bg-gray-800 text-white flex justify-between items-center">
                            <span class="text-sm font-semibold">${name}</span>
                            <span id="slideCounter" class="text-xs bg-gray-700 px-3 py-1 rounded">Loading...</span>
                        </div>
                        <div class="flex-1 relative bg-gray-900 flex items-center justify-center">
                            <div id="slideContainer" class="w-full h-full flex items-center justify-center">
                                <div class="text-white text-center">
                                    <svg class="animate-spin h-12 w-12 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="text-sm">Converting presentation to slides...</p>
                                    <p class="text-xs text-gray-400 mt-2">This may take a moment</p>
                                </div>
                            </div>
                            <!-- Navigation buttons -->
                            <button id="prevSlide" class="hidden absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-60 hover:bg-opacity-80 text-white p-3 rounded-full transition z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <button id="nextSlide" class="hidden absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-60 hover:bg-opacity-80 text-white p-3 rounded-full transition z-10">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <div class="p-2 bg-gray-100 flex justify-center gap-2">
                            <a href="${url}" download class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download PPT
                            </a>
                        </div>
                    </div>`;
                
                // Load slides via AJAX
                fetch(`/preview-ppt/${fileId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.slides.length > 0) {
                            window.currentSlides = data.slides;
                            window.currentSlideIndex = 0;
                            showSlide(0);
                            
                            // Show navigation
                            document.getElementById('prevSlide').classList.remove('hidden');
                            document.getElementById('nextSlide').classList.remove('hidden');
                            
                            // Navigation handlers
                            document.getElementById('prevSlide').onclick = () => {
                                if (window.currentSlideIndex > 0) {
                                    window.currentSlideIndex--;
                                    showSlide(window.currentSlideIndex);
                                }
                            };
                            
                            document.getElementById('nextSlide').onclick = () => {
                                if (window.currentSlideIndex < window.currentSlides.length - 1) {
                                    window.currentSlideIndex++;
                                    showSlide(window.currentSlideIndex);
                                }
                            };
                            
                            // Keyboard navigation
                            const keyHandler = function(e) {
                                if (e.key === 'ArrowLeft' && window.currentSlideIndex > 0) {
                                    window.currentSlideIndex--;
                                    showSlide(window.currentSlideIndex);
                                } else if (e.key === 'ArrowRight' && window.currentSlideIndex < window.currentSlides.length - 1) {
                                    window.currentSlideIndex++;
                                    showSlide(window.currentSlideIndex);
                                }
                            };
                            
                            document.addEventListener('keydown', keyHandler);
                            
                            // Store handler untuk cleanup nanti
                            window.slideKeyHandler = keyHandler;
                        } else {
                            document.getElementById('slideContainer').innerHTML = `
                                <div class="text-white text-center">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg mb-2">Failed to load presentation</p>
                                    <p class="text-sm text-gray-400">Please try downloading the file</p>
                                </div>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading slides:', error);
                        document.getElementById('slideContainer').innerHTML = `
                            <div class="text-white text-center">
                                <svg class="w-16 h-16 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-lg mb-2">Error loading presentation</p>
                                <p class="text-sm text-gray-400">${error.message}</p>
                            </div>`;
                    });
            }
            else if (['doc','docx','xls','xlsx'].includes(ext)) {
                // Preview Office documents lainnya
                const isLocalhost = window.location.hostname === 'localhost' || 
                                  window.location.hostname === '127.0.0.1' ||
                                  window.location.hostname.includes('192.168');
                
                if (isLocalhost) {
                    content.innerHTML = `
                        <div class="text-white text-center p-8 bg-gray-800 rounded-lg">
                            <svg class="w-16 h-16 mx-auto mb-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-xl mb-2 font-semibold">Preview tidak tersedia di Localhost</p>
                            <p class="text-sm text-gray-300 mb-1">${name}</p>
                            <p class="text-xs text-gray-400 mb-6">File ${ext.toUpperCase()} memerlukan URL publik untuk preview online</p>
                            <a href="${url}" download 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download File
                            </a>
                            <p class="text-xs text-gray-500 mt-4">💡 Tip: Upload ke server production untuk preview online</p>
                        </div>`;
                } else {
                    const fullUrl = url.startsWith('http') ? url : window.location.origin + url;
                    content.innerHTML = `
                        <div class="bg-white h-[80vh] flex flex-col">
                            <div class="p-3 bg-gray-100 border-b flex justify-between items-center">
                                <span class="text-sm text-gray-600">${name}</span>
                                <a href="${url}" download 
                                   class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded transition">
                                    Download
                                </a>
                            </div>
                            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fullUrl)}" 
                                    class="w-full flex-1" 
                                    frameborder="0"
                                    onload="this.style.opacity=1"
                                    onerror="this.parentElement.innerHTML='<div class=\'p-8 text-center\'><p class=\'text-red-600 mb-4\'>Gagal memuat preview</p><a href=\'${url}\' download class=\'bg-blue-600 text-white px-4 py-2 rounded\'>Download File</a></div>'"
                                    style="opacity:0;transition:opacity 0.3s">
                            </iframe>
                        </div>`;
                }
            } 
            else {
                // File lain
                content.innerHTML = `
                    <div class="text-white text-center p-8 bg-gray-800 rounded-lg">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <p class="text-lg mb-2">Preview tidak tersedia</p>
                        <p class="text-sm text-gray-400 mb-4">${name}</p>
                        <a href="${url}" download 
                           class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download
                        </a>
                    </div>`;
            }

            const modal = document.getElementById('previewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    });

    // Function to show slide
    function showSlide(index) {
        const container = document.getElementById('slideContainer');
        const counter = document.getElementById('slideCounter');
        const slides = window.currentSlides;
        
        if (!slides || !slides[index]) return;
        
        container.innerHTML = `
            <img src="${slides[index]}" 
                 alt="Slide ${index + 1}" 
                 class="max-w-full max-h-full object-contain"
                 style="max-height: calc(80vh - 120px);">
        `;
        
        counter.textContent = `Slide ${index + 1} / ${slides.length}`;
        
        // Update button states
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');
        
        if (prevBtn) {
            prevBtn.disabled = index === 0;
            prevBtn.style.opacity = index === 0 ? '0.5' : '1';
            prevBtn.style.cursor = index === 0 ? 'not-allowed' : 'pointer';
        }
        
        if (nextBtn) {
            nextBtn.disabled = index === slides.length - 1;
            nextBtn.style.opacity = index === slides.length - 1 ? '0.5' : '1';
            nextBtn.style.cursor = index === slides.length - 1 ? 'not-allowed' : 'pointer';
        }
    }

    // Upload form handler
    document.getElementById('uploadWorkInstructionForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>`;

        fetch(this.action, {
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
                closeModal('uploadModalWorkInstruction');
                this.reset();
                
                const notif = document.createElement('div');
                notif.className = 'fixed top-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg z-[200] flex items-center gap-2';
                notif.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> ${data.message}`;
                document.body.appendChild(notif);
                setTimeout(() => notif.remove(), 3000);

                // Reload tab
                if (typeof loadTabContent === 'function') {
                    const activeTab = document.querySelector('.tab-link.active');
                    if (activeTab) loadTabContent(activeTab.dataset.tab, activeTab.dataset.url);
                } else {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(() => alert('Terjadi kesalahan saat upload'))
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });

    // Auto-fill nama dari filename
    document.getElementById('fileWorkInstructionInput')?.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            const nameInput = this.form.querySelector('input[name="nama"]');
            if (nameInput && !nameInput.value) {
                nameInput.value = fileName.replace(/\.[^/.]+$/, "");
            }
        }
    });

    // Escape key
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal('uploadModalWorkInstruction');
            
            // Cleanup keyboard handler saat tutup modal
            if (window.slideKeyHandler) {
                document.removeEventListener('keydown', window.slideKeyHandler);
                window.slideKeyHandler = null;
            }
            
            closeModal('previewModal');
        }
    });
</script>