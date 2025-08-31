<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'questions_answers',
    ];

    protected $casts = [
        'questions_answers' => 'array',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}