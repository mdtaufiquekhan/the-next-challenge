<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'cover_image',
        'review_challenge',
        'review_solution',
        'review_submission',
        'review_evaluation',
        'review_participation',
        'review_awards',
        'review_deadline',
        'review_resources',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        // No JSON or date fields defined in this schema
    ];
}