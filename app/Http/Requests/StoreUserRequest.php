<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDrawingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
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
            
            // Semua file maksimal 200MB
            '3d_files.*' => 'nullable|file|max:204800', // 200MB
            'sample_parts.*' => 'nullable|file|max:204800', // 200MB
            'quality_docs.*' => 'nullable|file|max:204800', // 200MB
            'setup_procedures.*' => 'nullable|file|max:204800', // 200MB
            'quotes.*' => 'nullable|file|max:204800', // 200MB
            'work_instructions.*' => 'nullable|file|max:204800', // 200MB
        ];
    }

    public function messages()
    {
        return [
            '*.max' => 'File tidak boleh lebih dari 200MB',
            '3d_files.*.max' => 'File 3D tidak boleh lebih dari 200MB',
            'sample_parts.*.max' => 'File sample part tidak boleh lebih dari 200MB',
            'quality_docs.*.max' => 'File dokumen kualitas tidak boleh lebih dari 200MB',
            'setup_procedures.*.max' => 'File prosedur setup tidak boleh lebih dari 200MB',
            'quotes.*.max' => 'File quote tidak boleh lebih dari 200MB',
            'work_instructions.*.max' => 'File instruksi kerja tidak boleh lebih dari 200MB',
        ];
    }
}