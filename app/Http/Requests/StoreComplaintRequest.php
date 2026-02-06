<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'tempat_kejadian' => ['required', 'string', 'max:255'],
            'waktu_kejadian' => ['required', 'date'],
            'kronologis_singkat' => ['required', 'string'],
            'korban' => ['nullable', 'string', 'max:255'],
            'terlapor' => ['nullable', 'string', 'max:255'],
            'saksi_saksi' => ['nullable', 'string'],
            'cf-turnstile-response' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'no_hp' => trim((string) $this->input('no_hp')),
            'nik' => $this->input('nik') ? trim((string) $this->input('nik')) : null,
        ]);
    }
}
