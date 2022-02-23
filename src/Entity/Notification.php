<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    normalizationContext: [
        "groups" => [
            "notification_read"
        ],
        "enable_max_depth" => true
    ]
)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["notification_read"])]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Groups(["notification_read"])]
    private $content;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["notification_read"])]
    private $created_at;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["notification_read"])]
    private $is_read;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(["notification_read"])]
    private $path;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(["notification_read"])]
    private $id_path;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["notification_read"])]
    private $receiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getIdPath(): ?int
    {
        return $this->id_path;
    }

    public function setIdPath(?int $id_path): self
    {
        $this->id_path = $id_path;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }
}
