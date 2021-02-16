<?php
class Cp_Dog_Model_Trigger_Service extends Varien_Object {
	const DOG_TRIGGER_CREATE_DATA	= 'trigger_create_data';
	public function create(array $data) {
		$edit		= false;
		if(isset($data['trigger_id']) )
			$edit 	= true;
		if($edit) {
			$trigger	= Mage::getModel('dog/trigger')->load($data['trigger_id']);
		}
		else
			$trigger	= Mage::getModel('dog/trigger'); 

		$template	= Mage::getModel('dog/trigger_template')->load($data['template_id']);
			
		$className	= $template->getClassName();
		if(empty($className))
			Mage::throwException('A template must have a class_name.');
		
		if($edit) {
			$triggerable		= $trigger->getTriggerable();
			$triggerable		->setEdit(true);
		}
		else
			$triggerable		= Mage::getModel($className);

		if(!$triggerable instanceof Cp_Dog_Model_Triggerable_Interface)
			Mage::throwException('All trigger models must impliment Cp_Dog_Model_Triggerable');

		Mage::register(self::DOG_TRIGGER_CREATE_DATA, $data);
		$triggerable->canCreate();
		$triggerable->populateData();
		
		
		$trigger->setTemplateId($template->getId())
			->setName($data['name'])
			->setClassData(serialize($triggerable))
			->setData('job_schedule', $data['interval'])
			->setEnabled(1);
		
		$trigger->save();
        
        if (!$edit)
        {
                $runModel = Mage::getModel("dog/trigger_run");
                $runModel->setTriggerId($trigger->getId())
                         ->setStatus("I")
                         ->setRunNumber(0)
                         ->setMessage("INITIAL DUMMY RUN")
                         ->setRunTime(date("Y-m-d H:i:s"));
                $runModel->save();            
        }
		
	}
}
