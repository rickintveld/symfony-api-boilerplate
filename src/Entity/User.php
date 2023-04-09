<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Repository\UserRepository;
use App\Entity\LifeCycleTrait;
use App\Model\Identifier;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(types: ['https://schema.org/User'], operations: [
    new GetCollection(name: 'app_users', uriTemplate: '/user/all', outputFormats: ['json']),
    new Get(name: 'app_user', uriTemplate: '/user/{id}', outputFormats: ['json']),
    new Post(name: 'app_user_create', uriTemplate: '/user/create', inputFormats: ['json'], outputFormats: ['json']),
    new Patch(name: 'app_user_enable', uriTemplate: '/user/enable', input: Identifier::class, inputFormats: ['json'], outputFormats: ['json']),
    new Patch(name: 'app_user_disable', uriTemplate: '/user/disable', input: Identifier::class, inputFormats: ['json'], outputFormats: ['json']),
    new Delete(name: 'app_user_remove', uriTemplate: '/user/remove/{id}'),
])]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    use LifeCycleTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 100)]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
