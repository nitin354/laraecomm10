<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    public $table = 'users_address';
    protected $fillable = ['first_name', 'last_name', 'email', 'address', 'city', 'state', 'zip', 'mobile', 'apartment', 'notes', 'user_id'];
}
