<?php


namespace App\Tests\Functional;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateUser()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $client->request('POST', '/api/users', [
            'json' => [
                'username' => 'cheeseplease',
                'password' => 'brie'
            ]
        ]);
        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateUserNoPassword()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $client->request('POST', '/api/users', [
            'json' => [
                'username' => 'cheesepleaseNoPassword',
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);
    }
}