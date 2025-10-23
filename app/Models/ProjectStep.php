<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'step_number',
        'title',
        'description',
        'materials_needed',
        'tools_required',
        'duration'
    ];

    protected $casts = [
        'step_number' => 'integer',
    ];

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Scopes
    public function scopeOrdered($query)
    {
        return $query->orderBy('step_number');
    }

    // Helpers
    public function hasMaterials()
    {
        return !empty($this->materials_needed);
    }

    public function hasTools()
    {
        return !empty($this->tools_required);
    }

    public function hasDuration()
    {
        return !empty($this->duration);
    }

    public function getMaterialsList()
    {
        if (!$this->hasMaterials()) {
            return [];
        }
        
        return array_filter(
            array_map('trim', explode("\n", $this->materials_needed))
        );
    }

    public function getToolsList()
    {
        if (!$this->hasTools()) {
            return [];
        }
        
        return array_filter(
            array_map('trim', explode("\n", $this->tools_required))
        );
    }
}