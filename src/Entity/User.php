<?php
/**
 * User entity.
 */

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class user.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'email_idx', columns: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Email.
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $login;

    /**
     * Roles.
     *
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Password.
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private ?string $password;

    /**
     * Represents the relationship between this object and its associated comments.
     *
     * @var Collection contains the collection of comments associated with this object
     *
     * @ORM\OneToMany(mappedBy="author", targetEntity=Comment::class)
     *
     * @ORM\JoinColumn(nullable=true)
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $comments;

    /**
     * Represents the author of this object.
     *
     * @var User represents the user who is the author of this object
     */
    private User $author;

    /**
     * Constructs a new instance of this object.
     * Initializes the comments collection as an ArrayCollection.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Retrieves the login of this object.
     *
     * @return string|null the login of this object, or null if it is not set
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * Sets the login of this object.
     *
     * @param string|null $login the login to set, or null to unset the login
     */
    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    /**
     * A visual identifier that represents this index.html.twig.
     *
     * @return string User identifier
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     *
     * @return string Username
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for roles.
     *
     * @return array<int, string> Roles
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Setter for roles.
     *
     * @param array<int, string> $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for password.
     *
     * @return string|null Password
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for password.
     *
     * @param string $password User password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive information from the token.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * Retrieves the comments associated with this object.
     *
     * @return Collection the collection of comments associated with this object
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Retrieves the author of this object.
     *
     * @return User|null the author of this object, or null if there is no assigned author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Sets the author of this object.
     *
     * @param User|null $author the author to set, or null if there is no assigned author
     *
     * @return self a reference to itself
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
