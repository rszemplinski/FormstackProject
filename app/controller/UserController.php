<?php

namespace App\Controller;

use JustTheBasicz\Controller;
use App\Model\User;

class UserController extends Controller
{

    public function test()
    {
        $user = new User();
        $user->email = "test@test.com";
        $user->firstName = "das";
        $user->lastName = "sdasd";
        $user->password = "12341";
        print_r($user);
        $user->save();
        return $this->renderJSON(['test']);
    }
}
