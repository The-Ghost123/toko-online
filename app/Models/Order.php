<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAYMENT_SUBMITTED = 'payment_submitted';
    public const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_METHOD_BANK = 'bank';
    public const PAYMENT_METHOD_EWALLET = 'e-wallet';
    public const PAYMENT_METHOD_QRIS = 'qris';

    protected $fillable = [
        'user_id',
        'payment_method',
        'proof_photo',
        'status',
        'total_items',
        'total_price',
        'shipping_address',
        'shipping_latitude',
        'shipping_longitude',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'total_items' => 'integer',
        'total_price' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING_PAYMENT => 'Pending Payment',
            self::STATUS_PAYMENT_SUBMITTED => 'Payment Submitted',
            self::STATUS_PAYMENT_VERIFIED => 'Payment Verified',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_REFUNDED => 'Refunded',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getTimelineStepsAttribute(): array
    {
        return [
            ['key' => self::STATUS_PENDING_PAYMENT, 'label' => 'Pending Payment'],
            ['key' => self::STATUS_PAYMENT_SUBMITTED, 'label' => 'Payment Submitted'],
            ['key' => self::STATUS_PAYMENT_VERIFIED, 'label' => 'Payment Verified'],
            ['key' => self::STATUS_SHIPPED, 'label' => 'Shipped'],
        ];
    }

    public function getActiveStepIndexAttribute(): int
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return 0;
        }

        $steps = collect($this->timeline_steps)->pluck('key')->toArray();
        $position = array_search($this->status, $steps, true);

        if ($position === false) {
            return count($steps) - 1;
        }

        return $position;
    }

    public function isPaymentVerified(): bool
    {
        return $this->status === self::STATUS_PAYMENT_VERIFIED;
    }

    public function isShipped(): bool
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_PAYMENT,
            self::STATUS_PAYMENT_SUBMITTED,
            self::STATUS_PAYMENT_VERIFIED,
            self::STATUS_PROCESSING,
        ], true);
    }
}
