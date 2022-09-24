<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'sub_total',
        'total',
        'customer_id',
        'number',
        'date',
        'due_date',
        'discount',
        'refrence',
        'terms_and_conditions'
    ];
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function invoice_items(){
        return $this->hasMany(InvoiceItem::class);
    }
}
