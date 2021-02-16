<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_List_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setCollection(Mage::getResourceModel("dog/summary_profile_collection"));
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn('profile_id', array(
            'header'     => "Id",
            'index'      => 'profile_id',
            'searchable' => true,
            'sortable'   => true,
            'type'       => 'number'
        ));
        $this->addColumn('profile_name', array(
            'header'     => "Profile Name",
            'index'      => 'name',
            'searchable' => true,
            'sortable'   => true
        ));   
        $this->addColumn('stores', array(
            'header'     => "Profile Stores",
            'index'      => 'store_ids',
            'renderer'   => 'Cp_Dog_Block_Adminhtml_Summary_Profile_List_Grid_Renderer_Stores',
            'searchable' => false,
            'filter'     => false,
            'sortable'   => false
        ));           
        $this->addColumn('contacts', array(
            'header'     => "Profile Contacts",
            'index'      => 'contacts',
            'renderer'   => 'Cp_Dog_Block_Adminhtml_Summary_Profile_List_Grid_Renderer_Contacts',
            'searchable' => false,
            'filter'     => false,
            'sortable'   => false
        ));       
        $this->addColumn('send_time', array(
            'header'     => "Profile Send Time",
            'index'      => 'send_time',
            //'renderer'   => 'Cp_Dog_Block_Adminhtml_Summary_Profile_List_Grid_Renderer_Sendtime',
            'searchable' => true,
            'sortable'   => true,
            'type'       => 'options',
            'options'    => Mage::helper("dog/summary")->getSendTimeOptions()
        ));   
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'profile_id' => $row->getProfileId()
        ));
    }    
}    