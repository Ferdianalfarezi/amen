<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'view')) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'create')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah user.');
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'create')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menambah user.'
                ], 403);
            }
            return back()->with('error', 'Anda tidak memiliki akses untuk menambah user.');
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'departemen' => 'required|string|max:255',
            'password' => 'required|string|min:5|confirmed',
            'role' => 'required|in:user,admin,superadmin',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'departemen' => $validated['departemen'],
            'role' => $validated['role'],
            'status' => $validated['status'] ?? 'aktif',
            'permissions' => User::getDefaultPermissionsByRole($validated['role']),
        ];

        $user = User::create($userData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan!',
                'user' => $user
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'edit')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk edit user.'
                ], 403);
            }
            abort(403, 'Anda tidak memiliki akses untuk edit user.');
        }

        // If AJAX request, return JSON
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'departemen' => $user->departemen,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at->toIso8601String(),
                    'updated_at' => $user->updated_at->toIso8601String(),
                    'drawings_count' => $user->drawings()->count()
                ]
            ]);
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'edit')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk edit user.'
                ], 403);
            }
            return back()->with('error', 'Anda tidak memiliki akses untuk edit user.');
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'departemen' => 'required|string|max:255',
            'password' => 'nullable|string|min:5|confirmed',
            'role' => 'required|in:user,admin,superadmin',
            'status' => 'required|in:aktif,tidak_aktif'
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'departemen' => $validated['departemen'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        // Update permissions jika role berubah
        if ($validated['role'] !== $user->role) {
            $data['permissions'] = User::getDefaultPermissionsByRole($validated['role']);
        }

        $user->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate!',
                'user' => $user
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'delete')) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus user.');
        }

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function toggleStatus(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'edit')) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah status user.');
        }

        $user->update([
            'status' => $user->status === 'aktif' ? 'tidak_aktif' : 'aktif'
        ]);

        return back()->with('success', 'Status user berhasil diubah!');
    }

    /**
     * Get user permissions (for modal)
     */
    public function getPermissions(User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'view')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'permissions' => $user->getPermissions(),
            'modules' => User::getAvailableModules(),
            'is_superadmin' => $user->isSuperadmin(),
        ]);
    }

    /**
     * Update user permissions
     */
    public function updatePermissions(Request $request, User $user)
    {
        // Check permission
        if (!auth()->user()->hasPermission('user_management', 'edit')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah permissions.'
            ], 403);
        }

        // Superadmin tidak perlu permissions
        if ($user->isSuperadmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Superadmin memiliki akses penuh ke semua fitur!'
            ], 400);
        }

        $request->validate([
            'permissions' => 'required|array',
        ]);

        $user->update([
            'permissions' => $request->permissions
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permissions berhasil diupdate!'
        ]);
    }
}