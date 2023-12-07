<?php
declare(strict_types=1);

namespace App\Domain\Api\Request\Update;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateOrderReqDto {

	/**
	 * @Assert\NotBlank
	 */
	public int $state;

}
