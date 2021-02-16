<?php
 
class Cp_Dog_Block_Adminhtml_Summary_Profile_Edit extends Mage_Adminhtml_Block_Widget
{ 
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('cp/dog/summary/profile/edit.phtml');
        $this->setId('profile_edit_form');
    }
    
    protected function _prepareLayout()
    {
        $this->setChild('back_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Back'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store', 0))).'\')',
                    'class' => 'back'
                ))
        );       
        $this->setChild('reset_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Reset'),
                    'onclick'   => 'setLocation(\''.$this->getUrl('*/*/*', array('_current'=>true)).'\')'
                ))
        );        
        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('catalog')->__('Save'),
                    'onclick'   => 'profileEditForm.submit()',
                    'class' => 'save'
                ))
        );
        if ($this->getRequest()->getParam("profile_id")):
            $this->setChild('delete_button' ,$this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label'     => Mage::helper('catalog')->__('Delete'),
                        'onclick'   => 'confirmSetLocation(\''.Mage::helper('catalog')->__('Are you sure?').'\', \''.$this->getDeleteUrl().'\')',
                        'class'  => 'delete'
                    ))
            );
        endif;                    
    }

    public function getSaveUrl()
    {
        $args = array();
        if (!$this->isNew())
        {
            $args["profile_id"] = $this->getProfileId();
        }
        return $this->getUrl("*/*/*", $args);
    }
    
    public function getDeleteUrl()
    {
        $args = array();
        if (!$this->isNew())
        {
            $args["profile_id"] = $this->getProfileId();
            $args["delete"]     = 1;
        }
        return $this->getUrl("*/*/*", $args);        
    }

    public function isNew()
    {
        return $_isNew = !($this->getRequest()->getParam("profile_id") && intval($this->getRequest()->getParam("profile_id")));
    }

    public function getProfileId()
    {
        return $this->getRequest()->getParam('profile_id');
    }
 
    public function getHeader()
    {
        if ($this->isNew())
        {
            $_text = Mage::helper("dog")->__('Create New Daily Summary Email');
        }
        else
        {
            $_text = Mage::helper("dog")->__('Edit Daily Summary Email (Id: ' . $this->getProfileId() . ')');
        }
        return $_text;
    }
 
}