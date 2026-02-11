<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiPhasesTest extends WebTestCase
{
    public function testGetPhases(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/phases');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('nom', $data[0]);
        $this->assertArrayHasKey('ordre', $data[0]);
    }

    public function testPhasesOrderedByOrdre(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/phases');

        // Vérifier que la réponse est OK
        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        // Vérifier qu'on a des données
        $this->assertNotNull($data);
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        // Vérifier que les phases sont triées par ordre
        $ordres = array_column($data, 'ordre');
        $ordresSorted = $ordres;
        sort($ordresSorted);

        $this->assertEquals($ordresSorted, $ordres);
    }
}
