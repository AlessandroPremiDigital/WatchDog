<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Edit_Tab_Contacts
    extends Cp_Dog_Block_Adminhtml_Trigger_Edit_Tab_Abstract
{
    public function __construct()
    {
        parent::__construct();
       // $this->setTemplate('cp/dog/summary/profile/edit/reports/form.phtml');
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('dog/adminhtml_form_contacts_renderer_fieldset_element')
        );
    }
    protected function _prepareForm()
    {
        $_isNew = $this->isNew();
        
        $_args = array();
        
        if (!$_isNew)
        {
            $_args = array("trigger_id" => $this->getRequest()->getParam("trigger_id"));
        }
        
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/*', $_args),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
        ));
 
      #  $form->setUseContainer(true); !Important that this is commented out
 
        $this->setForm($form);
        
        $fieldset = $form->addFieldset('masters', array(
            'legend' => Mage::helper("dog")->__('Master Contacts')
        ));
        
        $fieldset->addField('master_contacts', "text", array(
            'label' => "Your Master Contacts",
            'disabled' => true,
            'value'    => Mage::helper("dog")->getMasterContact()->getConfigValue(),
            'note'     => "Note: Master contacts (set in the settings area) will receive ALL reports sent out of the system.<br/>  <span class='tz-default'>To change this navigate to<br/> <strong>CustomerParadigm->Watchdog->Settings</strong></span>"
        ));
        
        $fieldset = $form->addFieldset('contacts', array(
            'legend' =>Mage::helper("dog")->__('Email Contacts')
        ));
        
        $data = array();
        
        $fieldset->addType('contact','Cp_Dog_Block_Adminhtml_Form_Element_Contact');
        
        foreach(Mage::getResourceModel("dog/contact_collection") as $contact)
        {
            $args = array(
                'label' => Mage::helper('dog')->__($contact->getData('name')),
                'email' => $contact->getData('email'),
                'name'        => 'contacts[]',
                'value'       => $contact->getId()
            );
            if (!$this->isNew())
            {
                $model    = $this->getTrigger()->getTriggerable();
                $contacts = $model->getData('contacts');
                if (in_array($contact->getId(), $contacts)) 
                    $args["checked"] = true;
            }            
            $fieldset->addField('contact_'.$contact->getId(), 'contact', $args);
            
        }
        
      #  $form->setValues($data);
                
        return parent::_prepareForm();
    }    
}    
