<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['postalCode']; // we have to guard postalCode because field_name is postal_code. can't add postalCode to sql query

    // protected $fillable = [
    //     'name',
    //     'type',
    //     'email',
    //     'address',
    //     'state',
    //     'city',
    //     'postal_code',
    // ];

    public function invoices(){
        return $this->hasMany(Invoice::class);
    }
}
