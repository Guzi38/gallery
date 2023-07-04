<?php
/**
 * Photo entity.
 */

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Photo.
 */
#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ORM\Table(name: 'photos')]
class Photo
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 120)]
    #[Assert\NotBlank]
    private ?string $title = null;

    /**
     * Content.
     */
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $content = null;

    /**
     * Filename.
     */
    #[ORM\Column(type: 'string', length: 191, nullable: true)]
    #[Assert\Type('string')]
    private ?string $filename;

    /**
     * Gallery.
     *
     * @var Gallery
     **/
    #[ORM\ManyToOne(targetEntity: Gallery::class)]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gallery $gallery;

    /**
     * Tags.
     *
     * @var ArrayCollection<int, Tag>
     */
    #[Assert\Valid]
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'photos')]
    #[ORM\JoinTable(name: 'photos_tags')]
    private $tags;

    /**
     * Comments.
     *
     * @var ArrayCollection
     */
    #[ORM\OneToMany(mappedBy: 'photo', targetEntity: Comment::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     *
     * @ORM\JoinColumn(nullable=false)
     */
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Add tag.
     *
     * @param Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag.
     *
     * @param Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Add comment.
     *
     * @param Comment $comment Comment
     *
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPhoto($this);
        }

        return $this;
    }

    /**
     * Remove comment.
     *
     * @param Comment $comment Comment
     *
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPhoto() === $this) {
                $comment->setPhoto(null);
            }
        }

        return $this;
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for date.
     *
     * @return \DateTimeImmutable|null Date
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * Setter for date.
     *
     * @param \DateTimeImmutable|null $date Date
     */
    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    /**
     * Getter for content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string|null $content Content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * Getter for Title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for Title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for gallery.
     *
     * @return Gallery|null Gallery
     */
    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    /**
     * Setter for gallery.
     *
     * @param Gallery|null $gallery Gallery
     */
    public function setGallery(?Gallery $gallery): void
    {
        $this->gallery = $gallery;
    }

    /**
     * Getter for tags.
     *
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Getter for comment.
     *
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Setter for author.
     *
     * @param User|null $author Author
     */
    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    /**
     * Getter for author.
     *
     * @return User|null Author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Getter for filename.
     *
     * @return string|null Filename
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * Setter for filename.
     *
     * @param string|null $filename Filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * Getter for updatedAt.
     *
     * @return \DateTimeImmutable|null UpdatedAt
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updatedAt.
     *
     * @param \DateTimeImmutable|null $updatedAt UpdatedAt
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable|null $createdAt Created at
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
