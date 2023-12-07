<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Trait\TId;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 */
class Customer extends AbstractEntity {

	use TId;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $firstName;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $lastName;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $email;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $telephone;

	/** @ORM\OneToMany(targetEntity="Order", mappedBy="customer") */
	private ?PersistentCollection $order = null;

	public function getFirstName(): string {
		return $this->firstName;
	}

	public function setFirstName(string $firstName): Customer {
		$this->firstName = $firstName;
		return $this;
	}

	public function getLastName(): string {
		return $this->lastName;
	}

	public function setLastName(string $lastName): Customer {
		$this->lastName = $lastName;
		return $this;
	}

	public function getFullName(): string {
		return $this->getFirstName() . ' ' . $this->getLastName();
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function setEmail(string $email): Customer {
		$this->email = $email;
		return $this;
	}

	public function getTelephone(): string {
		return $this->telephone;
	}

	public function setTelephone(string $telephone): Customer {
		$this->telephone = $telephone;
		return $this;
	}

	public function getOrder(): ?PersistentCollection {
		return $this->order;
	}

	public function setOrder(?PersistentCollection $order): Customer {
		$this->order = $order;
		return $this;
	}

	public function toArray(): array {
		return [
			'id' => $this->getId(),
			'firstName' => $this->getFirstName(),
			'lastName' => $this->getLastName(),
			'email' => $this->getEmail(),
			'telephone' => $this->getTelephone()
		];
	}

}
