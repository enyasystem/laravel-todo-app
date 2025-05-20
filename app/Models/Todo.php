<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    /**
     * Get the category that owns the todo.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected $fillable = [
        'title', 
        'description', 
        'completed', 
        'category_id',
        'priority',
        'position'
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];
    
    /**
     * Get the priority label.
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            default => 'Low'
        };
    }
    
    /**
     * Get the priority color.
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            1 => 'bg-blue-100 text-blue-800',
            2 => 'bg-yellow-100 text-yellow-800',
            3 => 'bg-red-100 text-red-800',
            default => 'bg-blue-100 text-blue-800'
        };
    }
}
