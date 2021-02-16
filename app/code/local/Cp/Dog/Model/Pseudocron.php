<?php
Class Cp_Dog_Model_Pseudocron
{
    /**
     * Default event handler for the pseudocron job scheduler. Loads all triggerable jobs from the db and matches their job
     * schedule against the current timestamp. If a match is found, and there are no previous entries for the given run number
     * it will run the triggerable model
     * @param Varien_Object $observer
     * @return void
     */
    public function dispatch()
    {
        // we need to use: summary_profile::send

        /** Force this to run as the default config's timezone **/
        Mage::app()->setCurrentStore(0);
        date_default_timezone_set("UTC");             
        //$_utc_timestamp = $observer->getTimestamp();
        //$_run_number    = $observer->getRunNumber();
        $_run_number = rand(1, 9999);
       $timezone = date_default_timezone_get();
       date_default_timezone_set($timezone);

       $now = Mage::getModel('core/date')->timestamp(time());
       $_utc_timestamp = date('Y-m-d H:i:s');
  
        //if ( $_utc_timestamp == strtotime(date("Y-m-d 00:00:00")))
        //{
        //    Mage::dispatchEvent("CP_DOG_ERROR_CLEAN", array());
        //}
        /** 
         * This code doesn't need to be here, as the incoming timestamp 
         * is already utc, and we want it to be converted and based on the local time 
         * **/
                #date_default_timezone_set("UTC"); //Important for correct date conversion from the external server
                #$date       = Mage::app()->getLocale()->date($_utc_timestamp;
                
                /** Invert the timestamp difference...ugh -__- magento code **/
                #$timestamp  = $date->get(Zend_Date::TIMESTAMP) - $date->get(Zend_Date::TIMEZONE_SECS);
        
        //Mage::log('DOG Pseudocron begin');


        foreach(Mage::getResourceModel("dog/trigger_collection")->addFieldToFilter("enabled", 1) as $trigger)
        {   

           
            // check with Magento Cron if this tigger is ready to run.
            $schedule = Mage::getModel("cron/schedule");
            $schedule->setCronExpr(trim($trigger->getJobSchedule()));
            $match    =$schedule->trySchedule($_utc_timestamp);
	        $message  = '';

            //Mage::log('DOG Pseudocron schedule: '.$trigger->getJobSchedule());

            if ($match)
            {
                

                /** This block of code is used to check for duplicate runs **/
                $dup = Mage::getModel("dog/trigger_run")
                    ->getCollection()
                    ->addFieldToFilter("trigger_id", $trigger->getId())
                    ->addFieldToFilter("run_number", $_run_number)
                    ;
                if ($dup->count()) continue;
                /** End duplicate check **/                
                //$triggerable = Mage::getModel(trim($trigger->getClass()));
                $triggerable    = unserialize($trigger->getClassData());
                
                 //Mage::log('DOG Pseudocron running'.get_class($triggerable));
                try
                {
                    /** Guarantees we will have the "run(Varien_Object $classData)" method available **/
                    if (!($triggerable Instanceof Cp_Dog_Model_Triggerable_Interface))
                    {
                        Mage::throwException("Class stored in dog trigger table is not an instance of the triggerable interface: " . get_class($triggerable));
                    }
                                       
                    /** Run it! Additional exceptions may be thrown here **/
                    
                    $triggerable->run($trigger);
            
                    $status  = "S";
                }
                catch (Mage_Core_Exception $e)
                {
                    $status  = "E";
                    $message = $e->getMessage(); 
                }
                
                $runModel = Mage::getModel("dog/trigger_run");
                $runModel->setTriggerId($trigger->getId())
                         ->setStatus($status)
                         ->setRunNumber($_run_number)
                         ->setMessage($message)
                         ->setRunTime(date("Y-m-d H:i:s"));
                $runModel->save();
            }      
        }
     
    }
}
