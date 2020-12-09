<?php


namespace App\Tests\Functional\User;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserGetTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testGetUsersInfoRoleUser()
    {
        $client = static::createClient();
        $this->loginUser($client);

        $client->request('GET', '/api/users/');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testGetUsersInfoRoleAdmin()
    {
        $client = static::createClient();
        $this->loginUserADmin($client);

        $client->request('GET', '/api/users/');
        $this->assertResponseStatusCodeSame(200);
    }
}