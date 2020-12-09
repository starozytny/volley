<?php


namespace App\Tests\Functional\User;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserGetTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testGetUsersInfo()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $client->request('GET', 'api/users');
        $this->assertResponseIsSuccessful();
    }

    public function testGetUserInfoRoleUser()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $data = $client->request('GET', 'api/users/'.$user->getId());
        $data = $data->toArray();
        $this->assertArrayNotHasKey('roles', $data);
    }

    public function testGetUserInfoRoleAdmin()
    {
        $client = static::createClient();
        $user = $this->loginUserAdmin($client);

        $data = $client->request('GET', 'api/users/'.$user->getId());
        $data = $data->toArray();
        $this->assertArrayHasKey('roles', $data);
    }
}