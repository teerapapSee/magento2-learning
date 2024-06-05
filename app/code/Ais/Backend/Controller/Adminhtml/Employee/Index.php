<?php
namespace Ais\Backend\Controller\Adminhtml\Employee;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Hello test controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        echo __('Hello Ais_Backend Team.');
    }

    /**
     * Check Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ais_Backend::employee');
    }
}