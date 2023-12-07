<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Enum\State;
use App\Model\Database\Entity\Trait\TCreatedAt;
use App\Model\Database\Entity\Trait\TId;
use App\Model\Database\Entity\Trait\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="`order`")
 */
class Order extends AbstractEntity {

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/** @ORM\ManyToOne(targetEntity="Customer", inversedBy="order") */
	private Customer $customer;

	/** @ORM\OneToMany(targetEntity="OrderDetail", mappedBy="order") */
	private ?PersistentCollection $orderDetail = null;

	/**
	 * @ORM\Column(type="string")
	 */
	private int $state;

	public function getCustomer(): Customer {
		return $this->customer;
	}

	public function setCustomer(Customer $customer): Order {
		$this->customer = $customer;
		return $this;
	}

	public function getOrderDetail(): ?PersistentCollection {
		return $this->orderDetail;
	}

	public function setOrderDetail(?PersistentCollection $orderDetail): Order {
		$this->orderDetail = $orderDetail;
		return $this;
	}

	public function getState(): State {
		return State::from($this->state);
	}

	public function setState(int $state): Order {
		$this->state = State::from($state)->value;
		return $this;
	}

	public function toArray(): array {
		return [
			'id' => $this->getId(),
			'state' => $this->getState()->getDescription(),
			'createdAt' => $this->getCreatedAt(),
			'updatedAt' => $this->getUpdatedAt(),
			'customer' => $this->getCustomerData(),
			'products' => $this->getProductsData()
		];
	}

	private function getCustomerData(): array {
		$customer = $this->getCustomer();

		return [
			'fullName' => $customer->getFullName(),
			'email' => $customer->getEmail(),
			'telephone' => $customer->getTelephone()
		];
	}

	private function getProductsData(): array {
		$productArray = [];
		if($this->getOrderDetail() && !$this->getOrderDetail()->isEmpty()) {
			/** @var \App\Model\Database\Entity\OrderDetail $row */
			foreach($this->getOrderDetail()->getValues() as $row) {
				$productArray[] = [
					'name' => $row->getProduct()->getName(),
					'value' => $row->getProduct()->getValue(),
					'stockCode' => $row->getProduct()->getStockCode(),
					'price' => $row->getPrice()
				];
			}
		}
		return $productArray;
	}

}
