<?php

namespace AppBundle\Tests\Controller;

use AppBundle\DataFixtures\ORM\LoadRole;
use AppBundle\Entity\Role;
use Nines\UserBundle\DataFixtures\ORM\LoadUser;
use Nines\UtilBundle\Tests\Util\BaseTestCase;

class RoleControllerTest extends BaseTestCase {
    protected function getFixtures() {
        return array(
            LoadUser::class,
            LoadRole::class,
        );
    }

    public function testAnonIndex() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/role/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }

    public function testUserIndex() {
        $client = $this->makeClient(array(
            'username' => 'user@example.com',
            'password' => 'secret',
        ));
        $crawler = $client->request('GET', '/role/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('New')->count());
    }

    public function testAdminIndex() {
        $client = $this->makeClient(array(
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ));
        $crawler = $client->request('GET', '/role/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('New')->count());
    }

    public function testAnonShow() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/role/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }

    public function testUserShow() {
        $client = $this->makeClient(array(
            'username' => 'user@example.com',
            'password' => 'secret',
        ));
        $crawler = $client->request('GET', '/role/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->selectLink('Edit')->count());
        $this->assertEquals(0, $crawler->selectLink('Delete')->count());
    }

    public function testAdminShow() {
        $client = $this->makeClient(array(
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ));
        $crawler = $client->request('GET', '/role/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->selectLink('Edit')->count());
        $this->assertEquals(1, $crawler->selectLink('Delete')->count());
    }

    public function testAnonEdit() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/role/1/edit');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testUserEdit() {
        $client = $this->makeClient(array(
            'username' => 'user@example.com',
            'password' => 'secret',
        ));
        $crawler = $client->request('GET', '/role/1/edit');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminEdit() {
        $client = $this->makeClient(array(
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ));
        $formCrawler = $client->request('GET', '/role/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Update')->form(array(
            'role[label]' => 'Cheese',
            'role[description]' => 'It is a cheese',
        ));

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect('/role/1'));
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Cheese")')->count());
    }

    public function testAnonNew() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/role/new');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testUserNew() {
        $client = $this->makeClient(array(
            'username' => 'user@example.com',
            'password' => 'secret',
        ));
        $crawler = $client->request('GET', '/role/new');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminNew() {
        $client = $this->makeClient(array(
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ));
        $formCrawler = $client->request('GET', '/role/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $formCrawler->selectButton('Create')->form(array(
            'role[label]' => 'Cheese',
            'role[description]' => 'It is a cheese',
        ));

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $responseCrawler->filter('td:contains("Cheese")')->count());
    }

    public function testAnonDelete() {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/role/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

    public function testUserDelete() {
        $client = $this->makeClient(array(
            'username' => 'user@example.com',
            'password' => 'secret',
        ));
        $crawler = $client->request('GET', '/role/1/delete');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAdminDelete() {
        self::bootKernel();
        $em = static::$kernel->getContainer()->get('doctrine')->getManager();
        $preCount = count($em->getRepository(Role::class)->findAll());
        $client = $this->makeClient(array(
            'username' => 'admin@example.com',
            'password' => 'supersecret',
        ));
        $crawler = $client->request('GET', '/role/1/delete');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $responseCrawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $em->clear();
        $postCount = count($em->getRepository(Role::class)->findAll());
        $this->assertEquals($preCount - 1, $postCount);
    }
}
