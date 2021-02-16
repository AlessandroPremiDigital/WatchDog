<?php
class Cp_Dog_Resource_Trigger_Run extends Mage_Core_Model_Resource_Db_Abstract {
	protected function _construct() {
		$this->_init('dog/trigger_run', 'id');
	}
}
