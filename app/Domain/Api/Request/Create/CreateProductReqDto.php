<?php
declare(strict_types=1);

namespace App\Domain\Api\Request;
namespace App\Domain\Api\Request\Create;
use Symfony\Component\Validator\Constraints as Assert;


final class CreateProductReqDto {

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
