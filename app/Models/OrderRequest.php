<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    use HasFactory;

    protected $casts = [
        'data_g' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'origin',
        'creator',
        'creator_number',
        'technical_manager',
        'collection_date',
        'collection_number',
        'data_g',
        'status',
        'total',
        'cpf_technical',
        'id_tecnico',
        'owner_id',
        'uid',
        'tipo',
        'parceiro',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function orderRequestPayment()
    {
        return $this->hasMany(OrderRequestPayment::class, 'order_request_id');
    }
    public function tecnico()
    {
        return $this->hasOne(Tecnico::class, 'id', 'id_tecnico');
    }
    public function owner()
    {
        return $this->hasOne(Owner::class, 'id', 'owner_id');
    }
    public function animals()
    {
        return $this->hasMany(Animal::class, 'order_id');
    }
    public function payments()
    {
        return $this->hasOne(PaymentReturn::class, 'order_request_id');
    }
    public function datacoleta()
    {
        return $this->hasOne(DataColeta::class, 'id_order');
    }
}
