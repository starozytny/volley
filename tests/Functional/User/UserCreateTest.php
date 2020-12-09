<?php


namespace App\Tests\Functional\User;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserCreateTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    const URL_CREATE = "/api/users/";

    public function testCreateUserAdminRole()
    {
        $client = static::createClient();
        $this->loginUserAdmin($client);

        $json = '{
            "username":"henry", 
            "email":"henry@outlook.fr", 
            "password":"azerty"
        }';

        $this->sendRequestJson($client, "POST", self::URL_CREATE, $json, 200);
    }

    public function testCreateUserWrongRole()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $json = '{
            "username":"henry", 
            "email":"henry@outlook.fr", 
            "password":"azerty"
        }';

        $this->sendRequestJson($client, "POST", self::URL_CREATE, $json, 403);
    }

    public function testCreateUserWrongEmail()
    {
        $client = static::createClient();
        $this->loginUserAdmin($client);

        $json = '{
            "username":"henry", 
            "email":"henry", 
            "password":"azerty"
        }';

        $this->sendRequestJson($client, "POST", self::URL_CREATE, $json, 400);
    }

    public function testCreateUserNoWriteableProperty()
    {
        $client = static::createClient();
        $this->loginUserAdmin($client);

//        $this->createUserApi($client, [ 'roles' => ['ROLE_USER'] ], 400);
    }

    public function testCreateUserEmpty()
    {
        $client = static::createClient();
        $this->loginUserAdmin($client);

//        $this->createUserApi($client, [], 400);
    }

    public function testCreateUserNoUsername()
    {
        $client = static::createClient();
        $this->loginUserAdmin($client);

//        $this->createUserApi($client, [ 'password' => 'azerty' ], 400);
    }

    public function testCreateUserNoPassword()
    {
        $client = static::createClient();
        $this->loginUserAdmin($client);

//        $this->createUserApi($client, [ 'username' => 'cheesepleaseNoPassword' ], 400);
    }


}