<?php
namespace Ais\Helloword\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Test
 * @package Ais\Helloword\Controller\Index;
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
        echo "Ais Hello wlord";
        return;
    }

   
   
  
}
