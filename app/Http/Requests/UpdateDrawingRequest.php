<?php

namespace App\Http\Requests;

use App\Models\DrawingFile;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDrawingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tahun_project' => 'required|integer|min:1900|max:' . date('Y'),
            'customer' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',

            // Multiple files untuk DrawingFile categories (optional saat update)
            'sample_part' => 'nullable|array',
            'sample_part.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,wmv,pdf,doc,docx,xls,xlsx,txt|max:512000',
            'sample_part_names' => 'nullable|array',
            'sample_part_names.*' => 'nullable|string|max:255',

            'quality' => 'nullable|array',
            'quality.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,wmv,pdf,doc,docx,xls,xlsx,txt|max:512000',
            'quality_names' => 'nullable|array',
            'quality_names.*' => 'nullable|string|max:255',

            'setup_procedure' => 'nullable|array',
            'setup_procedure.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,wmv,pdf,doc,docx,xls,xlsx,txt|max:512000',
            'setup_procedure_names' => 'nullable|array',
            'setup_procedure_names.*' => 'nullable|string|max:255',

            'quotes' => 'nullable|array',
            'quotes.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,txt|max:51200',
            'quotes_names' => 'nullable|array',
            'quotes_names.*' => 'nullable|string|max:255',

            'work_instruction' => 'nullable|array',
            'work_instruction.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,txt|max:51200',
            'work_instruction_names' => 'nullable|array',
            'work_instruction_names.*' => 'nullable|string|max:255',

            // Multiple 3D files untuk File3D (optional saat update)
            'files3d' => 'nullable|array',
            'files3d.*' => 'nullable|file|max:102400|mimetypes:application/octet-stream,model/gltf-binary,model/gltf+json',
            'files3d_names' => 'nullable|array',
            'files3d_names.*' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama drawing harus diisi',
            'tahun_project.required' => 'Tahun proyek harus diisi',
            'tahun_project.integer' => 'Tahun proyek harus berupa angka',
            'tahun_project.min' => 'Tahun proyek tidak boleh sebelum 1900',
            'tahun_project.max' => 'Tahun proyek tidak boleh melebihi tahun saat ini',
            'customer.required' => 'Customer harus diisi',
            'project.required' => 'Proyek harus diisi',
            'departemen.required' => 'Departemen harus diisi',

            'sample_part.*.mimes' => 'File sample part harus berformat JPEG, PNG, JPG, GIF, MP4, AVI, MOV, WMV, PDF, DOC, DOCX, XLS, XLSX, atau TXT',
            'sample_part.*.max' => 'Ukuran file sample part maksimal 500MB',

            'quality.*.mimes' => 'File quality harus berformat JPEG, PNG, JPG, GIF, MP4, AVI, MOV, WMV, PDF, DOC, DOCX, XLS, XLSX, atau TXT',
            'quality.*.max' => 'Ukuran file quality maksimal 500MB',

            'setup_procedure.*.mimes' => 'File setup procedure harus berformat JPEG, PNG, JPG, GIF, MP4, AVI, MOV, WMV, PDF, DOC, DOCX, XLS, XLSX, atau TXT',
            'setup_procedure.*.max' => 'Ukuran file setup procedure maksimal 500MB',

            'quotes.*.mimes' => 'File quotes harus berformat PDF, DOC, DOCX, XLS, XLSX, atau TXT',
            'quotes.*.max' => 'Ukuran file quotes maksimal 50MB',

            'work_instruction.*.mimes' => 'File work instruction harus berformat PDF, DOC, DOCX, XLS, XLSX, atau TXT',
            'work_instruction.*.max' => 'Ukuran file work instruction maksimal 50MB',

            'files3d.*.max' => 'Ukuran file 3D maksimal 100MB',
            'files3d.*.mimetypes' => 'File 3D harus berformat GLB, GLTF, atau OBJ',
        ];
    }
}