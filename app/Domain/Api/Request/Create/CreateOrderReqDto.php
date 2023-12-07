<?php
declare(strict_types=1);

namespace App\Domain\Api\Request\Create;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateOrderReqDto {

	/**
	 * @Assert\NotBlank
	 */
	public int $customer;

	/**
	 * @Assert\NotBlank
	 */
	public int $state;

	/**
	 * @Assert\NotBlank
	 * @Assert\Valid
	 */
	public array $items;
}
