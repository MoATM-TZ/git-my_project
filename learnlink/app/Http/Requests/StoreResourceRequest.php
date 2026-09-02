<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    /** Max accepted upload size: 20 MB. */
    private const MAX_SIZE_KB = 20480;

    public function authorize(): bool
    {
        return true; // Route is wrapped by the "auth" middleware.
    }

    /**
     * Validation rules replacing the ad-hoc checks that lived inside the
     * legacy upload_process.php.
     */
    public function rules(): array
    {
        $allowedMimes = implode(',', [
            'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx',
            'txt', 'md', 'csv', 'zip',
            'jpg', 'jpeg', 'png', 'gif', 'webp',
        ]);

        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category' => ['required', 'string', 'in:'.implode(',', array_keys(Resource::CATEGORIES))],
            'file' => [
                'required',
                'file',
                'max:'.self::MAX_SIZE_KB,
                'mimes:'.$allowedMimes,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'The file may not be larger than 20 MB.',
            'file.mimes' => 'Allowed file types: documents, spreadsheets, presentations, archives and images.',
        ];
    }
}
