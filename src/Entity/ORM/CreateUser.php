<?php

namespace App\Entity\ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUser
{
    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Length(min="5", max="120")
     * @Assert\Email(checkHost="true", mode="html5")
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 20,
     *      minMessage = "must be at least 5 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $login;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 8,
     *      max = 20,
     *      minMessage = "must be at least 8 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "must be at least 3 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "must be at least 3 characters long",
     *      maxMessage = "cannot be longer than 20 characters"
     * )
     */
    private $surname;

    /**
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "cannot be longer than 50 characters"
     * )
     */
    private $status;

    /**
     * @var UploadedFile
     * @Assert\File(maxSize="1m")
     * @Assert\Image(allowLandscape="false", minPixels="1")
     */
    private $avatar;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return UploadedFile
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }
}
