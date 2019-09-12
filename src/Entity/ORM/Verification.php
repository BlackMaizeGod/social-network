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
    private $users;

    /**
     * @var bool
     * @Assert\NotNull()
     */
    private $choice;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(?User $user): void
    {
        $this->users = $user;
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
