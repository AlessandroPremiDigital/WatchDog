<?php
class Cp_Dog_Block_Adminhtml_Trigger_Grid_Container extends Mage_Adminhtml_Block_Widget_Grid_Container {
	public function __construct() {
		$this->_blockGroup	= 'dog';
        $this->_headerText = Mage::helper('dog')->__('View / Edit Trigger Alert Emails');
		$this->_controller	= 'adminhtml_trigger';
		return parent::__construct();
	}
    
    public function getCreateUrl()
    {
        return $this->getUrl("*/*/edit");
    }
    public function getHeaderHtml()
    {
        return '<h3 class="icon-head head-adminhtml-trigger watchdog">' . $this->getHeaderText() . '</h3>';
    }
}
