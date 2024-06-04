<?php
namespace Ais\Helloword\Api;
interface ResponseItemInterface
{
    const DATA_ID = 'id';
    const DATA_SKU = 'sku';
    const DATA_NAME = 'name';
    const DATA_DESCRIPTION = 'description';
    /**
     * @return int
     */
    public function getId();
    /**
     * @return string
     */
    public function getSku();
    /**
     * @return string
     */
    public function getName();
    /**
     * @return string|null
     */
    public function getDescription();
    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id);
    /**
     * @param string $sku
     * @return $this
     */
    public function setSku(string $sku);
    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name);
    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description);
}