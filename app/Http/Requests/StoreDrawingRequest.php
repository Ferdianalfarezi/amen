<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDrawingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Sesuaikan dengan logika otorisasi Anda
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tahun_project' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'customer' => 'nullable|string|max:255',
            'project' => 'nullable|string|max:255',
            'departemen' => 'nullable|string|max:255',
        ];
    }
}