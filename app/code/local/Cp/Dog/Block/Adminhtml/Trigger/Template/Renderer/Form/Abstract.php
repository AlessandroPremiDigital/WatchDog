<?php
Abstract Class Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Form_Abstract extends Mage_Adminhtml_Block_Widget_Form {
	protected $_form	= null;
	protected $_trigger	= null;
	protected $_action	= null;
	
	protected function _initForm() {
		$this->_form = new Varien_Data_Form(array(
		    'id'        => 'edit_form',
		    'action'    => $this->_action,
		    'method'    => 'post',
		    'enctype'   => 'multipart/form-data'
		));
		
		$state		= 'new';

		$templateId	= $this->getRequest()->getParam('template_id');
		$this->_form->addField('template_id', 'hidden', array(
			'name' => 'template_id',
			'value'=> $templateId
		));

		if(Mage::registry('current_trigger') !== null) {
			$this->_trigger = Mage::registry('current_trigger');

			if ($this->_trigger->getId()) {
			    $this->_form->addField('trigger_id', 'hidden', array(
				'name' 	=> 'trigger_id',
				'value'	=> $this->_trigger->getId(),
			    ));
			    $state	= 'edit';
			}
		} else {
			$this->_trigger	= null;
		}
		
		$this->_form->addField('state', 'hidden', array(
			'name' => 'state',
			'value'=> $state
		));
	}
	
	protected function _addCronSelect(&$fieldset) {
		if(Mage::registry('current_trigger') instanceof Cp_Dog_Model_Trigger)
			$intervalValue = Mage::registry('current_trigger')->getData('job_schedule');
		else
			$intervalValue = '';
		
		 $interval = $fieldset->addField('interval', 'select',
		    array(
			'name'  => 'interval',
			'class' => 'interval',
			'label' => Mage::helper('customer')->__('Interval'),
			'title' => Mage::helper('customer')->__('Interval'),
			'note'  => Mage::helper('customer')->__('Interval on which to email.'),
			'required' => true,
			'values' => array(
				'*/5 * * * *' 	=> 'Every Five Minutes',
				'*/30 * * * *' 	=> 'Every Half Hour',
				'0 * * * *' 	=> 'Every Hour',
				'0 */2 * * *'	=> 'Every Two Hours',
				'0 */5 * * *'	=> 'Every Five Hours',
				'0 */12 * * *'	=> 'Every Twelve Hours',
				'0 0 * * *'	=> 'Every Day',
				'0 0 0 * *'	=> 'Every Week',
		    ),
			'value' => $intervalValue,
		));
		
		
	}
}
