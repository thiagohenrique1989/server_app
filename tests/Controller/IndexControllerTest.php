<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class IndexControllerTest
 * @package App\Tests\Controller
 */
class IndexControllerTest extends WebTestCase
{
    public function testPageTitle(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Server List');
    }
}
