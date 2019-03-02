<?php

namespace App\Model;

use JustTheBasicz\Model as BasicModel;

class User extends BasicModel
{
    protected $table = 'users';
    public $timestamps = false;

    protected $fillable = array('first_name', 'last_name', 'email');
    protected $hidden = array('password');
}
