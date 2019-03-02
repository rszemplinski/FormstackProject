<?php

namespace App\Model;

use JustTheBasicz\Model as BasicModel;

class User extends BasicModel
{
    protected $table = 'users';

    public $timestamps = false;

    public $firstName;
    public $lastName;
    public $email;
    public $password;
}
