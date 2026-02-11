<?php

namespace App\Tests\Entity;

use App\Entity\Matche;
use App\Entity\Phase;
use App\Entity\Equipe;
use App\Entity\ScoreFinal;
use PHPUnit\Framework\TestCase;

class MatcheTest extends TestCase
{
    public function testMatchCreation(): void
    {
        $match = new Matche();
        $match->setStatut('LIVE');
        $match->setDateHeure(new \DateTimeImmutable('2026-06-12 21:00:00'));

        $this->assertEquals('LIVE', $match->getStatut());
        $this->assertInstanceOf(\DateTimeImmutable::class, $match->getDateHeure());
    }

    public function testMatchWithTeams(): void
    {
        $match = new Matche();

        $equipeA = new Equipe();
        $equipeA->setNom('France');
        $equipeA->setCode('FRA');

        $equipeB = new Equipe();
        $equipeB->setNom('Brésil');
        $equipeB->setCode('BRA');

        $match->setEquipeA($equipeA);
        $match->setEquipeB($equipeB);

        $this->assertEquals('France', $match->getEquipeA()->getNom());
        $this->assertEquals('BRA', $match->getEquipeB()->getCode());
    }

    public function testMatchWithPhase(): void
    {
        $match = new Matche();
        $phase = new Phase();
        $phase->setNom('Finale');
        $phase->setOrdre(6);

        $match->setPhase($phase);

        $this->assertEquals('Finale', $match->getPhase()->getNom());
        $this->assertEquals(6, $match->getPhase()->getOrdre());
    }

    public function testScoreFinalRelation(): void
    {
        $match = new Matche();
        $score = new ScoreFinal();
        $score->setScoreA(2);
        $score->setScoreB(1);
        $score->setTermineLe(new \DateTimeImmutable());

        $match->setScoreFinal($score);

        $this->assertEquals(2, $match->getScoreFinal()->getScoreA());
        $this->assertEquals(1, $match->getScoreFinal()->getScoreB());
    }
}
