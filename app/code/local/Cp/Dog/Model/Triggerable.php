<?php
Class Cp_Dog_Model_Triggerable extends Varien_Object {
	public function populateData() {
		$data	= $this->_getSentData();
		$this->setStoreIds($data['store_ids']);
		$this->setContacts($data['contacts']);
		return $this;
	}
	protected function _getSentData() {
		return Mage::registry(Cp_Dog_Model_Trigger_Service::DOG_TRIGGER_CREATE_DATA);
	
	}
	
}
