<?php

namespace App\DataFixtures;

use App\Entity\Equipe;
use App\Entity\Matche;
use App\Entity\Phase;
use App\Entity\ScoreFinal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des phases
        $phases = [
            ['nom' => 'Groupes', 'ordre' => 1],
            ['nom' => '8èmes de finale', 'ordre' => 2],
            ['nom' => 'Quarts de finale', 'ordre' => 3],
            ['nom' => 'Demi-finales', 'ordre' => 4],
            ['nom' => 'Petite finale', 'ordre' => 5],
            ['nom' => 'Finale', 'ordre' => 6],
        ];

        $phaseEntities = [];
        foreach ($phases as $phaseData) {
            $phase = new Phase();
            $phase->setNom($phaseData['nom']);
            $phase->setOrdre($phaseData['ordre']);
            $manager->persist($phase);
            $phaseEntities[] = $phase;
        }

        // Création des équipes
        $equipesData = [
            ['nom' => 'France', 'code' => 'FRA'],
            ['nom' => 'Brésil', 'code' => 'BRA'],
            ['nom' => 'Argentine', 'code' => 'ARG'],
            ['nom' => 'Allemagne', 'code' => 'GER'],
            ['nom' => 'Espagne', 'code' => 'ESP'],
            ['nom' => 'Angleterre', 'code' => 'ENG'],
            ['nom' => 'Portugal', 'code' => 'POR'],
            ['nom' => 'Belgique', 'code' => 'BEL'],
            ['nom' => 'Pays-Bas', 'code' => 'NED'],
            ['nom' => 'Italie', 'code' => 'ITA'],
            ['nom' => 'Croatie', 'code' => 'CRO'],
            ['nom' => 'Uruguay', 'code' => 'URU'],
            ['nom' => 'Mexique', 'code' => 'MEX'],
            ['nom' => 'États-Unis', 'code' => 'USA'],
            ['nom' => 'Canada', 'code' => 'CAN'],
            ['nom' => 'Japon', 'code' => 'JPN'],
        ];

        $equipes = [];
        foreach ($equipesData as $equipeData) {
            $equipe = new Equipe();
            $equipe->setNom($equipeData['nom']);
            $equipe->setCode($equipeData['code']);
            $manager->persist($equipe);
            $equipes[] = $equipe;
        }

        // Création de matchs pour la phase de groupes
        $phaseGroupes = $phaseEntities[0];

        // Match terminé
        $match1 = new Matche();
        $match1->setPhase($phaseGroupes);
        $match1->setEquipeA($equipes[0]); // France
        $match1->setEquipeB($equipes[1]); // Brésil
        $match1->setDateHeure(new \DateTimeImmutable('2026-06-12 21:00:00'));
        $match1->setStatut('FINISHED');
        $manager->persist($match1);

        // Score final pour match1
        $score1 = new ScoreFinal();
        $score1->setMatche($match1);
        $score1->setScoreA(2);
        $score1->setScoreB(1);
        $score1->setTermineLe(new \DateTimeImmutable('2026-06-12 22:50:00'));
        $manager->persist($score1);

        // Match en cours (LIVE)
        $match2 = new Matche();
        $match2->setPhase($phaseGroupes);
        $match2->setEquipeA($equipes[2]); // Argentine
        $match2->setEquipeB($equipes[3]); // Allemagne
        $match2->setDateHeure(new \DateTimeImmutable('2026-06-13 18:00:00'));
        $match2->setStatut('LIVE');
        $manager->persist($match2);

        // Match à venir (SCHEDULED)
        $match3 = new Matche();
        $match3->setPhase($phaseGroupes);
        $match3->setEquipeA($equipes[4]); // Espagne
        $match3->setEquipeB($equipes[5]); // Angleterre
        $match3->setDateHeure(new \DateTimeImmutable('2026-06-14 21:00:00'));
        $match3->setStatut('SCHEDULED');
        $manager->persist($match3);

        // Match terminé 2
        $match4 = new Matche();
        $match4->setPhase($phaseGroupes);
        $match4->setEquipeA($equipes[6]); // Portugal
        $match4->setEquipeB($equipes[7]); // Belgique
        $match4->setDateHeure(new \DateTimeImmutable('2026-06-13 15:00:00'));
        $match4->setStatut('FINISHED');
        $manager->persist($match4);

        $score4 = new ScoreFinal();
        $score4->setMatche($match4);
        $score4->setScoreA(3);
        $score4->setScoreB(3);
        $score4->setTermineLe(new \DateTimeImmutable('2026-06-13 16:50:00'));
        $manager->persist($score4);

        // Match à venir 2
        $match5 = new Matche();
        $match5->setPhase($phaseGroupes);
        $match5->setEquipeA($equipes[8]); // Pays-Bas
        $match5->setEquipeB($equipes[9]); // Italie
        $match5->setDateHeure(new \DateTimeImmutable('2026-06-15 18:00:00'));
        $match5->setStatut('SCHEDULED');
        $manager->persist($match5);

        // Quelques matchs pour les 8èmes de finale
        $phase8emes = $phaseEntities[1];

        $match6 = new Matche();
        $match6->setPhase($phase8emes);
        $match6->setEquipeA($equipes[10]); // Croatie
        $match6->setEquipeB($equipes[11]); // Uruguay
        $match6->setDateHeure(new \DateTimeImmutable('2026-06-28 21:00:00'));
        $match6->setStatut('SCHEDULED');
        $manager->persist($match6);

        $match7 = new Matche();
        $match7->setPhase($phase8emes);
        $match7->setEquipeA($equipes[12]); // Mexique
        $match7->setEquipeB($equipes[13]); // États-Unis
        $match7->setDateHeure(new \DateTimeImmutable('2026-06-29 18:00:00'));
        $match7->setStatut('SCHEDULED');
        $manager->persist($match7);

        // Match pour la finale
        $phaseFinale = $phaseEntities[5];

        $match8 = new Matche();
        $match8->setPhase($phaseFinale);
        $match8->setEquipeA($equipes[14]); // Canada
        $match8->setEquipeB($equipes[15]); // Japon
        $match8->setDateHeure(new \DateTimeImmutable('2026-07-19 20:00:00'));
        $match8->setStatut('SCHEDULED');
        $manager->persist($match8);

        $manager->flush();
    }
}
