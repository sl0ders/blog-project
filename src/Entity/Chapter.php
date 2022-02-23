<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChapterRepository::class)]
#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    subresourceOperations: [
        "post_get_subresource" => [
            "path" => "/chapter/{id}/posts"
        ],
    ], normalizationContext: [
    "groups" => [
        "chapter_read"
    ]
],
)]
class Chapter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["chapter_read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["chapter_read", "post_read"])]
    private $name;

    #[ORM\Column(type: 'integer')]
    #[Groups(["chapter_read", "post_read"])]
    private $number;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["chapter_read", "post_read"])]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(["chapter_read", "post_read"])]
    private $updated_at;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["chapter_read", "post_read"])]
    private $is_enabled;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chapters')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["chapter_read", "post_read"])]
    private $author;

    #[ORM\OneToMany(mappedBy: 'chapter', targetEntity: Post::class, orphanRemoval: true)]
    #[ApiSubresource]
    #[Groups(["chapter_read"])]
    private $posts;

    #[ORM\OneToOne(targetEntity: Picture::class)]
    #[Groups(["chapter_read", "post_read"])]
    private $picture;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

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

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setChapter($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getChapter() === $this) {
                $post->setChapter(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture): void
    {
        $this->picture = $picture;
    }
}
