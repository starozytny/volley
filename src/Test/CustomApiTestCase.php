<?php


namespace App\Test;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class CustomApiTestCase extends WebTestCase
{
    protected function getEntityManager()
    {
        return self::$container->get('doctrine')->getManager();
    }

    protected function createUser(string $username, string $password="azerty"): User
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

    protected function logIn($client, string $username, string $password="azerty")
    {
        $client->request('POST', '/api/login_check', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            sprintf('{"username":"%s", "password":"%s"}', $username, $password)
        );
        $this->assertResponseStatusCodeSame(200);
    }

    protected function createUserAndLogIn($client, string $username, string $password="azerty"): User
    {
        $user = $this->createUser($username, $password);
        $this->logIn($client, $username, $password);
        return $user;
    }

    protected function createUserAdminAndLogIn($client, string $username, string $password="azerty"): User
    {
        $user = $this->createUser($username, $password);
        $this->setToAdmin($user);

        $this->logIn($client, $username, $password);
        return $user;
    }

    protected function setToRole(User $user, array $role): User
    {
        $user->setRoles($role);

        $em = $this->getEntityManager();
        $em->flush();

        return $user;
    }

    protected function setToDev(User $user): User
    {
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_DEVELOPER']);

        $em = $this->getEntityManager();
        $em->flush();

        return $user;
    }

    protected function setToAdmin(User $user): User
    {
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

        $em = $this->getEntityManager();
        $em->flush();

        return $user;
    }

    protected function loginUser($client): User
    {
        return $this->createUserAndLogIn($client, "shanbo", "azerty");
    }

    protected function loginUserAdmin($client): User
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

//    protected function initData($client)
//    {
//        self::runCommand('cite:tr:da 2020', $client);
//    }

    protected static function runCommand($command, $client): int
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication($client)->run(new StringInput($command));
    }

    protected static function getApplication($client): Application
    {
        if (null === self::$application) {
            self::bootKernel();
            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }
}