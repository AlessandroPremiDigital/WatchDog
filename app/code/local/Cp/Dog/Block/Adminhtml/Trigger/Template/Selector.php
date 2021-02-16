<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Template_Selector
    extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->loadTemplates();
        $this->setTemplate("cp/dog/trigger/template/selector.phtml");
    }
    
    public function loadTemplates()
    {
        $this->setData("template_collection", Mage::helper('dog/trigger')->getTemplateCollection());
        return $this;
    }
}    