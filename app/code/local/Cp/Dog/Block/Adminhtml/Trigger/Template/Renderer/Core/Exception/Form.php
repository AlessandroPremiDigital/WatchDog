<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Core_Exception_Form
    extends Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Form_Abstract
{
    protected function _construct() {
	$this->_action	= $this->getUrl('dog/adminhtml_customerparadigm_dog_trigger_edit'); //Into Abstract?	
	return parent::_construct();
    }
    protected function _prepareForm()
    {
		if(Mage::registry('current_trigger') instanceof Cp_Dog_Model_Trigger) {
			$triggerable	= Mage::registry('current_trigger')->getTriggerable();
			$level_2_val	= ($triggerable->getLevelTwo() !== null ? $triggerable->getLevelTwo(): false);
			$level_3_val	= ($triggerable->getLevelThree() !== null ? $triggerable->getLevelThree(): false);
			
		}
		else {
			$level_2_val	= false;
			$level_3_val	= false;
		}
	$this->_initForm();
	 $whenFieldset 	= $this->_form->addFieldset('when_fieldset', array('legend'=>Mage::helper('customer')->__('Notify me when')));
	 $level2 = $whenFieldset->addField('level_2', 'checkbox',
            array(
                'name'  => 'level_2',
                'label' => Mage::helper('dog')->__('Alert me about Critical Errors'),
                'title' => Mage::helper('dog')->__('Alert me about Critical Errors'),
                'note'  => Mage::helper('dog')->__('Critical are complete show stoppers, and mean the user could be seeing a raw PHP error..'),
		'checked' => $level_2_val,
            )
        );
	 $level3 = $whenFieldset->addField('level_3', 'checkbox',
            array(
                'name'  => 'level_3',
                'label' => Mage::helper('dog')->__('Alert me about General Errors'),
                'title' => Mage::helper('dog')->__('Alert me about General Errors'),
                'note'  => Mage::helper('dog')->__('General errors may not be show stoppers, however they may be visable to the user depending on you configuration.'),
		'checked' => $level_3_val,
            )
        );
	$this->_addCronSelect($whenFieldset);
	
	$this->_form->setUseContainer(false);
        $this->setForm($this->_form);
        return parent::_prepareForm();
    }
}
