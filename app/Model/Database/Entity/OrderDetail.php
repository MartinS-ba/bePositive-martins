<?php
declare(strict_types=1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Trait\TId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class OrderDetail extends AbstractEntity {

	use TId;

	/** @ORM\ManyToOne(targetEntity="Order", inversedBy="orderDetail") */
	private Order $order;

	/** @ORM\ManyToOne(targetEntity="Product", inversedBy="orderDetail") */
	private Product $product;

	/** @ORM\Column(type="float")  */
	private float $price;

	public function getOrder(): Order {
		return $this->order;
	}

	public function setOrder(Order $order): OrderDetail {
		$this->order = $order;
		return $this;
	}

	public function getProduct(): Product {
		return $this->product;
	}

	public function setProduct(Product $product): OrderDetail {
		$this->product = $product;
		return $this;
	}

	public function getPrice(): float {
		return $this->price;
	}

	public function setPrice(float $price): OrderDetail {
		$this->price = $price;
		return $this;
	}

}
