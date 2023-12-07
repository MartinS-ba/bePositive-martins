<?php
declare(strict_types=1);

namespace App\Domain\Api\Request\Create;

use Symfony\Component\Validator\Constraints as Assert;


final class OrderItemReqDto {

	/**
	 * @Assert\NotBlank
	 */
	public int $product;

	/**
	 * @Assert\NotBlank
	 */
	public float $price;
}
