<?php
class Cp_Dog_Model_Trigger extends Mage_Core_Model_Abstract {
	protected function _construct() 
	{
		$this->_init('dog/trigger');
	}
	public function getLastRun() 
	{
		$runs	= $this->getRunCollection();
		$runs	->getSelect()->limit(1)->order('run_time DESC');
//		die($runs->getSelect());
		return $runs->getFirstItem();
	}
	public function getRunCollection() {
		$runCollection	= Mage::getModel('dog/trigger_run')->getCollection()->addFieldToFilter('trigger_id', $this->getId());
		return $runCollection;
	}
	public function getTriggerable() {
		return unserialize($this->getClassData());
	}
	public function getTemplate() {
		if($this->getTemplateId() != null)
			return Mage::getModel('dog/trigger_template')->load($this->getTemplateId());
		return null;
	}
}
