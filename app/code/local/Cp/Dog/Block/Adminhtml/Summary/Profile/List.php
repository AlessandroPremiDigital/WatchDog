<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_List
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_summary_profile_list';
        $this->_headerText = Mage::helper('dog')->__('View Existing Daily Summary Alerts');
        $this->_blockGroup = "dog";
        parent::__construct();
      #  $this->setTemplate('cp/warehouse/grid/container.phtml');
        $this->_updateButton('add', 'onclick', 'window.location = \'' . $this->getUrl('*/*/edit') . '\'');
        /*$this->addButton('filter_form_submit', array(
            'label'     => Mage::helper('reports')->__('Show Report'),
            'onclick'   => 'filterFormSubmit()'
        ));*/
    }

    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/*', array('_current' => true));
    }    
    
    public function getHeaderHtml()
    {
       return '<h3 class="icon-head head-empty watchdog">'. $this->_headerText. '</h3>'; 
    }
}    