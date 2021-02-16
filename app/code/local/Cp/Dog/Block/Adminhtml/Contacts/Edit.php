<?php
class Cp_Dog_Block_Adminhtml_Contacts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cp/dog/contacts.phtml');
        $this->_removeButton('reset');
        $this->_updateButton('save', 'label', Mage::helper('dog')->__('Save and Update Contacts'));
    }    
    
    public function getHeaderText()
    {
        return 'Manage WatchDog Contacts';
    }    
    
    public function getHeaderHtml()
    {
        return '<h3 class="icon-head head-empty watchdog">' . $this->getHeaderText() . '</h3>';
    }
    
    public function getFieldMap()
    {
        return array(
          #  "contact_id" => "Id", 
            "email"      => "Email", 
            "name"       => "Full Name"
        );
    }
    
    public function getContacts()
    {
        return Mage::getResourceModel("dog/contact_collection"); 
    }
    
    /**
     * Preparing layout, adding buttons
     *
     * @return Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('dog')->__('Delete'),
                    'class' => 'delete delete-option'
                )));

        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('dog')->__('Add Contact'),
                    'class' => 'add',
                    'id'    => 'add_new_option_button'
                )));
        return parent::_prepareLayout();
    }

    /**
     * Retrieve HTML of delete button
     *
     * @return string
     */
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    /**
     * Retrieve HTML of add button
     *
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }    
}
