<?php


namespace App\Test;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;

class CustomApiTestCase extends ApiTestCase
{
    protected function getEntityManager()
    {
        return self::$container->get('doctrine')->getManager();
    }

    protected function createUser(string $username, string $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail(sprintf("%s@outlook.fr", $username));
        $encoded = self::$container->get('security.password_encoder')
            ->encodePassword($user, $password);
        $user->setPassword($encoded);

        $em = $this->getEntityManager();
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

    protected function createUserAdminAndLogIn(Client $client, string $username, string $password): User
    {
        $user = $this->createUser($username, $password);
        $this->setToAdmin($user);

        $this->logIn($client, $username, $password);
        return $user;
    }

    protected function setToAdmin(User $user)
    {
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    protected function loginUser(Client $client)
    {
        return $this->createUserAndLogIn($client, "shanbo", "azerty");
    }

    protected function loginUserAdmin(Client $client)
    {
        return $this->createUserAdminAndLogIn($client, "shanbo", "azerty");
    }
}