<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Trait\TCreatedAt;
use App\Model\Database\Entity\Trait\TId;
use App\Model\Database\Entity\Trait\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 */
class Product extends AbstractEntity {

	use TId;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $name;

	/**
	 * @ORM\Column(type="integer")
	 */
	private int $value;

	/**
	 * @ORM\Column(type="string")
	 */
	private string $stockCode;

	/** @ORM\OneToMany(targetEntity="OrderDetail", mappedBy="product") */
	private ?PersistentCollection $orderDetail = null;

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): Product {
		$this->name = $name;
		return $this;
	}

	public function getValue(): int {
		return $this->value;
	}

	public function setValue(int $value): Product {
		$this->value = $value;
		return $this;
	}

	public function getStockCode(): string {
		return $this->stockCode;
	}

	public function setStockCode(string $stockCode): Product {
		$this->stockCode = $stockCode;
		return $this;
	}

	public function getOrderDetail(): ?PersistentCollection {
		return $this->orderDetail;
	}

	public function setOrderDetail(?PersistentCollection $orderDetail): Product {
		$this->orderDetail = $orderDetail;
		return $this;
	}

	public function toArray(): array {
		return [
			'id' => $this->getId(),
			'name' => $this->getName(),
			'value' => $this->getValue(),
			'stockCode' => $this->getStockCode()
		];
	}

}
