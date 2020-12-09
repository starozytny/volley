<?php


namespace App\Test;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomApiTestCase extends WebTestCase
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

    protected function logIn($client, string $username, string $password)
    {
        $client->request('POST', '/api/login_check', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            sprintf('{"username":"%s", "password":"%s"}', $username, $password)
        );
        $this->assertResponseStatusCodeSame(200);
    }

    protected function createUserAndLogIn($client, string $username, string $password): User
    {
        $user = $this->createUser($username, $password);
        $this->logIn($client, $username, $password);
        return $user;
    }

    protected function createUserAdminAndLogIn($client, string $username, string $password): User
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
        $em->flush();

        return $user;
    }

    protected function loginUser($client)
    {
        return $this->createUserAndLogIn($client, "shanbo", "azerty");
    }

    protected function loginUserAdmin($client)
    {
        return $this->createUserAdminAndLogIn($client, "shanbo", "azerty");
    }

    protected function sendRequestJson($client, $method, $url, $json, $codeExpected)
    {
        $client->request($method, $url, [], [],
            ['CONTENT_TYPE' => 'application/json'],
            $json
        );
        $this->assertResponseStatusCodeSame($codeExpected);
    }
}