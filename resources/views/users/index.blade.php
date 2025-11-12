@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <h3 class="text-2xl font-bold text-gray-900">User Management</h3>
        @if(auth()->user()->hasPermission('user_management', 'create'))
        <a href="javascript:void(0)" onclick="openCreateModal()"
           class="bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg flex items-center transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add New User
        </a>
        @endif
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition" id="user-row-{{ $user->id }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->username }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $user->departemen }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->role === 'superadmin' ? 'bg-red-500 text-white' : 
                            ($user->role === 'admin' ? 'bg-black text-white' : 'bg-gray-500 text-white') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(auth()->user()->hasPermission('user_management', 'edit'))
                            <form action="{{ route('users.toggle-status', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status === 'aktif' ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }} transition">
                                    {{ ucfirst($user->status) }}
                                </button>
                            </form>
                            @else
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                @if(auth()->user()->hasPermission('user_management', 'edit'))
                                <button onclick="openPermissionModal({{ $user->id }}, this)" 
                                        class="text-green-600 hover:text-green-900"
                                        title="Manage Permissions">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </button>
                                @endif

                                @if(auth()->user()->hasPermission('user_management', 'edit'))
                                <button onclick="openEditModal({{ $user->id }})" 
                                        class="text-blue-600 hover:text-blue-900" 
                                        title="Edit User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                @endif

                                @if(auth()->user()->hasPermission('user_management', 'delete') && $user->id !== auth()->user()->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete User">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <p class="text-gray-500 text-lg">No users found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="bg-white px-6 py-4 border-t border-gray-200">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Permission Modal -->
<div id="permissionModal" 
     class="fixed inset-0 bg-gray-600 bg-opacity-0 overflow-y-auto h-full w-full z-50 hidden opacity-0 transition-opacity duration-300 ease-out">
    <div id="permissionModalContent"
         class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-lg bg-white 
                transform scale-95 transition-all duration-300 ease-out opacity-0">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-bold text-gray-900">Set Permissions - <span id="modalUserName"></span></h3>
            <button onclick="closePermissionModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mt-4" id="permissionContent">
            <div class="text-center py-8">
                <svg class="animate-spin h-8 w-8 mx-auto text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-gray-600">Loading...</p>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 pt-4 border-t mt-4">
            <button onclick="closePermissionModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Cancel</button>
            <button onclick="savePermissions()" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">Save Permissions</button>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div id="createUserModal" 
     class="fixed inset-0 bg-gray-600 bg-opacity-0 hidden flex items-center justify-center z-50 opacity-0 transition-all duration-300 ease-out">
    
    <div id="createModalContent"
         class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-bold text-gray-900">Add New User</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form via Include -->
        <div class="p-6">
            @include('users.create')
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editUserModal" 
     class="fixed inset-0 bg-gray-600 bg-opacity-0 hidden flex items-center justify-center z-50 opacity-0 transition-all duration-300 ease-out">
    
    <div id="editModalContent"
         class="bg-white rounded-xl shadow-xl w-full max-w-2xl mx-4 transform scale-95 opacity-0 transition-all duration-300 max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center border-b px-6 py-4">
            <h3 class="text-lg font-bold text-gray-900">Edit User</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Container - KOSONG AWALNYA -->
        <div class="p-6" id="editFormContainer">
            <!-- Tidak ada spinner, langsung load form -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentUserId = null;
let currentUserElement = null;

// === UTILS ===
function ucfirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).replace('_', ' ');
}

