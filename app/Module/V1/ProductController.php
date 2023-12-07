<?php
declare(strict_types=1);

namespace App\Module\V1;

use Apitte\Core\Annotation\Controller as Apitte;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Domain\Api\Facade\ProductFacade;
use App\Model\Database\Entity\Product;
use App\Model\Database\EntityManagerDecorator;
use Doctrine\DBAL\Exception\DriverException;
use RuntimeException;

/**
 * @Apitte\Path("/product")
 * @Apitte\Tag("Product")
 */
class ProductController extends BaseV1Controller {

	private ProductFacade $productFacade;

	public function __construct(EntityManagerDecorator $em, ProductFacade $productFacade) {
		parent::__construct($em);
		$this->productFacade = $productFacade;
	}

	/**
	 * @Apitte\Path("/")
	 * @Apitte\Method("GET")
	 */
	public function read(ApiRequest $request, ApiResponse $response): ApiResponse {
		$entities = $this->em->getRepository(Product::class)
			->findAll();
		$result = array_map(static fn (Product $entity) => $entity->toArray(), $entities);
		return $response->writeJsonBody($result);
	}

	/**
	 * @Apitte\Path("/{id}")
	 * @Apitte\Method("GET")
	 * @Apitte\RequestParameters({
	 *       @Apitte\RequestParameter(name="id", in="path", type="int", description="Product ID")
	 *  })
	 */
	public function specificRead(ApiRequest $request, ApiResponse $response): ApiResponse {
		$product = $this->em->getRepository(Product::class)->find(
			$request->getParameter('id')
		);

		if(is_null($product)) {
			return $response->withStatus(404)
				->writeJsonBody([
					'message' => 'Product not found'
				]);
		}

		return $response->writeJsonBody($product->toArray());
	}

	/**
	 * @Apitte\Path("/create")
	 * @Apitte\Method("PUT")
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\Create\CreateProductReqDto")
	 */
	public function create(ApiRequest $request, ApiResponse $response): ?ApiResponse {
		/** @var \App\Domain\Api\Request\Create\CreateProductReqDto $dto */
		$dto = $request->getParsedBody();
		try {
			$product = $this->productFacade->create($dto);

			return $response->writeJsonBody(
				$product->toArray()
			);
		} catch(RuntimeException $e) {
			return $response
				->withStatus($e->getCode())
				->writeJsonBody([
					'message' => $e->getMessage()
				]);
		}
	}

	/**
	 * @Apitte\Path("/{id}/edit")
	 * @Apitte\Method("POST")
	 * @Apitte\RequestParameters({
	 * 		@Apitte\RequestParameter(name="id", in="path", type="int", description="Product ID")
	 * })
	 * @Apitte\RequestBody(entity="App\Domain\Api\Request\Update\UpdateProductReqDto")
	 */
	public function edit(ApiRequest $request, ApiResponse $response): ApiResponse {
		/** @var \App\Domain\Api\Request\Update\UpdateProductReqDto $dto */
		$dto = $request->getParsedBody();
		$id = (int)$request->getParameter('id');

		try {
			$result = $this->productFacade->update($id, $dto);
			return $response->writeJsonBody(
				$result->toArray()
			);
		} catch(RuntimeException $e) {
			return $response
				->withStatus(401)
				->writeJsonBody([
					'message' => $e->getMessage()
				]);
		}
	}

	/**
	 * @Apitte\Path("/{id}/delete")
	 * @Apitte\Method("DELETE")
	 * @Apitte\RequestParameters({
	 * 		@Apitte\RequestParameter(name="id", in="path", type="int", description="Product ID")
	 * })
	 */
	public function delete(ApiRequest $request, ApiResponse $response): ApiResponse {
		$id = (int)$request->getParameter('id');

		try {
			$result = $this->productFacade->delete($id);

			return $response->writeJsonBody(
				$result
			);
		} catch(RuntimeException $e) {
			return $response
				->withStatus(401)
				->writeJsonBody([
					'message' => $e->getMessage()
				]);
		}
	}

}
