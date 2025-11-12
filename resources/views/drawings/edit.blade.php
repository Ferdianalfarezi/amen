@extends('layouts.app')

@section('title', 'Edit Drawing')
@section('page-title', 'Edit Drawing')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Edit Drawing</h3>
            <p class="text-gray-600 mt-1">Update drawing information and 2D photos</p>
        </div>

        <form action="{{ route('drawings.update', $drawing->id) }}" method="POST" enctype="multipart/form-data" id="drawingForm">
            @csrf
            @method('PUT')

            <!-- Nama Drawing -->
            <div class="mb-6">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                    Drawing Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="nama" 
                       id="nama" 
                       value="{{ old('nama', $drawing->nama) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                       placeholder="Enter drawing name"
                       required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                          placeholder="Enter drawing description">{{ old('deskripsi', $drawing->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid untuk Tahun, Customer, Project, Departemen -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tahun Project -->
                <div>
                    <label for="tahun_project" class="block text-sm font-medium text-gray-700 mb-2">
                        Project Year
                    </label>
                    <input type="number" 
                           name="tahun_project" 
                           id="tahun_project" 
                           value="{{ old('tahun_project', $drawing->tahun_project) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                           placeholder="e.g., 2025"
                           min="1900"
                           max="2100">
                    @error('tahun_project')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer -->
                <div>
                    <label for="customer" class="block text-sm font-medium text-gray-700 mb-2">
                        Customer
                    </label>
                    <input type="text" 
                           name="customer" 
                           id="customer" 
                           value="{{ old('customer', $drawing->customer) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                           placeholder="Enter customer name">
                    @error('customer')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project -->
                <div>
                    <label for="project" class="block text-sm font-medium text-gray-700 mb-2">
                        Project
                    </label>
                    <input type="text" 
                           name="project" 
                           id="project" 
                           value="{{ old('project', $drawing->project) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                           placeholder="Enter project name">
                    @error('project')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Departemen -->
                <div>
                    <label for="departemen" class="block text-sm font-medium text-gray-700 mb-2">
                        Department
                    </label>
                    <input type="text" 
                           name="departemen" 
                           id="departemen" 
                           value="{{ old('departemen', $drawing->departemen) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                           placeholder="e.g., Design, R&D, Production">
                    @error('departemen')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Existing 2D Photos -->
            @if($drawing->files2d->count() > 0)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Existing 2D Photos ({{ $drawing->files2d->count() }})
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="existing_files_2d">
                    @foreach($drawing->files2d as $file2d)
                        <div class="relative group" id="existing-file2d-{{ $file2d->id }}">
                            <img src="{{ asset('storage/' . $file2d->file_path) }}" 
                                 alt="Photo" 
                                 class="w-full h-32 object-cover rounded-lg border border-gray-300">
                            <button type="button" 
                                    onclick="deleteFile2D({{ $file2d->id }})"
                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                            <p class="text-xs text-gray-600 mt-1 truncate">{{ $file2d->nama }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Add New 2D Photos -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Add New 2D Photos
                    </label>
                    <button type="button" 
                            onclick="document.getElementById('files_2d_input').click()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Photos
                    </button>
                </div>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition cursor-pointer" onclick="document.getElementById('files_2d_input').click()">
                    <input type="file" 
                           id="files_2d_input"
                           accept="image/*"
                           multiple
                           class="hidden"
                           onchange="handlePhotos(this)">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-600 font-medium mb-1">Click to upload new 2D photos</p>
                    <p class="text-gray-400 text-sm">JPEG, PNG, JPG, GIF, WEBP (Max 10MB each)</p>
                </div>

                <div id="files_2d_list" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-1">Managing Other Files</h4>
                        <p class="text-sm text-blue-700">
                            To manage 3D files, sample parts, and other documents, please go to the detail page where you can upload files in their respective tabs.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg transition">
                    Update Drawing
                </button>
                <a href="{{ route('drawings.show', $drawing->id) }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-lg text-center transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Store new files in array
    let files2DArray = [];

    // ========== DELETE EXISTING FILE 2D ==========
    function deleteFile2D(id) {
        if (confirm('Are you sure you want to delete this photo?')) {
            fetch(`/files-2d/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`existing-file2d-${id}`).remove();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete photo');
            });
        }
    }

    // ========== NEW 2D PHOTOS ==========
    function handlePhotos(input) {
        const newFiles = Array.from(input.files);
        
        newFiles.forEach(file => {
            const exists = files2DArray.some(f => f.name === file.name && f.size === file.size);
            if (!exists) {
                files2DArray.push(file);
            }
        });
        
        renderPhotos();
        input.value = '';
    }

    function renderPhotos() {
        const container = document.getElementById('files_2d_list');
        container.innerHTML = '';
        
        files2DArray.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <button type="button" 
                            onclick="removePhoto(${index})"
                            class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border border-gray-300">
                    <p class="text-xs text-gray-600 mt-1 truncate">${file.name}</p>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        
        updateFormFiles();
    }

    function removePhoto(index) {
        files2DArray.splice(index, 1);
        renderPhotos();
    }

    // ========== UPDATE FORM FILES ==========
    function updateFormFiles() {
        const form = document.getElementById('drawingForm');
        
        // Remove old file inputs
        form.querySelectorAll('input[name="files_2d[]"], input[name="file_2d_names[]"]').forEach(input => {
            if (input.id !== 'files_2d_input') {
                input.remove();
            }
        });
        
        // Add 2D photos
        files2DArray.forEach((file, index) => {
            const input = document.createElement('input');
            input.type = 'file';
            input.name = 'files_2d[]';
            input.style.display = 'none';
            
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            
            form.appendChild(input);
            
            // Add name input
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'file_2d_names[]';
            nameInput.value = file.name.split('.')[0];
            form.appendChild(nameInput);
        });
    }
</script>
@endpush
@endsection