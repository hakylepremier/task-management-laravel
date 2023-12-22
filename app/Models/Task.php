<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'goal_id',
        'state_id',
        'user_id'
    ];

    // /**
    //  * The model's default values for attributes.
    //  *
    //  * @var array
    //  */
    protected $attributes = [
        'user_id' => null,
        'goal_id' => null,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
