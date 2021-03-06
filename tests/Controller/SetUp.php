<?php
/**
 * Created by PhpStorm.
 * User: julienbutty
 * Date: 15/07/2018
 * Time: 11:12.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class SetUp extends WebTestCase
{
    protected $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function logIn($username = 'user')
    {
        if ('admin' == $username) {
            $user = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('App:User')->findOneByUsername('admin');
            $role = $user->getRoles();
            $session = $this->client->getContainer()->get('session');

            $firewallName = 'main';

            $token = new UsernamePasswordToken($user, null, $firewallName, $role);
            $session->set('_security_'.$firewallName, serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $this->client->getCookieJar()->set($cookie);
        } else {
            $user = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository('App:User')->findOneByUsername('user');
            $role = $user->getRoles();
            $session = $this->client->getContainer()->get('session');

            $firewallName = 'main';

            $token = new UsernamePasswordToken($user, null, $firewallName, $role);
            $session->set('_security_'.$firewallName, serialize($token));
            $session->save();

            $cookie = new Cookie($session->getName(), $session->getId());
            $this->client->getCookieJar()->set($cookie);
        }
    }
}
