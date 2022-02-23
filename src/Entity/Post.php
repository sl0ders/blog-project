<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[
    ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    subresourceOperations: [
        "comments_get_subresource" => [
            "path" => "/post/{id}/comments"
        ]
    ],
    normalizationContext: [
        "groups" => [
            "post_read"
        ]
    ]
)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["post_read", "chapter_read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["post_read", "chapter_read"])]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups(["post_read", "chapter_read"])]
    private $content;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(["post_read", "chapter_read"])]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(["post_read", "chapter_read"])]
    private $updated_at;

    #[ORM\Column(type: 'boolean')]
    #[Groups(["post_read", "chapter_read"])]
    private $is_enabled;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["post_read", "chapter_read"])]
    private $author;

    #[ORM\ManyToOne(targetEntity: Chapter::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[ApiSubresource]
    #[Groups(["post_read"])]
    private $chapter;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: PostNote::class, orphanRemoval: true)]
    #[Groups(["post_read", "chapter_read"])]
    #[ApiSubresource]
    private $postNotes;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, orphanRemoval: true)]
    #[ApiSubresource]
    #[Groups(["post_read", "chapter_read"])]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Picture::class, cascade: ["persist", "remove"])]
    #[Groups(["post_read", "chapter_read"])]
    private $pictures;

    public function __construct()
    {
        $this->postNotes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    /**
     * @return Collection|PostNote[]
     */
    public function getPostNotes(): Collection
    {
        return $this->postNotes;
    }

    public function addPostNote(PostNote $postNote): self
    {
        if (!$this->postNotes->contains($postNote)) {
            $this->postNotes[] = $postNote;
            $postNote->setPost($this);
        }

        return $this;
    }

    public function removePostNote(PostNote $postNote): self
    {
        if ($this->postNotes->removeElement($postNote)) {
            // set the owning side to null (unless already changed)
            if ($postNote->getPost() === $this) {
                $postNote->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setPost($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPost() === $this) {
                $picture->setPost(null);
            }
        }

        return $this;
    }
}
