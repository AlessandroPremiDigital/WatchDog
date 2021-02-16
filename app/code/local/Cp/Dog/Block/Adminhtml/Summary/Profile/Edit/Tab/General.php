<?php
 
class Cp_Dog_Block_Adminhtml_Summary_Profile_Edit_Tab_General extends Cp_Dog_Block_Adminhtml_Summary_Profile_Edit_Tab_Abstract
{
    protected function _prepareForm()
    {
        $_isNew = $this->isNew();
        
        $_args = array();
        
        if (!$_isNew)
        {
            $_args = array("profile_id" => $this->getRequest()->getParam("profile_id"));
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
        
        $fieldset->addField('profile_name', 'text', array(
             'label'     => Mage::helper('dog')->__('Summary Email Name'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'name',
             'note'     => Mage::helper('dog')->__('Enter a name (ie: "Electronic Store Sales Report")'),
        ));        
        
        $_times             = array();
      /*  for($i = 1; $i < 24; $i++)
        {
            $_times[$i] = "{$i} : 00";
        }*/
        
        $_times             = Mage::helper("dog/summary")->getSendTimeOptions();
        
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
            'note'     => "Note: Send time is based on the timezone of your server, and the report will be from 12 am the current day until the selected time.<br/><span class='tz-default'>Your default timezone is set to:<br/><strong>" . $_currentZoneLabel . '</strong></span>',
            'name'      => 'send_time',
            'values'    => $_times
        ));
        
        $_storeValues = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm();
        
        $fieldset->addField('profile_stores', 'multiselect', array(
             'label'     => Mage::helper('dog')->__('Stores'),
             'class'     => 'required-entry',
             'required'  => true,
             'width'     => 400,
             'name'      => 'store_ids[]',
             'values'    => $_storeValues,
             'note'     => Mage::helper('dog')->__('Select all stores you would like in this summary.<br/><br/> Note: If you select multiple stores, the report will aggregate reporting information for all stores.  To view individual store reporting, please create an individual summary for each store.')
        ));        
        
         if (!$_isNew)
         {
             $_data    = array(
                'profile_stores' => $this->getProfile()->getStoreIds(),
                'profile_name'   => $this->getProfile()->getName(),
                'profile_time'   => $this->getProfile()->getSendTime()
                
             );
             $form->setValues($_data);
         }
        
        return parent::_prepareForm();
    }
}