<?php


namespace App\Tests\Functional;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateUser()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $this->createUserApi($client, [
            'username' => 'cheeseplease',
            'email' => 'cheseplease@outlook.fr',
            'password' => 'brie'
        ], 201);
    }

    public function testCreateUserEmpty()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $this->createUserApi($client, [], 400);
    }

    public function testCreateUserNoUsername()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $this->createUserApi($client, [ 'password' => 'azerty' ], 400);
    }

    public function testCreateUserNoPassword()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $this->createUserApi($client, [ 'username' => 'cheesepleaseNoPassword', ], 400);
    }

    public function testCreateUserNoWriteableProperty()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $this->createUserApi($client, [ 'roles' => 'ROLE_USER', ], 400);
    }

    protected function createUserApi(Client $client, Array $json, $codeReturn)
    {
        $client->request('POST', '/api/users', [ 'json' => $json ]);
        $this->assertResponseStatusCodeSame($codeReturn);
    }
}