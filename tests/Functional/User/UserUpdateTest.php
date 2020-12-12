<?php


namespace App\Tests\Functional\User;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserUpdateTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    const URL_UPDATE = "/api/users/";

    public function testUpdateUser()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $json = '{
            "username" => "test",
            "email" => "test@test.fr",
        }';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 200);
    }

    public function testUpdateUserMissingData()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $json = '{
            "username" => "test",
        }';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 200);
    }
}