<?php


namespace App\Test;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;

class CustomApiTestCase extends ApiTestCase
{
    protected function createUser(string $username, string $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail(sprintf("%s@outlook.fr", $username));
        $encoded = self::$container->get('security.password_encoder')
            ->encodePassword($user, $password);
        $user->setPassword($encoded);

        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    protected function logIn(Client $client, string $username, string $password)
    {
        $client->request('POST', '/api/login_check', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => $username,
                'password' => $password
            ],
        ]);
        $this->assertResponseStatusCodeSame(200);
    }

    protected function createUserAndLogIn(Client $client, string $username, string $password): User
    {
        $user = $this->createUser($username, $password);
        $this->logIn($client, $username, $password);
        return $user;
    }

    protected function loginUser(Client $client)
    {
        $this->createUserAndLogIn($client, "shanbo", "azerty");
    }
}