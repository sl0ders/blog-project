<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    normalizationContext: [
        "groups" => [
            "picture_read"
        ]
    ]
)]
#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["post_read", "picture_read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["post_read", "picture_read"])]
    private $name;

    #[ORM\OneToOne(targetEntity: Chapter::class, cascade: ['persist', 'remove'])]
    #[Groups(["picture_read", "post_read"])]
    private $chapter;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'pictures')]
    #[Groups(["picture_read"])]
    private $post;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

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
