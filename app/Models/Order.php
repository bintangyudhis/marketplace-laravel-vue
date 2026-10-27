<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use UUID;

    public const STATUS_BARU = 'baru';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_BATAL = 'batal';

    public const STATUSES = [
        self::STATUS_BARU,
        self::STATUS_DIPROSES,
        self::STATUS_SELESAI,
        self::STATUS_BATAL,
    ];

    protected $fillable = [
        'user_id',
        'code',
        'status',
        'total_price',
        'recipient_name',
        'phone',
        'address',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
