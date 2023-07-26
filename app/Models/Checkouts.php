<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkouts extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','book_id','checkout_date','return_date'];

    protected $visible = ['id','user_id','book_id','checkout_date','return_date'];
}
