<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\PreFlushEventArgs;

class SlugUser
{
    public function preFlush(User $user, PreFlushEventArgs $args)
    {
        $slug = strtolower($user->getName().'-'.$user->getSurname().'-'.$user->getId());
        $slug = preg_replace('~[^\pL\d]+~u', '-', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $user->setSlug($slug);
    }
}
