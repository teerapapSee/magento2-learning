<?php
namespace Ais\Frontend\Controller\TestFolder;

use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Test
 * @package Ais\Frontend\Controller\TestFolder;
 */
class Test extends \Magento\Framework\App\Action\Action
{
  
    public function __construct(
        \Magento\Backend\App\Action\Context $context
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {        
        echo "Ais Test";
        return;
    }

   
   
  
}
