<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $client->request('GET', '/api/account', []);
        $this->assertResponseRedirects();

        $client->request('POST', '/api/login_check', [
            'headers' => [
                'Content-type' => 'application/json'
            ],
            'json' => [
                'username' => 'shanbo',
                'password' => 'azerty'
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $client->request('GET', '/api/account', []);
        $this->assertResponseIsSuccessful();
    }
}