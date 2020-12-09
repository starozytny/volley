<?php


namespace App\Tests\Functional\User;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserUpdateTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testUpdateUser()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $client->request('PUT', 'api/users/'.$user->getId(), [
            'json' => [
                'username' => "test"
            ]
        ]);
        $this->assertResponseIsSuccessful();
    }
}