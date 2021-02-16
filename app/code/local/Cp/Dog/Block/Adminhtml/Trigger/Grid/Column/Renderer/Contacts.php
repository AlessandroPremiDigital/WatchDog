<?php
class Cp_Dog_Block_Adminhtml_Trigger_Grid_Column_Renderer_Contacts extends  Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	public function render(Varien_Object $row) {
		$triggerable	= Mage::getModel('dog/trigger')->load($row->getTriggerId())->getTriggerable();
		$contactIds	= $triggerable->getContacts();

		if($contactIds == null || 
		(is_array($contactIds) && count($contactIds) == 0))
			return 'None';

		$contactEmail	= array();

		foreach($contactIds as $contactId) {	
			$contact 	= Mage::getModel('dog/contact')->load($contactId);
			$contactEmail[]	= $contact->getEmail();
		}
		
		return implode(',',$contactEmail);
		
		
		
	}
}
