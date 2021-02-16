<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('trigger_tabs');
        $this->setDestElementId('trigger_edit_form');
        $this->setTitle(Mage::helper('dog')->__('Trigger'));
    }

    protected function _prepareLayout()
    {
        $this->addTab('general', array(
            'label'     => Mage::helper('dog')->__('General'),
            'content'   => $this->_translateHtml($this->getLayout()
                ->createBlock('dog/adminhtml_trigger_edit_tab_general')->toHtml()),
        ));   
       $this->addTab('conditions', array(
            'label'     => Mage::helper('dog')->__('Conditions'),
            'content'   => $this->_translateHtml($this->getLayout()
                ->createBlock('dog/adminhtml_trigger_edit_tab_conditions')->toHtml()),
        ));     
        $this->addTab('contacts', array(
            'label'     => Mage::helper('dog')->__('Email Contacts'),
            'content'   => $this->_translateHtml($this->getLayout()
                ->createBlock('dog/adminhtml_trigger_edit_tab_contacts')->toHtml()),
        ));                    
        return parent::_prepareLayout();
    }
    
    /**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }    
    
}    