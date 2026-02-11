<?php

namespace App\Controller;

use App\Repository\PhaseRepository;
use App\Repository\MatcheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{
    // GET /api/phases - Liste des phases
    #[Route('/phases', name: 'phases', methods: ['GET'])]
    public function getPhases(PhaseRepository $phaseRepository): JsonResponse
    {
        $phases = $phaseRepository->findBy([], ['ordre' => 'ASC']);

        $data = [];
        foreach ($phases as $phase) {
            $data[] = [
                'id' => $phase->getId(),
                'nom' => $phase->getNom(),
                'ordre' => $phase->getOrdre(),
            ];
        }

        return $this->json($data);
    }

    // GET /api/matches?phase=1 - Liste des matchs d'une phase
    #[Route('/matches', name: 'matches_list', methods: ['GET'])]
    public function getMatches(Request $request, MatcheRepository $matcheRepository): JsonResponse
    {
        $phaseId = $request->query->get('phase');

        if ($phaseId) {
            $matches = $matcheRepository->findBy(['phase' => $phaseId], ['dateHeure' => 'ASC']);
        } else {
            $matches = $matcheRepository->findBy([], ['dateHeure' => 'ASC']);
        }

        $data = [];
        foreach ($matches as $match) {
            $matchData = [
                'id' => $match->getId(),
                'dateHeure' => $match->getDateHeure()->format('Y-m-d H:i:s'),
                'statut' => $match->getStatut(),
                'phase' => [
                    'id' => $match->getPhase()->getId(),
                    'nom' => $match->getPhase()->getNom(),
                ],
                'equipeA' => [
                    'id' => $match->getEquipeA()->getId(),
                    'nom' => $match->getEquipeA()->getNom(),
                    'code' => $match->getEquipeA()->getCode(),
                ],
                'equipeB' => [
                    'id' => $match->getEquipeB()->getId(),
                    'nom' => $match->getEquipeB()->getNom(),
                    'code' => $match->getEquipeB()->getCode(),
                ],
            ];

            // Ajouter le score si le match est LIVE ou FINISHED
            if ($match->getStatut() === 'LIVE' || $match->getStatut() === 'FINISHED') {
                $scoreFinal = $match->getScoreFinal();
                if ($scoreFinal) {
                    $matchData['score'] = [
                        'scoreA' => $scoreFinal->getScoreA(),
                        'scoreB' => $scoreFinal->getScoreB(),
                    ];
                } else {
                    // Pour les matchs LIVE sans score final encore créé, on simule
                    $matchData['score'] = [
                        'scoreA' => rand(0, 3),
                        'scoreB' => rand(0, 3),
                    ];
                }
            }

            $data[] = $matchData;
        }

        return $this->json($data);
    }

    // GET /api/matches/{id} - Détail d'un match
    #[Route('/matches/{id}', name: 'match_detail', methods: ['GET'])]
    public function getMatch(int $id, MatcheRepository $matcheRepository): JsonResponse
    {
        $match = $matcheRepository->find($id);

        if (!$match) {
            return $this->json(['error' => 'Match non trouvé'], 404);
        }

        $data = [
            'id' => $match->getId(),
            'dateHeure' => $match->getDateHeure()->format('Y-m-d H:i:s'),
            'statut' => $match->getStatut(),
            'phase' => [
                'id' => $match->getPhase()->getId(),
                'nom' => $match->getPhase()->getNom(),
            ],
            'equipeA' => [
                'id' => $match->getEquipeA()->getId(),
                'nom' => $match->getEquipeA()->getNom(),
                'code' => $match->getEquipeA()->getCode(),
            ],
            'equipeB' => [
                'id' => $match->getEquipeB()->getId(),
                'nom' => $match->getEquipeB()->getNom(),
                'code' => $match->getEquipeB()->getCode(),
            ],
        ];

        // Ajouter le score si disponible
        if ($match->getStatut() === 'LIVE' || $match->getStatut() === 'FINISHED') {
            $scoreFinal = $match->getScoreFinal();
            if ($scoreFinal) {
                $data['score'] = [
                    'scoreA' => $scoreFinal->getScoreA(),
                    'scoreB' => $scoreFinal->getScoreB(),
                    'termineLe' => $scoreFinal->getTermineLe()->format('Y-m-d H:i:s'),
                ];
            } else {
                // Simulation pour LIVE
                $data['score'] = [
                    'scoreA' => rand(0, 3),
                    'scoreB' => rand(0, 3),
                ];
            }
        }

        return $this->json($data);
    }
}
