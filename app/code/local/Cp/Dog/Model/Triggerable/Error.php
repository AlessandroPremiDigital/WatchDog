<?php
class Cp_Dog_Model_Triggerable_Error
	extends Cp_Dog_Model_Triggerable
	implements Cp_Dog_Model_Triggerable_Interface {

	public function run(Cp_Dog_Model_Trigger $trigger) {
		$this->_trigger		= $trigger;
		$lastRun 			= $this->_trigger->getLastRun();

		//if($lastRun->getRunTime() == null) // First run! Make a entry but do not run
		//	return;
		
		$this->_sendAlert();
		return $this;
	}
	protected function _sendAlert() {
		$hasErrors	= true;
		$block		= Mage::getSingleton('core/layout')->createBlock('dog/triggerable_order')->setTemplate('dog/triggerable/error.phtml');
		$errors		= $this->_getErrorsFromLastRun();
//		die($errors->getSelect());
//		die($errors->getSelect());
		$block		->setErrors($errors);
//		$emailTitle	= ($errors->count() != 0 ? 'Error Alert (New Errors)' : 'Error Alert (No Errors)');
		$hasErrors	= ($errors->count() != 0 ? true : false);
		$emailTitle	= ($hasErrors == true ? 'Error Alert (New Errors)' : 'Error Alert (No Errors)');
		$emailTitle	= $this->_trigger->getName().' | '.$emailTitle;
		if($hasErrors) {
			$helper		= Mage::helper('dog')->sendAlertEmail($block->toHtml(),$emailTitle,$this->getContacts(),'cp_dog_triggerable_error');
        } else {
			//$helper		= Mage::helper('dog')->sendAlertEmail($block->toHtml(),$emailTitle,$this->getContacts(),'cp_dog_triggerable_error_none');
        }
		
	}
    protected function _getErrorsFromLastRun() {
        $lastRun        = $this->_trigger->getLastRun();
        $errors         = Mage::getModel('dog/error')
                    ->getCollection()
                    ->addFieldToFilter('main_table.date', array(
                        'from'      => $lastRun->getRunTime(),
                        'to'        => date("Y-m-d H:i:s"),
                        'datetime'  => true
                    ))
                    ->addFieldToFilter('main_table.store_id', array('in' => $this->getStoreIds()));
        //Add the level filter
        $whereIn    = array();
        $gt         = array();
        if($this->getLevelTwo() === true)
        {
            $whereIn[] = 2;
        }
        if($this->getLevelThree() === true)
        {
            $whereIn[] = 3;
            $whereIn[] = 4;
            $whereIn[] = 5;
            $whereIn[] = 6;
            $whereIn[] = 10;
        }
        $errors         ->addFieldToFilter('level', array('in' => $whereIn));
//die($errors->getSelect());
        return $errors;
    }
	public function canCreate() {
		//$data	= Mage::registry(Cp_Dog_Model_Trigger_Service::DOG_TRIGGER_CREATE_DATA);
		$data	= $this->_getSentData();
		if((!isset($data['level_2'])) && (!isset($data['level_3'])))
			Mage::throwException('Please select a error level.');
		
	
	}
	public function populateData() {
//		$data	= Mage::registry(Cp_Dog_Model_Trigger_Service::DOG_TRIGGER_CREATE_DATA);
		$data	= $this->_getSentData();
		if(isset($data['level_2']))
			$this->setLevelTwo(true);
		else
			$this->setLevelTwo(false);
			
		if(isset($data['level_3']))
			$this->setLevelThree(true);
		else
			$this->setLevelThree(false);
		
		return parent::populateData();
	}
}
