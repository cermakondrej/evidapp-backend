<?php


namespace EvidApp\Security\Application\Request\Login;


class LoginRequest
{

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $plainPassword;

    public function __construct(string $email, string $plainPassword)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }



}