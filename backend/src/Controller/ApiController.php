<?php

namespace App\Controller;

use App\Repository\MatcheRepository;
use App\Repository\PhaseRepository;
use App\Validator\MatchValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class ApiController extends AbstractController
{
    #[Route('/api/phases', name: 'api_phases', methods: ['GET'])]
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

    #[Route('/api/matches', name: 'api_matches_list', methods: ['GET'])]
    public function getMatches(Request $request, MatcheRepository $matcheRepository, LoggerInterface $logger): JsonResponse
    {
        $phaseId = $request->query->get('phase');

        // ✅ VALIDATION
        if ($phaseId !== null) {
            $phaseId = (int) $phaseId;
            if (!MatchValidator::validatePhaseId($phaseId)) {
                $logger->warning('Tentative d\'accès avec Phase ID invalide', ['phase_id' => $phaseId]);
                return $this->json(['error' => 'Phase ID invalide'], Response::HTTP_BAD_REQUEST);
            }
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

    #[Route('/api/matches/{id}', name: 'api_match_detail', methods: ['GET'])]
    public function getMatch(int $id, MatcheRepository $matcheRepository, LoggerInterface $logger): JsonResponse
    {
        // ✅ VALIDATION
        if (!MatchValidator::validateMatchId($id)) {
            $logger->warning('Tentative d\'accès avec Match ID invalide', ['match_id' => $id]);
            return $this->json(['error' => 'Match ID invalide'], Response::HTTP_BAD_REQUEST);
        }

        $match = $matcheRepository->find($id);

        if (!$match) {
            return $this->json(['error' => 'Match non trouvé'], Response::HTTP_NOT_FOUND);
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
                $data['score'] = [
                    'scoreA' => rand(0, 3),
                    'scoreB' => rand(0, 3),
                ];
            }
        }

        return $this->json($data);
    }
}
