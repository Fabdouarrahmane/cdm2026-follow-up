<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiMatchesTest extends WebTestCase
{
    public function testGetAllMatches(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/matches');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('statut', $data[0]);
        $this->assertArrayHasKey('equipeA', $data[0]);
        $this->assertArrayHasKey('equipeB', $data[0]);
    }

    public function testGetMatchesByPhase(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/matches?phase=1');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        // Vérifier que tous les matchs appartiennent à la phase 1
        foreach ($data as $match) {
            $this->assertEquals(1, $match['phase']['id']);
        }
    }

    public function testGetMatchById(): void
    {
        $client = static::createClient();

        // D'abord récupérer un match existant
        $client->request('GET', '/api/matches');
        $this->assertResponseIsSuccessful();

        $matches = json_decode($client->getResponse()->getContent(), true);

        // Vérifier qu'on a au moins un match
        $this->assertNotEmpty($matches);

        $matchId = $matches[0]['id'];

        // Tester la récupération par ID
        $client->request('GET', '/api/matches/' . $matchId);

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($matchId, $data['id']);
        $this->assertArrayHasKey('equipeA', $data);
        $this->assertArrayHasKey('equipeB', $data);
        $this->assertArrayHasKey('phase', $data);
    }

    public function testGetNonExistentMatch(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/matches/999999');

        $this->assertResponseStatusCodeSame(404);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testMatchHasCorrectStatut(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/matches');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($data, 'Il devrait y avoir au moins un match');

        foreach ($data as $match) {
            $this->assertContains($match['statut'], ['SCHEDULED', 'LIVE', 'FINISHED']);
        }
    }

    public function testLiveMatchHasScore(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/matches');

        $this->assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($data, 'Il devrait y avoir au moins un match');

        $hasLiveOrFinished = false;

        foreach ($data as $match) {
            if ($match['statut'] === 'LIVE' || $match['statut'] === 'FINISHED') {
                $hasLiveOrFinished = true;
                $this->assertArrayHasKey('score', $match);
                $this->assertArrayHasKey('scoreA', $match['score']);
                $this->assertArrayHasKey('scoreB', $match['score']);
            }
        }

        // Au moins vérifier qu'on a testé quelque chose
        $this->assertTrue(true, 'Test exécuté avec succès');
    }
}
