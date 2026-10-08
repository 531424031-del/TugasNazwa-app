<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public const TYPE_INCOME = 'income';

    public const TYPE_EXPENSE = 'expense';

    /** Special income category used for the "uang saku" summary card. */
    public const CATEGORY_ALLOWANCE = 'Uang Saku';

    public const INCOME_CATEGORIES = [
        self::CATEGORY_ALLOWANCE,
        'Sampingan',
        'Hadiah',
        'Bonus',
        'Lainnya',
    ];

    public const EXPENSE_CATEGORIES = [
        'Makanan',
        'Transportasi',
        'Belanja',
        'Pulsa',
        'Kebutuhan Kuliah',
        'Lainnya',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'category',
        'description',
        'amount',
        'date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Limit records to the given owner. */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeOnDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('date', $date);
    }

    /** Free-text search across category, description, amount and date. */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('category', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->orWhere('amount', 'like', "%{$keyword}%")
                ->orWhere('date', 'like', "%{$keyword}%");
        });
    }

    /** Categories available for the given transaction type. */
    public static function categoriesFor(string $type): array
    {
        return $type === self::TYPE_EXPENSE
            ? self::EXPENSE_CATEGORIES
            : self::INCOME_CATEGORIES;
    }
}
