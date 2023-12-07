<?php
declare(strict_types=1);

namespace App\Domain\Api\Facade;

use App\Domain\Api\Request\Create\CreateOrderReqDto;
use App\Domain\Api\Request\Update\UpdateOrderReqDto;
use App\Model\Database\Entity\Customer;
use App\Model\Database\Entity\Order;
use App\Model\Database\Entity\OrderDetail;
use App\Model\Database\Entity\Product;
use RuntimeException;

class OrderFacade extends BaseFacade {

	/**
	 * Creates a new order.
	 *
	 * @param CreateOrderReqDto $dto The data transfer object containing the order details.
	 * @return Order Returns the created Order object.
	 * @throws RuntimeException If the customer does not exist or if a product does not exist.
	 */
	public function create(CreateOrderReqDto $dto): Order {
		$customer = $this->getEntityById(Customer::class, $dto->customer);
		$product = $this->em->getRepository(Product::class);

		if(!$customer) {
			throw new RuntimeException('Customer does not exist');
		}

		$order = (new Order())
			->setCustomer($customer)
			->setState($dto->state);
		$order->setCreatedAt();
		$this->em->persist($order);
		foreach($dto->items as $row) {
			$productEntity = $product->find($row['product']);
			if(!$productEntity) {
				throw new RuntimeException('Product does not exist');
			}
			$orderDetail = new OrderDetail();
			$orderDetail->setOrder($order);
			$orderDetail->setProduct($productEntity);
			$orderDetail->setPrice($row['price']);
			$this->em->persist($orderDetail);
		}

		$this->em->flush();

		/** todo Důvod: orderDetail má zpoždený flushing, takže ručně aktualizuji entitu */
		$this->em->refresh($order);

		return $order;
	}

	/**
	 * Updates the state of an order.
	 *
	 * @param int $orderId The ID of the order to update.
	 * @param UpdateOrderReqDto $dto The DTO containing the updated state of the order.
	 * @return Order The updated order.
	 * @throws RuntimeException If the order is not found.
	 */
	public function update(int $orderId, UpdateOrderReqDto $dto): Order {
		$order = $this->getEntityById(Order::class, $orderId);

		if(!$order) {
			throw new RuntimeException('Order was not found');
		}

		$order->setState($dto->state)
			->setUpdatedAt();

		$this->em->flush();

		return $order;
	}

	/**
	 * Deletes an order by ID.
	 *
	 * @param int $orderId The ID of the order to be deleted.
	 * @return array An array containing a message indicating that the order was successfully removed.
	 * @throws RuntimeException If the order was not found.
	 */
	public function delete(int $orderId): array {
		$order = $this->getEntityById(Order::class, $orderId);

		if(!$order) {
			throw new RuntimeException('Order was not found');
		}

		$this->em->remove($order);
		$this->em->flush();

		return ['message' => 'Successfully removed'];
	}

	/**
	 * Retrieves the orders associated with a specific customer.
	 *
	 * @param int $customerId The ID of the customer.
	 * @return array Returns an array of orders associated with the customer, or an empty array if none exist.
	 * @throws RuntimeException If the customer was not found.
	 */
	public function getOrdersByCustomerId(int $customerId): array {
		$customer = $this->getEntityById(Customer::class, $customerId);

		if(!$customer) {
			throw new RuntimeException('Customer was not found');
		}

		$orders = $customer->getOrder() ? $customer->getOrder()->toArray() : [];

		return array_map(static function (Order $order) {
			return $order->toArray();
		}, $orders);
	}
}
