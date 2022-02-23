<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    normalizationContext: [
        "groups" => [
            "address_read"
        ],
        "enable_max_depth" => true
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    strategy: "exact",
    properties: ["city" => "partial", "country" => "partial"]
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["address_read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["address_read"])]
    private $street;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["address_read"])]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["address_read"])]
    private $postal_code;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["address_read"])]
    private $country;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["address_read"])]
    private $phone_number;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["address_read"])]
    private $is_enabled;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
    #[Groups(["address_read"])]
    #[ORM\JoinColumn(nullable: false)]
    private $resident;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function getResident(): ?User
    {
        return $this->resident;
    }

    public function setResident(?User $resident): self
    {
        $this->resident = $resident;

        return $this;
    }
}
