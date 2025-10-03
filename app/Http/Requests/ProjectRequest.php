<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category_id' => 'required|exists:categories,id',
            'difficulty_level' => 'required|in:facile,intermédiaire,difficile',
            'estimated_time' => 'required|string|max:100',
            'impact_score' => 'required|integer|between:1,10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'steps' => 'required|array|min:1',
            'steps.*.title' => 'required|string|max:255',
            'steps.*.description' => 'required|string|min:20',
            'steps.*.materials_needed' => 'nullable|string',
            'steps.*.tools_required' => 'nullable|string',
            'steps.*.duration' => 'nullable|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Le titre du projet est obligatoire.',
            'description.required' => 'La description du projet est obligatoire.',
            'description.min' => 'La description doit contenir au moins 50 caractères.',
            'category_id.required' => 'Veuillez sélectionner une catégorie.',
            'difficulty_level.required' => 'Veuillez sélectionner un niveau de difficulté.',
            'difficulty_level.in' => 'Le niveau de difficulté doit être facile, intermédiaire ou difficile.',
            'estimated_time.required' => 'Le temps estimé est obligatoire.',
            'impact_score.required' => 'Le score d\'impact est obligatoire.',
            'impact_score.between' => 'Le score d\'impact doit être entre 1 et 10.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'L\'image ne doit pas dépasser 2MB.',
            'steps.required' => 'Au moins une étape est obligatoire.',
            'steps.min' => 'Le projet doit contenir au moins une étape.',
            'steps.*.title.required' => 'Le titre de l\'étape est obligatoire.',
            'steps.*.description.required' => 'La description de l\'étape est obligatoire.',
            'steps.*.description.min' => 'La description de l\'étape doit contenir au moins 20 caractères.',
        ];
    }
}