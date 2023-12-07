<?php
declare(strict_types=1);

namespace App\Domain\Api\Request\Update;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCustomerReqDto {

	/**
	 * @Assert\NotBlank
	 */
	public string $firstName;

	/**
	 * @Assert\NotBlank
	 */
	public string $lastName;

	/**
	 * @Assert\NotBlank
	 * @Assert\Email
	 */
	public string $email;

	/**
	 * @Assert\NotBlank
	 */
	public string $telephone;

}
