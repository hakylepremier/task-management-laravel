<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Goal extends Model
{
    use HasFactory;

    // private $stage = Stage::firstOrCreate([
    //     'title' => 'Processing'
    // ])->id;

    protected $fillable = [
        'title',
        'description',
        'image',
        'category_id',
        'stage_id',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'end_date' => 'datetime'
    ];

    // /**
    //  * The model's default values for attributes.
    //  *
    //  * @var array
    //  */
    protected $attributes = [
        'image' => null,
        'category_id' => null,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
