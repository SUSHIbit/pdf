<?php
// app/Models/CreditTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'credits',
        'amount',
        'stripe_session_id',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'credits' => 'integer',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

// app/Models/QuestionSet.php

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
        'document_id' => 'integer',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}