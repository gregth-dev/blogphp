<?php

namespace App\Model;

use App\Helpers\Text;

class User
{

    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    private $id;

    /**
     * Get the value of username
     *
     * @return  string
     */ 
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param  string  $username
     *
     * @return  self
     */ 
    public function setUsername(string $username): self
    {
        $this->username = Text::secure($username);

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */ 
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     *
     * @return  self
     */ 
    public function setPassword(string $password): self
    {
        $this->password = Text::secure($password);

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
