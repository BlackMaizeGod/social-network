<?php

namespace App\Entity\ORM;

use Symfony\Component\Validator\Constraints as Assert;

class Search
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(
     *     max=20,
     *     maxMessage="try use less then 20 symbols"
     * )
     */
    private $string;

    /**
     * @return mixed
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param mixed $string
     */
    public function setString($string): void
    {
        $this->string = $string;
    }
}
