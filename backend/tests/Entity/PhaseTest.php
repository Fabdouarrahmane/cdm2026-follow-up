<?php

namespace App\Tests\Entity;

use App\Entity\Phase;
use App\Entity\Matche;
use PHPUnit\Framework\TestCase;

class PhaseTest extends TestCase
{
    public function testPhaseCreation(): void
    {
        $phase = new Phase();
        $phase->setNom('Groupes');
        $phase->setOrdre(1);

        $this->assertEquals('Groupes', $phase->getNom());
        $this->assertEquals(1, $phase->getOrdre());
    }

    public function testAddMatch(): void
    {
        $phase = new Phase();
        $match = new Matche();

        $phase->addMatch($match);

        $this->assertCount(1, $phase->getMatches());
        $this->assertEquals($phase, $match->getPhase());
    }

    public function testRemoveMatch(): void
    {
        $phase = new Phase();
        $match = new Matche();

        $phase->addMatch($match);
        $this->assertCount(1, $phase->getMatches());

        $phase->removeMatch($match);
        $this->assertCount(0, $phase->getMatches());
    }
}
