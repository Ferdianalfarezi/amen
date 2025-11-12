{{-- resources/views/users/create.blade.php --}}
<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <!-- Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Full Name <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                   placeholder="Enter full name" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                Username <span class="text-red-500">*</span>
            </label>
            <input type="text" name="username" id="username" value="{{ old('username') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                   placeholder="Enter username" required>
            @error('username')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label for="departemen" class="block text-sm font-medium text-gray-700 mb-1">
                Department <span class="text-red-500">*</span>
            </label>
            <input type="text" name="departemen" id="departemen" value="{{ old('departemen') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                   placeholder="e.g., IT, Marketing" required>
            @error('departemen')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                Role <span class="text-red-500">*</span>
            </label>
            <select name="role" id="role"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" required>
                <option value="">Select Role</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
            </select>
            @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Row 3 -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password" id="password"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                   placeholder="Min 5 characters" required>
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Confirm Password <span class="text-red-500">*</span>
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                   placeholder="Re-enter password" required>
        </div>
    </div>

    <!-- Status -->
    <div class="mt-4">
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
            Status <span class="text-red-500">*</span>
        </label>
        <select name="status" id="status"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent" required>
            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Active</option>
            <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Inactive</option>
        </select>
        <p class="text-xs text-gray-500 mt-1">Inactive users cannot login to the system</p>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
        <p class="text-xs text-blue-800 leading-relaxed">
            <strong>User:</strong> Can create and manage own 3D models<br>
            <strong>Admin:</strong> Additional access to specific modules<br>
            <strong>Superadmin:</strong> Full access including user management
        </p>
    </div>

    <!-- Footer Buttons -->
    <div class="flex justify-end gap-3 pt-6 border-t mt-6">
        <button type="button" onclick="closeCreateModal()"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
            Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
            Create User
        </button>
    </div>
</form>