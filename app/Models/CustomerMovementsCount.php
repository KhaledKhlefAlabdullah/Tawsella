<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMovementsCount extends Model
{
    use HasFactory, HasUuid;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'customer_id',
        'movements_count',
        'last_win_date'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

}
