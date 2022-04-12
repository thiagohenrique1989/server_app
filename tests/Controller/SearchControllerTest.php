<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class SearchControllerTest
 * @package App\Tests\Controller
 */
class SearchControllerTest extends WebTestCase
{
    public function testResponseFormat(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search/index');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }
}
