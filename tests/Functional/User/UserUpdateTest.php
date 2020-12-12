<?php


namespace App\Tests\Functional\User;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserUpdateTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    const URL_UPDATE = "/api/users/";

    public function testUpdateUserMissingData()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $json = '{
            "username":"henry"
        }';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 200);
    }

    public function testUpdateUserEmpty()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $json = '{}';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 200);
    }

    public function testUpdateUserWrongEmail()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $json = '{
            "username":"henry", 
            "email":"azerty"
        }';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 400);
    }

    public function testUpdateUserFilled()
    {
        $client = static::createClient();
        $user = $this->loginUser($client);

        $json = '{
            "username":"henry", 
            "email":"azerty@test.fr"
        }';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 200);
    }

    public function testUpdateUserWrongUser()
    {
        $client = static::createClient();
        $user = $this->createUser("jason", "azerty");
        $hacker = $this->createUser("hacker", "azerty");
        $this->logIn($client, $hacker->getUsername(), "azerty");

        $json = '{
            "username":"henry", 
            "email":"azerty@test.fr"
        }';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 403);
    }

    public function testUpdateUserAdminRole()
    {
        $client = static::createClient();
        $user = $this->createUser("jason", "azerty");
        $admin = $this->loginUserAdmin($client);

        $json = '{
            "username":"henry", 
            "email":"azerty@test.fr"
        }';

        $this->sendRequestJson($client, "PUT", self::URL_UPDATE . $user->getId(), $json, 200);
    }
}