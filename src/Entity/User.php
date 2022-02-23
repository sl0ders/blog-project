<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    collectionOperations: ["GET", "POST"],
    itemOperations: ["GET", "PUT", "DELETE", "PATCH"],
    subresourceOperations: [
        "post_get_subresource" => [
            "path" => "/user/{id}/posts"
        ],
        "chapter_get_subresource" => [
            "path" => "/user/{id}/chapters"
        ],
        "comment_get_subresource" => [
            "path" => "/user/{id}/comments"
        ],
        "address_get_subresource" => [
            "path" => "/user/{id}/addresses"
        ],
        "notification_get_subresource" => [
            "path" => "/user/{id}/notifications"
        ]
    ],
    normalizationContext: [
        "groups" => [
            "user_read"
        ],
        "enable_max_depth" => true
    ]
)]
#[ApiFilter(
    SearchFilter::class,
    strategy: "exact",
    properties: ["firstname" => "partial", "lastname"=> "partial"]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["user_read"])]
    private $id;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["user_read", "post_read"])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(["user_read"])]
    private $roles = ["ROLE_USER"];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user_read", "comment_read", "post_read"])]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user_read", "comment_read", "post_read"])]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["user_read"])]
    private $state = "registered";

    #[ORM\OneToMany(mappedBy: 'resident', targetEntity: Address::class, orphanRemoval: true)]
    #[Groups(["user_read"])]
    #[ApiSubresource]
    private $addresses;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    #[Groups(["user_read"])]
    #[ApiSubresource]
    private $posts;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Chapter::class)]
    #[Groups(["user_read"])]
    #[ApiSubresource]
    private $chapters;

    #[ORM\OneToMany(mappedBy: 'notifier', targetEntity: PostNote::class)]
    #[Groups(["user_read"])]
    private $postNotes;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    #[Groups(["user_read"])]
    #[ApiSubresource]
    private $comments;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Notification::class, orphanRemoval: true)]
    #[Groups(["user_read"])]
    #[ApiSubresource]
    private $notifications;

    #[Groups(["user_read", "comment_read", "post_read"])]
    private $fullname;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->chapters = new ArrayCollection();
        $this->postNotes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setResident($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getResident() === $this) {
                $address->setResident(null);
            }
        }

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
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chapter[]
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $chapter->setAuthor($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getAuthor() === $this) {
                $chapter->setAuthor(null);
            }
        }

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
            $postNote->setNotifier($this);
        }

        return $this;
    }

    public function removePostNote(PostNote $postNote): self
    {
        if ($this->postNotes->removeElement($postNote)) {
            // set the owning side to null (unless already changed)
            if ($postNote->getNotifier() === $this) {
                $postNote->setNotifier(null);
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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setReceiver($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getReceiver() === $this) {
                $notification->setReceiver(null);
            }
        }

        return $this;
    }/**
 * @return mixed
 */public function getFullname()
{
    return $this->firstname . " " . $this->lastname;
}/**
 * @param mixed $fullname
 */public function setFullname($fullname): void
{
    $this->fullname = $fullname;
}
}