// === PERMISSION MODAL ===
function openPermissionModal(userId, element) {
    currentUserId = userId;
    currentUserElement = element;

    const permButton = element;
    const originalHTML = permButton.innerHTML;
    permButton.innerHTML = `
        <svg class="animate-spin h-4 w-4 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;
    permButton.disabled = true;

    const modal = document.getElementById('permissionModal');
    const modalContent = document.getElementById('permissionModalContent');

    // Skeleton loading
    document.getElementById('permissionContent').innerHTML = `
        <div class="animate-pulse">
            <div class="flex items-center mb-4">
                <div class="h-6 bg-gray-200 rounded w-1/4"></div>
            </div>
            <div class="space-y-3">
                <div class="h-4 bg-gray-200 rounded w-full"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                <div class="h-4 bg-gray-200 rounded w-4/6"></div>
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
            </div>
            <div class="mt-6 grid grid-cols-4 gap-4">
                ${Array.from({length: 12}, () => `
                    <div class="text-center">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mx-auto mb-2"></div>
                        <div class="h-6 bg-gray-200 rounded w-6 mx-auto"></div>
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0', 'bg-opacity-0');
        modal.classList.add('opacity-100', 'bg-opacity-50');
        modalContent.classList.remove('opacity-0', 'scale-95');
        modalContent.classList.add('opacity-100', 'scale-100');
    }, 10);

    // Load data
    fetch(`{{ url('users') }}/${userId}/permissions`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success) renderPermissions(data);
        else throw new Error(data.message || 'Gagal memuat izin');
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('permissionContent').innerHTML = `
            <div class="text-center py-8 text-red-600">
                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <p>${error.message}</p>
                <button onclick="closePermissionModal()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                    Tutup
                </button>
            </div>
        `;
    })
    .finally(() => {
        permButton.innerHTML = originalHTML;
        permButton.disabled = false;
    });
}

function closePermissionModal() {
    const modal = document.getElementById('permissionModal');
    const modalContent = document.getElementById('permissionModalContent');
    modal.classList.remove('opacity-100', 'bg-opacity-50');
    modal.classList.add('opacity-0', 'bg-opacity-0');
    modalContent.classList.remove('opacity-100', 'scale-100');
    modalContent.classList.add('opacity-0', 'scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('permissionContent').innerHTML = '';
    }, 300);

    currentUserId = null;
    currentUserElement = null;
}

function renderPermissions(data) {
    const { permissions, modules, is_superadmin } = data;
    const userName = currentUserElement.closest('tr').querySelector('.text-sm.font-medium').textContent;
    document.getElementById('modalUserName').textContent = userName;

    let html = '';
    if (is_superadmin) {
        html = `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mx-auto mb-2">
                    <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-green-800">Semua Fitur Terbuka untuk Superadmin!</h4>
                <p class="text-green-600 mt-2">Hak akses hanya bisa dikelola untuk user dengan role admin dan user.</p>
            </div>
        `;
    } else {
        html += `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                <p class="text-sm text-blue-800"><strong>Note:</strong> Download access is enabled for all users by default.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Module</th>
        `;
        const allActions = new Set();
        Object.values(modules).forEach(m => m.actions.forEach(a => allActions.add(a)));
        allActions.forEach(action => {
            html += `<th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">${ucfirst(action)}</th>`;
        });
        html += `</tr></thead><tbody class="bg-white divide-y divide-gray-200">`;
        Object.keys(modules).forEach(key => {
            const m = modules[key];
            html += `<tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium text-gray-900">${m.label}</td>`;
            allActions.forEach(action => {
                const hasAction = m.actions.includes(action);
                const isChecked = hasAction && permissions[key]?.[action];
                html += `<td class="px-4 py-3 text-center">
                    ${hasAction ? `
                        <input type="checkbox" class="w-4 h-4 text-gray-900 border-gray-300 rounded focus:ring-gray-900"
                               data-module="${key}" data-action="${action}" ${isChecked ? 'checked' : ''}>
                    ` : '<span class="text-gray-300">-</span>'}
                </td>`;
            });
            html += `</tr>`;
        });
        html += `</tbody></table></div>`;
    }
    document.getElementById('permissionContent').innerHTML = html;
}

function savePermissions() {
    if (!currentUserId) return;

    const checkboxes = document.querySelectorAll('#permissionContent input[type="checkbox"]');
    const permissions = {};
    checkboxes.forEach(cb => {
        const module = cb.dataset.module;
        const action = cb.dataset.action;
        if (!permissions[module]) permissions[module] = {};
        permissions[module][action] = cb.checked;
    });

    fetch(`{{ url('users') }}/${currentUserId}/permissions`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify({ permissions })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success', title: 'Success!', text: data.message,
                confirmButtonColor: '#1f2937', timer: 2000, timerProgressBar: true
            }).then(() => { closePermissionModal(); location.reload(); });
        } else {
            Swal.fire({ icon: 'error', title: 'Failed!', text: data.message || 'Gagal update izin' });
        }
    })
    .catch(err => {
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal simpan: ' + err.message });
    });
}

