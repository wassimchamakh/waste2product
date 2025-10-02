<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DechetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'quantity' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'contact_phone' => 'nullable|string|max:15',
            'notes' => 'nullable|string|max:500',
        ];

        // Photo obligatoire seulement à la création
        if ($this->isMethod('post')) {
            $rules['photo'] = 'required|image|mimes:jpeg,png,jpg,webp|max:2048';
        } else {
            $rules['photo'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'description.required' => 'La description est obligatoire',
            'description.min' => 'La description doit contenir au moins 20 caractères',
            'quantity.required' => 'La quantité est obligatoire',
            'location.required' => 'La localisation est obligatoire',
            'category_id.required' => 'La catégorie est obligatoire',
            'category_id.exists' => 'Cette catégorie n\'existe pas',
            'photo.required' => 'La photo est obligatoire',
            'photo.image' => 'Le fichier doit être une image',
            'photo.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou WEBP',
            'photo.max' => 'L\'image ne doit pas dépasser 2 Mo',
            'contact_phone.max' => 'Le numéro de téléphone ne doit pas dépasser 15 caractères',
            'notes.max' => 'Les notes ne doivent pas dépasser 500 caractères',
        ];
    }
}
