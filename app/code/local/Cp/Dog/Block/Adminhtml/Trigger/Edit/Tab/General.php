<?php
 
class Cp_Dog_Block_Adminhtml_Trigger_Edit_Tab_General extends Cp_Dog_Block_Adminhtml_Trigger_Edit_Tab_Abstract
{
    protected function _prepareForm()
    {
        $trigger	= Mage::registry('current_trigger');
	$_isNew		= ($trigger instanceof Cp_Dog_Model_Trigger ? false : true);
	
	
        $_args = array();
        
        if (!$_isNew)
        {
		$triggerable	= $trigger->getTriggerable();
		//$_args 		= array("trigger_id" => $this->getRequest()->getParam("trigger_id"));
		$storeValue	= $triggerable->getStoreIds();
		$nameValue	= $trigger->getName();
        } else {
		$storeValue	= '';
		$nameValue	= '';
	}
        
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/*', $_args),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
        ));
	
 	
      #  $form->setUseContainer(true); !Important that this is commented out
 
        $this->setForm($form);
        
        $fieldset = $form->addFieldset('meta_info', array(
            'legend' =>Mage::helper("dog")->__('General Settings')
        ));
        
        $fieldset->addField('trigger_name', 'text', array(
             'label'     => Mage::helper('dog')->__('Trigger Name'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'name',
             'note'     => Mage::helper('dog')->__('Enter a name (ie: "Electronics Store Order Spike")'),
	     'value'	=> $nameValue
        ));        
        
        $_times             = array();
      /*  for($i = 1; $i < 24; $i++)
        {
            $_times[$i] = "{$i} : 00";
        }*/
        
      /*  $_times             = array("23" => "11:00 PM", "17" => "5:00 PM");
        
        $_timezones         = Mage::app()->getLocale()->getOptionTimezones();
           
        $_currentZone       = Mage::getStoreConfig("general/locale/timezone");
        
        $_currentZoneLabel  = null;
        
        foreach($_timezones as $zone)
        {
            if ($zone["value"] == $_currentZone)
                $_currentZoneLabel = $zone["label"];
        }
                
        $fieldset->addField('profile_time', 'select', array(
            'label'     => Mage::helper("dog")->__('Send Time'),
            'class'     => 'required-entry',
            'required'  => true,
            'note'     => "Remember this will be based on the timezone your default config is set to. Currently yours is: <br/><strong>" . $_currentZoneLabel . '</strong>',
            'name'      => 'send_time',
            'values'    => $_times
        ));*/
        
        $_storeValues = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm();
	
        
        $fieldset->addField('profile_stores', 'multiselect', array(
             'label'     => Mage::helper('dog')->__('Stores'),
             'class'     => 'required-entry',
             'required'  => true,
             'width'     => 400,
             'name'      => 'store_ids[]',
             'values'    => $_storeValues,
             'note'     => Mage::helper('dog')->__('Select all stores you would like this trigger to monitor.'),
	     'value'    => $storeValue,
        ));        
        /**
         if (!$_isNew)
         {
             $_data    = array(
                'trigger_stores' => $this->getTrigger()->getStoreIds(),
                'trigger_name'   => $this->getTrigger()->getName()
                
             );
             $form->setValues($_data);
         }*/
        
        return parent::_prepareForm();
    }
}
