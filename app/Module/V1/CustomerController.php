<?php
declare(strict_types=1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Facade\CustomerFacade;
use App\Model\Database\Entity\Customer;
use App\Model\Database\EntityManagerDecorator;
use Doctrine\DBAL\Exception\DriverException;
use RuntimeException;

/**
 * @Apitte\Path("/customer")
 * @Apitte\Tag("Customer")
 */
class CustomerController extends BaseV1Controller {

	private CustomerFacade $customerFacade;

	public function __construct(EntityManagerDecorator $em, CustomerFacade $customerFacade) {
		parent::__construct($em);
		$this->customerFacade = $customerFacade;
	}

	/**
	 * @Apitte\Path("/")
	 * @Apitte\Method("GET")
	 */
	public function index(ApiRequest $request, ApiResponse $response): ApiResponse {
		$entities = $this->em->getRepository(Customer::class)->findAll();
		$result = array_map(static fn (Customer $entity) => $entity->toArray(), $entities);
		return $response->writeJsonBody($result);
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 *     @Apitte\RequestParameter(name="id", in="path", type="int", description="Customer ID")
	 * })
	 */
	public function show(ApiRequest $request, ApiResponse $response): ApiResponse {
		$id = (int)$request->getParameter('id');
		$result = $this->em->getRepository(Customer::class)->find($id);

		if(!$result) {
			return $response->withStatus(401)
				->writeJsonBody(['message' => 'Customer not found']);
		}

		return $response->writeJsonBody($result->toArray());
	}

	/**
	 * @Apitte\Path("/create")
	 * @Apitte\Method("PUT")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\Create\CreateCustomerReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ApiResponse {
		/** @var \App\Domain\Api\Request\Create\CreateCustomerReqDto $dto */
		$dto = $request->getParsedBody();
		try {
			$result = $this->customerFacade->create($dto);
			return $response->writeJsonBody($result->toArray());
		} catch(RuntimeException $e) {
			return $response->withStatus(401)
				->writeJsonBody(['message' => $e->getMessage()]);
		}
	}

	/**
	 * @Apitte\Path("/{id}/edit")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestParameters({
	 *     @Apitte\RequestParameter(name="id", in="path", type="int", description="Customer ID")
	 * })
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\Update\UpdateCustomerReqDto")
	 */
	public function edit(ApiRequest $request, ApiResponse $response): ApiResponse {
		$dto = $request->getParsedBody();
		$id = (int)$request->getParameter('id');

		try {
			$result = $this->customerFacade->update($id, $dto);
			return $response->writeJsonBody($result->toArray());
		} catch(RuntimeException $e) {
			return $response->withStatus(401)
				->writeJsonBody(['message' => $e->getMessage()]);
		}
	}

	/**
	 * @Apitte\Path("/{id}/delete")
	 * @Apitte\Method("DELETE")
	 * @Apitte\RequestParameters({
	 *     @Apitte\RequestParameter(name="id", in="path", type="int", description="Customer ID"),
	 * })
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse {
		$id = (int)$request->getParameter('id');

		try {
			$result = $this->customerFacade->delete($id);
			return $response->writeJsonBody($result);
		} catch(RuntimeException $e) {
			return $response->withStatus(401)
				->writeJsonBody(['message' => $e->getMessage()]);
		}
	}
}
