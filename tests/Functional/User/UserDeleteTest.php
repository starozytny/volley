<?php


namespace App\Tests\Functional\User;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserDeleteTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    const URL_DELETE = "/api/users/";

    public function testDeleteUserWrongRole()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $this->sendRequestJson($client, "DELETE", self::URL_DELETE . $user->getId(), null, 403);
    }

    public function testDeleteUserMe()
    {
        $client = static::createClient();
        $user = $this->loginUserAdmin($client);

        $this->sendRequestJson($client, "DELETE", self::URL_DELETE . $user->getId(), null, 400);
    }

    public function testDeleteUserSomebody()
    {
        $client = static::createClient();
        $toDelete = $this->createUser('toDelete', 'azerty');
        $this->loginUserAdmin($client);

        $this->sendRequestJson($client, "DELETE", self::URL_DELETE . $toDelete->getId(), null, 200);
    }
}