<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostNoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PostNoteRepository::class)]
#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    subresourceOperations: [
        "post_get_subresource" => [
            "path" => "/note/{id}/posts"
        ],
        "notifier_get_subresource" => [
            "path" => "/note/{id}/notifiers"
        ]
    ],
    normalizationContext: [
        "groups" => [
            "note_read"
        ],
        "enable_max_depth" => true
    ]
)]
class PostNote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["note_read"])]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(["note_read"])]
    private $note;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["note_read"])]
    private $created_at;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'postNotes')]
    #[Groups(["note_read"])]
    #[ORM\JoinColumn(nullable: false)]
    private $post;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'postNotes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["note_read"])]
    private $notifier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getNotifier(): ?User
    {
        return $this->notifier;
    }

    public function setNotifier(?User $notifier): self
    {
        $this->notifier = $notifier;

        return $this;
    }
}
