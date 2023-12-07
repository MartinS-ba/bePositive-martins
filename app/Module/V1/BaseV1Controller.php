<?php declare(strict_types=1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use App\Model\Database\EntityManagerDecorator;
use App\Module\BaseController;

/**
 * @Apitte\Path("/v1")
 * @Apitte\Id("v1")
 */
abstract class BaseV1Controller extends BaseController {

	protected EntityManagerDecorator $em;

	public function __construct(EntityManagerDecorator $em) {
		$this->em = $em;
	}

}
