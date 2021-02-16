<?php
class Cp_Dog_CronController extends Mage_Core_Controller_Front_Action {
	protected $_helper	= null;
	public function runAction() {
		try {
			$this->_helper	= Mage::helper('dog')->dispatchEvent('RUN_CRON',$this->getRequest()->getParams());
		} catch(Exception $ex) {
			die($ex->getMessage());
		
		}
        
        /** Do nothing else here (blank page) -- The external monitoring will pick up on the 200 STATUS **/
	}
}
