<?php
declare(strict_types=1);

namespace App\Domain\Api\Facade;

use App\Domain\Api\Request\Create\CreateProductReqDto;
use App\Domain\Api\Request\Update\UpdateProductReqDto;
use App\Model\Database\Entity\Product;
use RuntimeException;

class ProductFacade extends BaseFacade {

	/**
	 * Creates a new product
	 *
	 * @param CreateProductReqDto $dto The request DTO containing the product details
	 * @return Product The created product
	 */
	public function create(CreateProductReqDto $dto): Product {
		$product = (new Product())
			->setName($dto->name)
			->setValue($dto->value)
			->setStockCode($dto->stockCode);

		$this->em->persist($product);
		$this->em->flush($product);

		return $product;
	}

	/**
	 * Update a product with the given ID.
	 *
	 * @param int $productId The ID of the product to update.
	 * @param UpdateProductReqDto $dto The DTO object containing the updated product data.
	 * @return Product The updated product object.
	 * @throws RuntimeException If the product with the given ID is not found.
	 */
	public function update(int $productId, UpdateProductReqDto $dto): Product {
		$product = $this->getEntityById(Product::class, $productId);
		if(is_null($product)) {
			throw new RuntimeException('Product was not found');
		}

		$product
			->setName($dto->name)
			->setValue($dto->value)
			->setStockCode($dto->stockCode);

		$this->em->flush();

		return $product;
	}

	/**
	 * Deletes a product from the database.
	 *
	 * @param int $productId The ID of the product to be deleted.
	 *
	 * @return array An array with a success message.
	 * @throws RuntimeException If the product with the specified ID was not found.
	 *
	 */
	public function delete(int $productId): array {
		$product = $this->getEntityById(Product::class, $productId);

		if(is_null($product)) {
			throw new RuntimeException('Order was not found');
		}

		if($product->getOrderDetail() && !$product->getOrderDetail()->isEmpty()) {
			throw new RuntimeException('The product has already been purchased. Cannot be deleted.');
		}

		$this->em->remove($product);
		$this->em->flush();

		return [
			'message' => 'Successfully removed'
		];
	}

}
