<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Pour le moment, toujours autorisé (user_id = 7 simulé)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:100'],
            'type' => ['required', 'in:workshop,collection,training,repair_cafe'],
            'date_start' => ['required', 'date', 'after:now'],
            'date_end' => ['required', 'date', 'after:date_start'],
            'location' => ['required', 'string', 'max:255'],
            'max_participants' => ['required', 'integer', 'min:5', 'max:200'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'status' => ['required', 'in:draft,published'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // 2MB max
        ];

        // Si c'est une mise à jour, la date peut être dans le passé
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['date_start'] = ['required', 'date'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de l\'événement est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au minimum 100 caractères.',
            
            'type.required' => 'Le type d\'événement est obligatoire.',
            'type.in' => 'Le type d\'événement sélectionné n\'est pas valide.',
            
            'date_start.required' => 'La date de début est obligatoire.',
            'date_start.date' => 'La date de début doit être une date valide.',
            'date_start.after' => 'La date de début doit être dans le futur.',
            
            'date_end.required' => 'La date de fin est obligatoire.',
            'date_end.date' => 'La date de fin doit être une date valide.',
            'date_end.after' => 'La date de fin doit être après la date de début.',
            
            'location.required' => 'Le lieu est obligatoire.',
            'location.max' => 'Le lieu ne peut pas dépasser 255 caractères.',
            
            'max_participants.required' => 'Le nombre maximum de participants est obligatoire.',
            'max_participants.integer' => 'Le nombre de participants doit être un entier.',
            'max_participants.min' => 'Le nombre de participants doit être au minimum 5.',
            'max_participants.max' => 'Le nombre de participants ne peut pas dépasser 200.',
            
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'price.max' => 'Le prix ne peut pas dépasser 9999.99 DT.',
            
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou WEBP.',
            'image.max' => 'L\'image ne peut pas dépasser 2 Mo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titre',
            'description' => 'description',
            'type' => 'type d\'événement',
            'date_start' => 'date de début',
            'date_end' => 'date de fin',
            'location' => 'lieu',
            'max_participants' => 'nombre maximum de participants',
            'price' => 'prix',
            'status' => 'statut',
            'image' => 'image',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Si le champ publish_now est coché, on force le statut à published
        if ($this->has('publish_now') && $this->publish_now) {
            $this->merge([
                'status' => 'published'
            ]);
        }

        // Si le prix n'est pas renseigné ou vide, on le met à 0
        if (!$this->filled('price')) {
            $this->merge([
                'price' => 0
            ]);
        }
    }
}