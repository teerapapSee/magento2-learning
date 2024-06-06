<?php
namespace Ais\RestApi\Api\ProductRepository;
interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \Ais\RestApi\Api\ProductRepository\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);
    /**
     * Set descriptions for the products.
     *
     * @param \Ais\RestApi\Api\ProductRepository\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products);
}