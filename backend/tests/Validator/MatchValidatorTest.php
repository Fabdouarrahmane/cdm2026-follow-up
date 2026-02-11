<?php

namespace App\Tests\Validator;

use App\Validator\MatchValidator;
use PHPUnit\Framework\TestCase;

class MatchValidatorTest extends TestCase
{
    public function testValidMatchId(): void
    {
        $this->assertTrue(MatchValidator::validateMatchId(1));
        $this->assertTrue(MatchValidator::validateMatchId(100));
        $this->assertTrue(MatchValidator::validateMatchId(999999));
    }

    public function testInvalidMatchId(): void
    {
        $this->assertFalse(MatchValidator::validateMatchId(0));
        $this->assertFalse(MatchValidator::validateMatchId(-1));
        $this->assertFalse(MatchValidator::validateMatchId(1000000));
    }

    public function testValidPhaseId(): void
    {
        $this->assertTrue(MatchValidator::validatePhaseId(1));
        $this->assertTrue(MatchValidator::validatePhaseId(6));
        $this->assertTrue(MatchValidator::validatePhaseId(99));
    }

    public function testInvalidPhaseId(): void
    {
        $this->assertFalse(MatchValidator::validatePhaseId(0));
        $this->assertFalse(MatchValidator::validatePhaseId(-1));
        $this->assertFalse(MatchValidator::validatePhaseId(100));
    }

    public function testValidStatut(): void
    {
        $this->assertTrue(MatchValidator::validateStatut('SCHEDULED'));
        $this->assertTrue(MatchValidator::validateStatut('LIVE'));
        $this->assertTrue(MatchValidator::validateStatut('FINISHED'));
    }

    public function testInvalidStatut(): void
    {
        $this->assertFalse(MatchValidator::validateStatut('PENDING'));
        $this->assertFalse(MatchValidator::validateStatut(''));
        $this->assertFalse(MatchValidator::validateStatut('finished'));
        $this->assertFalse(MatchValidator::validateStatut('live'));
    }
}
