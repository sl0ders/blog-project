<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    subresourceOperations: [
        "post_get_subresource" => [
            "path" => "/comment/{id}/posts"
        ],
    ],
    normalizationContext: [
        "groups" => [
            "comment_read"
        ],
        "enable_max_depth" => true
    ]
)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["comment_read", "comments_get_subresource", "post_read"])]
    private $id;

    #[ORM\Column(type: 'text')]
    #[Groups(["comment_read", "comments_get_subresource", "post_read"])]
    private $content;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["comment_read", "comments_get_subresource", "post_read"])]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(["comment_read", "comments_get_subresource", "post_read"])]
    private $updated_at;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["comment_read", "comments_get_subresource", "post_read"])]
    private $is_enabled;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[Groups(["comment_read", "comments_get_subresource", "post_read"])]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["comment_read", "comments_get_subresource"])]
    private $post;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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
}
