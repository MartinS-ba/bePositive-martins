<?php
declare(strict_types=1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Facade\OrderFacade;
use App\Domain\Api\Request\Update\UpdateOrderReqDto;
use App\Model\Database\Entity\Order;
use App\Model\Database\EntityManagerDecorator;
use RuntimeException;
use Doctrine\DBAL\Exception\DriverException;
use Tracy\Debugger;

/**
 * @Apitte\Path("/order")
 * @Apitte\Tag("Order")
 */
class OrderController extends BaseV1Controller {

	private OrderFacade $orderFacade;

	public function __construct(EntityManagerDecorator $em, OrderFacade $orderFacade) {
		parent::__construct($em);
		$this->orderFacade = $orderFacade;
	}

	/**
	 * @Apitte\Path("/")
	 * @Apitte\Method("GET")
	 */
	public function read(ApiRequest $request, ApiResponse $response): ApiResponse {
		$entities = $this->em->getRepository(Order::class)->findAll();
		$result = array_map(static fn (Order $entity) => $entity->toArray(), $entities);
		return $response->writeJsonBody($result);
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 *       @Apitte\RequestParameter(name="id", in="path", type="int", description="Order ID")
	 *  })
	 */
	public function specificRead(ApiRequest $request, ApiResponse $response): ApiResponse {
		$order = $this->em->getRepository(Order::class)->find(
			$request->getParameter('id')
		);

		if (!$order) {
			return $response->withStatus(404)
				->writeJsonBody([
					'message' => 'Order not found'
				]);
		}

		return $response->writeJsonBody($order->toArray());
	}

	/**
	 * @Apitte\Path("/create")
	 * @Apitte\Method("PUT")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\Create\CreateOrderReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse {
		/** @var \App\Domain\Api\Request\Create\CreateOrderReqDto $dtos */
		$dto = $request->getParsedBody();
		try {
			$order = $this->orderFacade->create($dto);
			return $response->writeJsonBody($order->toArray());
		} catch (RuntimeException $e) {
			return $response
				->withStatus(400)
				->writeJsonBody(['message' => $e->getMessage()]);
		}
	}

	/**
	 * @Apitte\Path("/{id}/edit")
	 * @Apitte\Method("PATCH")
	 * @Apitte\RequestParameters({
	 * 		@Apitte\RequestParameter(name="id", in="path", type="int", description="Order ID")
	 * })
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\Update\UpdateOrderReqDto")
	 */
	public function edit(ApiRequest $request, ApiResponse $response): ApiResponse {
		/** @var \App\Domain\Api\Request\Update\UpdateOrderReqDto $dto */
		$dto = $request->getParsedBody();
		$orderId = (int)$request->getParameter('id');

		try {
			$order = $this->orderFacade->update($orderId, $dto);
			return $response->writeJsonBody($order->toArray());
		} catch (RuntimeException $e) {
			return $response
				->withStatus(400)
				->writeJsonBody(['message' => $e->getMessage()]);
		}
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("DELETE")
	 * @Apitte\RequestParameters({
	 * 		@Apitte\RequestParameter(name="id", in="path", type="int", description="Order ID")
	 * })
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse {
		$orderId = (int) $request->getParameter('id');

		try {
			$order = $this->orderFacade->delete($orderId);
			return $response->writeJsonBody($order);
		} catch (RuntimeException $e) {
			return $response
				->withStatus(400)
				->writeJsonBody(['message' => $e->getMessage()]);
		}
	}

	/**
	 * @Apitte\Path("/{id}/customer")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 * 		@Apitte\RequestParameter(name="id", in="path", type="int", description="Customer ID")
	 * })
	 */
	public function readByCustomer(ApiRequest $request, ApiResponse $response): ApiResponse {
		$customerId = (int) $request->getParameter('id');

		try {
			$orders = $this->orderFacade->getOrdersByCustomerId($customerId);
			return $response->writeJsonBody($orders);
		} catch (RuntimeException $e) {
			return $response
				->withStatus(400)
				->writeJsonBody(['message' => $e->getMessage()]);
		}
	}
}
