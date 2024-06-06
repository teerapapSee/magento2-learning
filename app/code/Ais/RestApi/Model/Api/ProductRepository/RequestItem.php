<?php
namespace Ais\RestApi\Model\Api\ProductRepository;
use Ais\RestApi\Api\ProductRepository\RequestItemInterface;
use Magento\Framework\DataObject;
/**
 * Class RequestItem
 */
class RequestItem extends DataObject implements RequestItemInterface
{
    public function getId() : int
    {
        return $this->_getData(self::DATA_ID);
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
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description) : mixed
    {
        return $this->setData(self::DATA_DESCRIPTION, $description);
    }
}