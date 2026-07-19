<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'   => 'required|exists:categories,id',
            'instructor_id' => 'required|exists:instructors,id',
            'judul'         => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'tanggal'       => 'required|date|after_or_equal:today',
            'jam'           => 'required|date_format:H:i',
            'lokasi'        => 'required|string|max:255',
            'kuota'         => 'required|integer|min:1',
            'harga'         => 'required|numeric|min:0',
            'poster'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'        => 'required|in:draft,published,cancelled',
        ];
    }
}