// === CREATE MODAL ===
function openCreateModal() {
    const modal = document.getElementById('createUserModal');
    const content = document.getElementById('createModalContent');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('bg-opacity-50', 'opacity-100');
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeCreateModal() {
    const modal = document.getElementById('createUserModal');
    const content = document.getElementById('createModalContent');
    modal.classList.remove('bg-opacity-50', 'opacity-100');
    modal.classList.add('bg-opacity-0', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

// === EDIT MODAL ===
function openEditModal(userId) {
    const editButton = event.target.closest('button');
    const originalHTML = editButton.innerHTML;
    editButton.innerHTML = `<svg class="animate-spin h-4 w-4 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
    editButton.disabled = true;

    fetch(`{{ url('users') }}/${userId}/edit`, {
        method: 'GET',
        headers: {
            'Accept': 'text/html',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(r => { if (!r.ok) throw new Error(`HTTP ${r.status}`); return r.text(); })
    .then(html => {
        document.getElementById('editFormContainer').innerHTML = html;
        const modal = document.getElementById('editUserModal');
        const content = document.getElementById('editModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('bg-opacity-50', 'opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        attachEditFormListeners(); // Re-attach setelah form dimuat
    })
    .catch(err => {
        document.getElementById('editFormContainer').innerHTML = `
            <div class="text-center py-8 text-red-600">
                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <p>${err.message}</p>
                <button onclick="closeEditModal()" class="mt-4 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Tutup</button>
            </div>
        `;
        const modal = document.getElementById('editUserModal');
        const content = document.getElementById('editModalContent');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('bg-opacity-50', 'opacity-100');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    })
    .finally(() => {
        editButton.innerHTML = originalHTML;
        editButton.disabled = false;
    });
}

function closeEditModal() {
    const modal = document.getElementById('editUserModal');
    const content = document.getElementById('editModalContent');
    modal.classList.remove('bg-opacity-50', 'opacity-100');
    modal.classList.add('bg-opacity-0', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('editFormContainer').innerHTML = '';
    }, 300);
}

function attachEditFormListeners() {
    const form = document.getElementById('editUserForm');
    if (!form) return;

    form.removeEventListener('submit', handleEditSubmit); // Hindari duplikat
    form.addEventListener('submit', handleEditSubmit);
}

function handleEditSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Updating...`;
    submitBtn.disabled = true;

    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Success!', text: data.message, timer: 2000, timerProgressBar: true })
                .then(() => { closeEditModal(); location.reload(); });
        } else {
            Swal.fire({ icon: 'error', title: 'Failed!', text: data.message || 'Gagal update user' });
        }
    })
    .catch(err => {
        Swal.fire({ icon: 'error', title: 'Error!', text: 'Gagal update: ' + err.message });
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// === DOM READY: EVENT DELEGATION ===
document.addEventListener('DOMContentLoaded', function () {
    // === DELETE CONFIRMATION ===
    document.addEventListener('click', function (e) {
        const deleteBtn = e.target.closest('button[type="submit"]');
        if (!deleteBtn) return;

        const form = deleteBtn.closest('form');
        if (!form) return;

        const isDeleteForm = form.action.includes('/users/') &&
                             form.method.toUpperCase() === 'POST' &&
                             form.querySelector('input[name="_method"][value="DELETE"]');

        if (!isDeleteForm) return;

        e.preventDefault();
        const userName = form.closest('tr')?.querySelector('.text-sm.font-medium')?.textContent.trim() || 'User';

        Swal.fire({
            title: 'Hapus User?',
            html: `Anda akan menghapus <strong>${userName}</strong>.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // === TOGGLE STATUS CONFIRMATION ===
    document.addEventListener('click', function (e) {
        const toggleBtn = e.target.closest('button[type="submit"]');
        if (!toggleBtn) return;

        const form = toggleBtn.closest('form');
        if (!form || !form.action.includes('/toggle-status')) return;

        e.preventDefault();
        const userName = form.closest('tr')?.querySelector('.text-sm.font-medium')?.textContent.trim() || 'User';
        const currentStatus = toggleBtn.textContent.trim();
        const newStatus = currentStatus.toLowerCase() === 'aktif' ? 'Nonaktif' : 'Aktif';

        Swal.fire({
            title: 'Ubah Status User?',
            html: `Ubah status <strong>${userName}</strong> dari <strong>${currentStatus}</strong> menjadi <strong>${newStatus}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1f2937',
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Ya, ubah ke ${newStatus}!`,
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });

    // === CLOSE MODAL ON BACKDROP ===
    ['permissionModal', 'createUserModal', 'editUserModal'].forEach(id => {
        const modal = document.getElementById(id);
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) {
                    if (id === 'permissionModal') closePermissionModal();
                    if (id === 'createUserModal') closeCreateModal();
                    if (id === 'editUserModal') closeEditModal();
                }
            });
        }
    });
});
</script>
@endpush