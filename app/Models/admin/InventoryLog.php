<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryLog extends Model
{
    use HasFactory;
    
    // Prevent deletion and editing of logs
    protected $table = 'inventory_logs';
    
    protected $fillable = [
        'reference_type',
        'reference_id',
        'product_id',
        'product_name',
        'quantity_before',
        'quantity_change',
        'quantity_after',
        'unit_cost',
        'total_cost',
        'movement_type',
        'reason',
        'user_id',
        'user_name',
        'ip_address',
        'metadata'
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_change' => 'integer',
        'quantity_after' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'metadata' => 'array'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(\App\Models\admin\Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Movement types constants
    const MOVEMENT_SALE = 'sale';
    const MOVEMENT_CANCEL = 'cancel';
    const MOVEMENT_MANUAL_ADD = 'manual_add';
    const MOVEMENT_MANUAL_SUBTRACT = 'manual_subtract';
    const MOVEMENT_ADJUSTMENT = 'adjustment';

    // Reference types constants
    const REFERENCE_ORDER = 'order';
    const REFERENCE_MANUAL = 'manual';
    const REFERENCE_ADJUSTMENT = 'adjustment';

    // Scopes for filtering
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByMovementType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    public function scopeByReference($query, $referenceType, $referenceId)
    {
        return $query->where('reference_type', $referenceType)
                    ->where('reference_id', $referenceId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Get movement type label
    public function getMovementTypeLabelAttribute()
    {
        $labels = [
            self::MOVEMENT_SALE => 'بيع',
            self::MOVEMENT_CANCEL => 'إلغاء طلب',
            self::MOVEMENT_MANUAL_ADD => 'إضافة يدوية',
            self::MOVEMENT_MANUAL_SUBTRACT => 'خصم يدوي',
            self::MOVEMENT_ADJUSTMENT => 'تعديل مخزون'
        ];

        return $labels[$this->movement_type] ?? $this->movement_type;
    }

    // Check if movement is addition
    public function isAddition()
    {
        return $this->quantity_change > 0;
    }

    // Check if movement is subtraction
    public function isSubtraction()
    {
        return $this->quantity_change < 0;
    }

    // Get formatted quantity change
    public function getFormattedQuantityChangeAttribute()
    {
        $prefix = $this->isAddition() ? '+' : '';
        return $prefix . $this->quantity_change;
    }

    // Prevent deletion
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($log) {
            throw new \Exception('لا يمكن حذف سجلات المخزون - السجلات محمية للمراجعة والمحاسبة');
        });

        static::updating(function ($log) {
            throw new \Exception('لا يمكن تعديل سجلات المخزون - السجلات محمية للمراجعة والمحاسبة');
        });
    }
}
