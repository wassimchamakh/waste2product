<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForumCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Must be authenticated to comment
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:5', 'max:5000'],
            'forum_post_id' => ['required', 'exists:forum_posts,id'],
            'parent_id' => ['nullable', 'exists:forum_comments,id'],
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'body.required' => 'Le commentaire ne peut pas être vide.',
            'body.min' => 'Le commentaire doit contenir au moins 5 caractères.',
            'body.max' => 'Le commentaire ne peut pas dépasser 5000 caractères.',
            'forum_post_id.required' => 'Le post est requis.',
            'forum_post_id.exists' => 'Ce post n\'existe pas.',
            'parent_id.exists' => 'Le commentaire parent n\'existe pas.',
        ];
    }

    /**
     * Get custom attribute names
     */
    public function attributes(): array
    {
        return [
            'body' => 'commentaire',
            'forum_post_id' => 'post',
            'parent_id' => 'commentaire parent',
        ];
    }
}
