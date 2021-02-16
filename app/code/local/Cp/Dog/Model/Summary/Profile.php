<?php
Class Cp_Dog_Model_Summary_Profile extends Mage_Core_Model_Abstract
    implements Cp_Dog_Model_Triggerable_Interface
{
    const DEFAULT_SUMMARY_PROFILE_EMAIL  = "cp_dog_summary_profile";
    
    protected $_serialized = array('store_ids', 'contacts', 'reports');
    
    protected $_reportCollection = null;
    
    protected function _construct()
    {
        $this->_init("dog/summary_profile");
    }
        
    /** Required by Triggerable **/
    public function run(Cp_Dog_Model_Trigger $trigger)
    {
         $origZone = date_default_timezone_get();
         date_default_timezone_set(Mage::getStoreConfig("general/locale/timezone"));               
         $this->load($this->getId());//Ugh, i know, it sucks. Must reload the class when its ran -__-
         $this->send();
         date_default_timezone_set($origZone);   
         return "Successfully ran summary profile id: " . $this->getId();
    }
    
    protected function _afterLoad()
    {
        $this->_unserialize();
    }
    
    protected function _beforeSave()
    {
        $this->_serialize();       
    }
    
    protected function _beforeDelete()
    {
        if ($this->getTriggerId())
            Mage::getModel("dog/trigger")->load($this->getTriggerId())->delete();
    }
    
    protected function _afterSave()
    {
        $this->_unserialize();
        $this->_checkTrigger();
        
    }
    
    protected function _serialize()
    {
        foreach($this->_serialized as $_key)
        {
            if ($this->getData($_key) && is_array($this->getData($_key)))
                $this->setData($_key, @serialize($this->getData($_key)));
            else
                $this->setData($_key, @serialize(array()));
        }            
    }
    
    protected function _unserialize()
    {
        foreach($this->_serialized as $_key)
        {
            if ($this->getData($_key) && strlen($this->getData($_key)))
                $this->setData($_key, @unserialize($this->getData($_key)));
            else
                $this->setData($_key, array());
        }            
    }
    
    protected function _checkTrigger()
    {
        if ($this->getTriggerId() && ($this->getData('send_time') == $this->getOrigData('send_time')))
        {
           return;
        }
        
        $trigger = Mage::getModel("dog/trigger");
        
        /** We need to update the run time **/
        if ($this->getTriggerId())
        {
            $trigger->load($this->getTriggerId())
                    ->setJobSchedule($this->_getCronExpr())
                    ->save();
                    ;
        }
        
        /** We need to enter a new trigger into the database **/
        else
        {
           $id = $trigger->setJobSchedule($this->_getCronExpr())
                 #   ->setClass("dog/summary_profile")
                    ->setCode("summary_email_" . $this->getId())
                    ->setName("Summary Email: " . $this->getName())
                    ->setEnabled(1)
                    ->setTemplateId(NULL) //NO template for summary emails, they have an entire admin section devoted to them
                    ->setClassData(@serialize($this))
                    ->save()
                    ->getId()
                    ;
           $this->setData("trigger_id", $id)->save(); //Careful, this could start an endless loop...
        }
        return $this;
    }
    
    protected function _getCronExpr()
    {
        $_time      = floatval($this->getData('send_time'));
        $_hours     = floor($_time / 3600);
        $_minutes   = ($_time - ($_hours * 3600))/60;
                
        $_expr = "{$_minutes} {$_hours} * * *";
        return $_expr;
    }
    
    public function refreshStats()
    {
        $aliases = array(
            'sales'       => 'sales/report_order',
         #   'tax'         => 'tax/report_tax',
         #   'shipping'    => 'sales/report_shipping',
            'invoiced'    => 'sales/report_invoiced',
            'refunded'    => 'sales/report_refunded',
            'coupons'     => 'salesrule/report_rule',
        #    'bestsellers' => 'sales/report_bestsellers',
        #    'viewed'      => 'reports/report_product_viewed',
        );
                
        $collectionsNames = $aliases;
        $currentDate = Mage::app()->getLocale()->date();
        $date = $currentDate->subHour(25);
        foreach ($collectionsNames as $collectionName) {
            Mage::getResourceModel($collectionName)->aggregate($date);
        }        
        return $this;
    }
    
    public function send()
    {
      #  $this->refreshStats();
        Mage::log('DOG Profile Send running');
        $_reports_html = array();
        /** Run each report, grabbing its phtml **/
        foreach($this->getReportCollection() as $report)
        {
            $_reports_html[] = $report->getHtml();
            #continue;
        }
        /** Merge all html **/
        $_reports_html = implode("<br/>", $_reports_html);
        
        $_template = Cp_Dog_Helper_Data::DEFAULT_SUMMARY_PROFILE_EMAIL;
        
        $_template_params = array(
            "reports_html" => $_reports_html,
            "profile_name" => ucwords($this->getName()),
            "stores_html" =>  $this->getStoresList()
        );
        
        /** Now let's send the email **/
        $mailer = Mage::getModel('core/email_template_mailer');
        
        $emailInfo = Mage::getModel('core/email_info');
        foreach($this->getContacts() as $c_id)
        {
            $contact = Mage::getModel("dog/contact")->load($c_id);
            $emailInfo->addTo($contact->getEmail(), $contact->getName());
        }
        #echo "AFTER SUPPOSED ERROR"; exit;

        foreach(Mage::helper("dog")->getMasterContacts() as $c)
        {
            $emailInfo->addTo($c);
        } 
        
        $mailer->addEmailInfo($emailInfo);
        // Set all required params and send emails
        $mailer->setSender(array(
            "name"  => Mage::helper("dog")->__(Mage::helper("dog")->getConfig("email_from_name")), 
            "email" => Mage::helper("dog")->__(Mage::helper("dog")->getConfig("email_from_address")) 
        ));
        $mailer->setStoreId(0);
        $mailer->setTemplateId($_template);
        $mailer->setTemplateParams($_template_params);
        $_ret_value = $mailer->send(); if ($_ret_value) $this->setData("email_sent", 1)->save();
        return $_ret_value;        
    }
    
    public function getReportCollection()
    {
        if (is_null(($this->_reportCollection)))
        {          
            /** Get a collection of objects based on the codes **/
            $this->_reportCollection = Mage::helper("dog/summary")->getReports($this->getReports());
            $_utcOffset              = Mage::getModel("core/date")->getGmtOffset();   
            $this->_reportCollection
                ->setDataToAll("store_ids", $this->getStoreIds())
                ->setDataToAll("today", date("m-d-Y"))
                ->setDataToAll("begin_today", date("Y-m-d H:i:s", strtotime(date("Y-m-d 00:00:00")) - $_utcOffset))
                ->setDataToAll("this_week", date("m-d-Y", strtotime(date("Y-m-d 00:00:00")) - 7 * 24 * 3600 ) . ' to ' . date("m-d-Y"))
                ->setDataToAll("begin_week", date("Y-m-d H:i:s", time() - $_utcOffset - (7 * 24 * 3600) ))
                ->setDataToAll("this_month", date("F Y"))
                ->setDataToAll("begin_month", date("Y-m-d H:i:s", strtotime(date("Y-m-01 00:00:00")) - $_utcOffset))
                ->setDataToAll("this_year", date("Y"))
                ->setDataToAll("begin_year", date("Y-m-d H:i:s", strtotime(date("Y-01-01 00:00:00")) - $_utcOffset))
                ->setDataToAll("now", date("Y-m-d H:i:s", time() - $_utcOffset))
                ;
        }
        
        return $this->_reportCollection;
    }
    
    public function getStoresList()
    {
        $_html = '<ul>'; 
        foreach($this->getStoreIds() as $id)
        {
            $store = Mage::getModel("core/store")->load($id);
            $_html .= '<li><span style="font-size:13pt; font-weight:bold; color:#3366cc;">' . $store->getData('name') . '</span></li>';
        }
        $_html .= '</ul>';   
        
        return $_html;     
    }
    
    
 /***** REQUIRED FOR INTERFACE *******/
    public function canCreate()
    {
        
    }
    public function populateData()
    {
        
    }  
}    
