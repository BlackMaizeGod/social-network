<?php

namespace App\Entity\ORM;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class Verification
{
    /**
     * @var ArrayCollection
     * @Assert\NotNull()
     */
    private $user;

    /**
     * @var bool
     * @Assert\NotNull()
     */
    private $choice;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getChoice(): ?bool
    {
        return $this->choice;
    }

    public function setChoice(bool $choice)
    {
        return $this->choice = $choice;
    }
}
