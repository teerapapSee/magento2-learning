<?php
namespace Ais\RestApi\Model\Api\ProductRepository;
use Ais\RestApi\Api\ProductRepository\ResponseItemInterface;
use Magento\Framework\DataObject;
/**
 * Class ResponseItem
 */
class ResponseItem extends DataObject implements ResponseItemInterface
{
    public function getId() : int
    {
        return $this->_getData(self::DATA_ID);
    }
    public function getSku() : string
    {
        return $this->_getData(self::DATA_SKU);
    }
    public function getName() : string
    {
        return $this->_getData(self::DATA_NAME);
    }
    public function getDescription() : string
    {
        return $this->_getData(self::DATA_DESCRIPTION);
    }
    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id) : mixed
    {
        return $this->setData(self::DATA_ID, $id);
    }
    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku) : mixed
    {
        return $this->setData(self::DATA_SKU, $sku);
    }
    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name) : mixed
    {
        return $this->setData(self::DATA_NAME, $name);
    }
    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description) : mixed
    {
        return $this->setData(self::DATA_DESCRIPTION, $description);
    }
}