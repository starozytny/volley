<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    public function testLogin()
    {
        $client = static::createClient();



        $client->request('GET', '/api/account', [
            'auth_bearer' => "55e21a3efcc855ae0a45c5015c17641ffdc14ff1b07d157fc6c60c5566d0ad095192437d9906e19849cb764bd73abf66ff76b355408e5ef9fa66d55e"
        ]);
        $this->assertResponseIsSuccessful();
    }
}