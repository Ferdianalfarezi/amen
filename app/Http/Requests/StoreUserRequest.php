<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // PERBAIKAN: Direct check role
        return Auth::check() && Auth::user()->role === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:5|confirmed',
            'departemen' => 'required|string|max:255',
            'role' => 'required|in:superadmin,admin,user',
            'status' => 'required|in:aktif,tidak_aktif',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 5 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'departemen.required' => 'Departemen harus diisi',
            'role.required' => 'Role harus dipilih',
            'status.required' => 'Status harus dipilih',
        ];
    }
}