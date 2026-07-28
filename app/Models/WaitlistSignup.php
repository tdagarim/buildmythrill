<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaitlistSignup extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['email'];
}
