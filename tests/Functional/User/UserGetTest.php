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

    public function testGetUserInfo()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $otherUser = $this->createUser('space', 'space');

        $client->request('GET', 'api/users/'.$user->getId());
        $client->request('GET', 'api/users/'.$otherUser->getId());

        $this->assertResponseIsSuccessful();
    }
}