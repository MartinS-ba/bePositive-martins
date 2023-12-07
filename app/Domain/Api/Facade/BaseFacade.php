<?php
declare(strict_types=1);

namespace App\Domain\Api\Facade;

use App\Model\Database\Entity\Customer;
use App\Model\Database\Entity\Order;
use App\Model\Database\Entity\Product;
use App\Model\Database\EntityManagerDecorator;

abstract class BaseFacade {

	protected EntityManagerDecorator $em;

	public function __construct(EntityManagerDecorator $em) {
		$this->em = $em;
	}

	protected function getEntityById(string $entity, int $id): Customer|Product|Order|null {
		return $this->em->getRepository($entity)->find($id);
	}

}
