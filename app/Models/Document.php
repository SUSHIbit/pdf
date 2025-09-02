<?php
// app/Models/Document.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'title',
        'folder_id',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'extracted_text',
        'question_count',
        'format',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'user_id' => 'integer',
        'folder_id' => 'integer',
        'question_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function questionSet(): HasOne
    {
        return $this->hasOne(QuestionSet::class);
    }

    public function getFileSizeFormatted(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes > 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getCreditCost(): int
    {
        return match($this->question_count) {
            10 => 1,
            20 => 2,
            30 => 3,
            default => 1,
        };
    }

    public function getDisplayName(): string
    {
        return $this->title ?: $this->original_name;
    }

    public function getFormatDisplay(): string
    {
        return match($this->format) {
            'mcq' => 'MCQ',
            'flashcard' => 'Flashcards',
            default => 'MCQ',
        };
    }

    public function getItemsText(): string
    {
        return match($this->format) {
            'mcq' => $this->question_count . ' questions',
            'flashcard' => $this->question_count . ' flashcards',
            default => $this->question_count . ' questions',
        };
    }
}