<?php
class Cp_Dog_Model_Triggerable_Order 
    extends Cp_Dog_Model_Triggerable
    implements Cp_Dog_Model_Triggerable_Interface {
    
    protected $_trigger     = null;
    public function run(Cp_Dog_Model_Trigger $trigger) {
        $this->_trigger     = $trigger;
        $lastRun        = $this->_trigger->getLastRun();

        //if($lastRun->getRunTime() == null) // First run! Make a entry but do not run
            //return;
        //die('blah');
        $ordersFromLastRun  = $this->_getOrdersFromLastRun();
        // Add logic from me to SQL (should be in a resource)
        $ordersFromLastRun
                    ->getSelect()
                    ->from(null,'COUNT(*) as num_orders')
                #   ->group('main_table.entity_id')
                   ->having('COUNT(*) '.$this->getPlusminus().' '.$this->getOrdersCreated());
                    ;  
//	var_dump($ordersFromLastRun->getFirstItem()->getNumOrders());
//	die($ordersFromLastRun->getSelect());             
        if($ordersFromLastRun->getFirstItem()->getNumOrders() == null) { //We did not have the needed amount of orders
            return;
        }
        else{
            $this->_sendAlert();
        }
        
        return $this;
    }
    protected function _sendAlert() {
        $block      = Mage::getSingleton('core/layout')->createBlock('dog/triggerable_order')->setTemplate('dog/triggerable/order.phtml');
        $orders     = $this->_getOrdersFromLastRun();
        $orders     ->getSelect()->join(
                    array('grid' => $orders->getTable('sales/order_grid')),
                    'main_table.increment_id = grid.increment_id',
                    array('shipping_name','billing_name')
                );
//      die($orders->getSelect());
        $block      ->setOrders($orders);
        $block      ->setTriggerable($this);
        
        $helper     = Mage::helper('dog')->sendAlertEmail($block->toHtml(),''.$this->_trigger->getName(), $this->getContacts(),'cp_dog_triggerable_orderrate');
    }
    protected function _getOrdersFromLastRun() {
        $lastRun    = $this->_trigger->getLastRun();
        $orders     = Mage::getModel('sales/order')
                ->getCollection()
                ->addFieldToFilter('main_table.created_at', array(
                    'from'      => $lastRun->getRunTime(),
                    'to'        => date("Y-m-d H:i:s"),
                    'datetime'  => true
            
                ))
                ->addFieldToFilter('main_table.store_id', array('in' => $this->getStoreIds()));
        return $orders;
        
        
    }
    public function canCreate() {
        //Throw exceptions here
    }
    public function populateData() {
        //$data = Mage::registry(Cp_Dog_Model_Trigger_Service::DOG_TRIGGER_CREATE_DATA);
        $data   = $this->_getSentData();
        $this->setData(array(
            'orders_created'    => $data['orders_created'],
            'plusminus'     => $data['plusminus'],
        ));
        
        return parent::populateData();
    }
    

}
