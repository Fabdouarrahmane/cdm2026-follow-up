<?php

namespace App\Validator;

class MatchValidator
{
    public static function validateMatchId(int $id): bool
    {
        return $id > 0 && $id < 1000000;
    }

    public static function validatePhaseId(int $id): bool
    {
        return $id > 0 && $id < 100;
    }

    public static function validateStatut(string $statut): bool
    {
        return in_array($statut, ['SCHEDULED', 'LIVE', 'FINISHED']);
    }
}
