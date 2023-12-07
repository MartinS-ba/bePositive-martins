<?php
declare(strict_types=1);

namespace App\Domain\Api\Request\Update;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductReqDto {

	/**
	 * @Assert\NotBlank
	 */
	public string $name;

	/**
	 * @Assert\NotBlank
	 */
	public int $value;

	/**
	 * @Assert\NotBlank
	 */
	public string $stockCode;

}
