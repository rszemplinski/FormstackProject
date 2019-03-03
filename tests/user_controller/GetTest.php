<?php

use PHPUnit\Framework\TestCase;
use App\Controller\UserController;
use JustTheBasicz\Request;
use JustTheBasicz\Response;

class GetTest extends TestCase
{

    public function testReturns200IfUserWithIDExists()
    {
        $controller = new UserController(new Request(), new Response());
        $result = $controller->get(1);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testReturns400IsUserDoesNotExist()
    {
        $controller = new UserController(new Request(), new Response());
        $result = $controller->get(3000);
        $this->assertEquals(400, $result->getStatusCode());
    }
}
