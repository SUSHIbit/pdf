<?php
// app/Models/Folder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function getDocumentCount(): int
    {
        return $this->documents()->count();
    }

    public function getCompletedDocumentCount(): int
    {
        return $this->documents()->where('status', 'completed')->count();
    }

    public function getTotalQuestionCount(): int
    {
        return $this->documents()->where('status', 'completed')->sum('question_count');
    }
}