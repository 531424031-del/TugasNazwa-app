<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingSetting extends Model
{
    /** Default overall savings target (Rp1.000.000). */
    public const DEFAULT_TARGET = 1000000;

    /** Default daily savings target (Rp5.000). */
    public const DEFAULT_DAILY = 5000;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'target_amount',
        'daily_amount',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'daily_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** A settings row carrying the defaults, used before one is saved. */
    public static function makeDefault(): self
    {
        return new self([
            'target_amount' => self::DEFAULT_TARGET,
            'daily_amount' => self::DEFAULT_DAILY,
        ]);
    }
}
