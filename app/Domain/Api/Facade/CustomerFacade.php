<?php
declare(strict_types=1);

namespace App\Domain\Api\Facade;

use App\Domain\Api\Request\Create\CreateCustomerReqDto;
use App\Domain\Api\Request\Update\UpdateCustomerReqDto;
use App\Model\Database\Entity\Customer;
use RuntimeException;

final class CustomerFacade extends BaseFacade {

	/**
	 * Creates a new customer.
	 *
	 * @param CreateCustomerReqDto $dto The DTO object containing the customer's information.
	 *
	 * @return Customer The created customer object.
	 */
	public function create(CreateCustomerReqDto $dto): Customer {
		$customer = (new Customer())
			->setFirstName($dto->firstName)
			->setLastName($dto->lastName)
			->setEmail($dto->email)
			->setTelephone($dto->telephone);

		$this->em->persist($customer);
		$this->em->flush($customer);

		return $customer;
	}

	/**
	 * Updates a customer with the given ID using the provided data.
	 *
	 * @param int $customerId The ID of the customer to update.
	 * @param UpdateCustomerReqDto $dto The data to update the customer with.
	 * @return Customer The updated customer object.
	 * @throws RuntimeException If the customer was not found.
	 */
	public function update(int $customerId, UpdateCustomerReqDto $dto): Customer {
		$customer = $this->getEntityById(Customer::class, $customerId);

		if(is_null($customer)) {
			throw new RuntimeException('Customer was not found');
		}

		$customer
			->setFirstName($dto->firstName)
			->setLastName($dto->lastName)
			->setTelephone($dto->telephone)
			->setEmail($dto->email);

		$this->em->flush();

		return $customer;
	}

	/**
	 * Deletes a customer and associated orders from the database.
	 *
	 * @param int $customerId The ID of the customer to delete.
	 *
	 * @return array An array with a single element 'message' indicating the success of the operation.
	 * @throws RuntimeException If the customer was not found.
	 *
	 */
	public function delete(int $customerId): array {
		$customer = $this->getEntityById(Customer::class, $customerId);

		if(is_null($customer)) {
			throw new RuntimeException('Customer was not found');
		}

		if($customer->getOrder() && !$customer->getOrder()->isEmpty()) {
			throw new RuntimeException('The customer has created an order. Cannot be deleted.');
		}
		$this->em->remove($customer);
		$this->em->flush();

		return [
			'message' => 'Successfully removed'
		];
	}

}
