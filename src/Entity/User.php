<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface {

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = array();

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string", length=100, nullable=false)
	 * @Assert\Length(
	 * min = "5",
	 * max = "100",
	 * minMessage = "Password must be at least 5 characters long",
	 * maxMessage = "Password cannot be longer than than 100 characters",
	 * )
	 */
	private $password;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $bio;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $birthday;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $email;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $phone;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $image;

	public function getId(): ?int {
		return $this->id;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUsername(): string {
		return (string) $this->username;
	}

	public function setUsername( string $username ): self {
		$this->username = $username;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array {
		$roles = $this->roles;
		// guarantee every user at least has ROLE_VISITOR
		$roles[] = 'ROLE_VISITOR';

		return array_unique( $roles );
	}

	public function setRoles( array $roles ): self {
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string {
		return (string) $this->password;
	}

	public function setPassword( string $password ): self {
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getSalt() {
		// not needed when using the "bcrypt" algorithm in security.yaml
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials() {
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	public function getBio(): ?string {
		return $this->bio;
	}

	public function setBio( ?string $bio ): self {
		$this->bio = $bio;

		return $this;
	}

	public function getBirthday(): ?string {
		return $this->birthday;
	}

	public function setBirthday( ?string $birthday ): self {
		$this->birthday = $birthday;

		return $this;
	}

	public function getEmail(): ?string {
		return $this->email;
	}

	public function setEmail( ?string $email ): self {
		$this->email = $email;

		return $this;
	}

	public function getPhone(): ?string {
		return $this->phone;
	}

	public function setPhone( ?string $phone ): self {
		$this->phone = $phone;

		return $this;
	}

	public function getName(): ?string {
		return $this->name;
	}

	public function setName( ?string $name ): self {
		$this->name = $name;

		return $this;
	}

	public function getImage(): ?string {
		return $this->image;
	}

	public function setImage( ?string $image ): self {
		$this->image = $image;

		return $this;
	}
}
