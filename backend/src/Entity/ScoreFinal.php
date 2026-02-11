<?php

namespace App\Entity;

use App\Repository\ScoreFinalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreFinalRepository::class)]
class ScoreFinal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $scoreA = null;

    #[ORM\Column]
    private ?int $scoreB = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $termineLe = null;

    #[ORM\OneToOne(inversedBy: 'scoreFinal', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Matche $matche = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoreA(): ?int
    {
        return $this->scoreA;
    }

    public function setScoreA(int $scoreA): static
    {
        $this->scoreA = $scoreA;

        return $this;
    }

    public function getScoreB(): ?int
    {
        return $this->scoreB;
    }

    public function setScoreB(int $scoreB): static
    {
        $this->scoreB = $scoreB;

        return $this;
    }

    public function getTermineLe(): ?\DateTimeImmutable
    {
        return $this->termineLe;
    }

    public function setTermineLe(\DateTimeImmutable $termineLe): static
    {
        $this->termineLe = $termineLe;

        return $this;
    }

    public function getMatche(): ?Matche
    {
        return $this->matche;
    }

    public function setMatche(Matche $matche): static
    {
        $this->matche = $matche;

        return $this;
    }
}
