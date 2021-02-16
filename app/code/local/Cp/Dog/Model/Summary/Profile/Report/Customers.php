<?php
Class Cp_Dog_Model_Summary_Profile_Report_Customers
    extends Cp_Dog_Model_Summary_Profile_Report_Abstract
{
    public function getTitle()
    {
        return Mage::helper("dog")->__('Customers');
    }
    
    public function getDescriptionHtml()
    {
$html =<<<HTML
<div class="report-description">
<p class="abbr">The <strong>Customers</strong> report gives you detailed information about customer acquisition and retainment</p>

<p>Daily Information: </p>
<ul>
 <li>Total # of New Customers</li>
 <li>Total # of Customers</li>
</ul>
<p>Information Regarding Active Customers: </p>
<ul>
 <li># Active Today</li>
 <li># Active this week</li>
 <li># Active this month</li>
 <li># Active this year</li>
</ul>
</div>
HTML;
      
        return $html;
    }
    
    protected function _loadData()
    {
        $collection = Mage::getModel("customer/customer")->getCollection()
                        ->addAttributeToFilter("created_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()))
                        ->addAttributeToFilter("store_id", array("in" => $this->getStoreIds()))
                        ; 
        $collection     ->getSelect()
                        ->reset(Zend_Db_Select::COLUMNS)
                        ->columns('COUNT(*) as new_customers')
                        ;
       $_new = $collection->getFirstItem();
       $_new_customers = $_new->getNewCustomers();
       
        $collection = Mage::getModel("customer/customer")->getCollection()
                         ->addAttributeToFilter("store_id", array("in" => $this->getStoreIds()))
                    #    ->addAttributeToFilter("created_at", array("from" => $this->getBeginToday(), "to" => date("Y-m-d 23:59:59")))
                        ; 
        $collection     ->getSelect()
                        ->reset(Zend_Db_Select::COLUMNS)
                        ->columns('COUNT(*) as all_customers')
                        ;   
                        
       $_all = $collection->getFirstItem();
       $_all_customers = $_all->getAllCustomers();        
       
       $this->setData("total_new_customers", $_new_customers);
       $this->setData("total_customers", $_all_customers);

       $todayCollection = $this->_getBaseCollection();
       $todayCollection->addFieldToFilter("login_at", array("from" => $this->getBeginToday(), "to" => $this->getNow()));
       $this->setData("active_today", ($_t = $todayCollection->getFirstItem()->getData("active_customers")) ? $_t : 0);
       
       $weekCollection = $this->_getBaseCollection();
       $weekCollection->addFieldToFilter("login_at", array("from" => $this->getBeginWeek(), "to" => $this->getNow()));
       $this->setData("active_week", ($_t = $weekCollection->getFirstItem()->getData("active_customers")) ? $_t : 0);       
       
       $monthCollection = $this->_getBaseCollection();
       $monthCollection->addFieldToFilter("login_at", array("from" => $this->getBeginMonth(), "to" => $this->getNow()));
       $this->setData("active_month", ($_t = $monthCollection->getFirstItem()->getData("active_customers")) ? $_t : 0);           
       
       $yearCollection = $this->_getBaseCollection();
       $yearCollection->addFieldToFilter("login_at", array("from" => $this->getBeginYear(), "to" => $this->getNow()));
       $this->setData("active_year", ($_t = $yearCollection->getFirstItem()->getData("active_customers")) ? $_t : 0);                 

       return $this;           
    }
    
    protected function _getBaseCollection()
    {
       $collection = Mage::getResourceModel("dog/customerlog_collection");
       $collection     ->addFieldToFilter("store_id", array("in" => $this->getStoreIds()))
                       ->getSelect()
                       ->reset(Zend_Db_Select::COLUMNS)
                       ->columns('COUNT(DISTINCT customer_id) as active_customers')       
                     ;
       return $collection;        
    }
}    