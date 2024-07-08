<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = ['postalCode']; // the request has postalCode & postal_code both. so we have to guard postalCode because field_name in db is postal_code. can't add postalCode to sql query

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
