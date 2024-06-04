<?php
namespace Ais\Helloword\Api;
interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \Ais\Helloword\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);
    /**
     * Set descriptions for the products.
     *
     * @param \Ais\Helloword\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products);
}