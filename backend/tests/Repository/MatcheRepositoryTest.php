<?php

namespace App\Tests\Repository;

use App\Entity\Matche;
use App\Repository\MatcheRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MatcheRepositoryTest extends KernelTestCase
{
    private MatcheRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = static::getContainer()->get(MatcheRepository::class);
    }

    public function testFindAll(): void
    {
        $matches = $this->repository->findAll();

        $this->assertIsArray($matches);
        $this->assertNotEmpty($matches);
        $this->assertInstanceOf(Matche::class, $matches[0]);
    }

    public function testFindByPhase(): void
    {
        $matches = $this->repository->findBy(['phase' => 1]);

        // Toujours faire une assertion même si le tableau est vide
        $this->assertIsArray($matches);

        foreach ($matches as $match) {
            $this->assertEquals(1, $match->getPhase()->getId());
        }

        // Si on a des matchs, au moins un doit avoir la phase 1
        if (count($matches) > 0) {
            $this->assertEquals(1, $matches[0]->getPhase()->getId());
        }
    }

    public function testFindByStatut(): void
    {
        $liveMatches = $this->repository->findBy(['statut' => 'LIVE']);

        // Toujours faire une assertion
        $this->assertIsArray($liveMatches);

        foreach ($liveMatches as $match) {
            $this->assertEquals('LIVE', $match->getStatut());
        }
    }
}
