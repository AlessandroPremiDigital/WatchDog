<?php
class Cp_Dog_Block_Adminhtml_Trigger_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('dog_trigger');
		$this->setUseAjax(false);
		$this->setDefaultSort('id');
		$this->setSaveParametersInSession(true);
	}
	protected function _prepareCollection() {
		$collection	= Mage::getModel('dog/trigger')->getCollection()->addGridFilters();
		$collection	->getSelect()
				->join(
					array('template' => $collection->getTable('dog/trigger_template')),
					'main_table.template_id = template.template_id',
					array('friendly_name')
				);
		//die($collection->getSelect());
		$this->setCollection($collection);
		
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns() {
		$this->addColumn('id', array(
			'header'    => Mage::helper('dog')->__('ID'),
			'index'     => 'trigger_id',
			'filter_index' => 'main_table.trigger_id',
			'type'  => 'number',
		));
		$this->addColumn('Name', array(
			'header'    => Mage::helper('dog')->__('Name'),
			'filter_index' => 'main_table.name',
			'index'     => 'name'
		));
		$this->addColumn('type', array(
			'header'    => Mage::helper('dog')->__('Type'),
			'filter_index' => 'template.friendly_name',
			'index'     => 'friendly_name'
		));
		$this->addColumn('contact_email', array(
			'header'    => Mage::helper('dog')->__('Contact Email'),
			'index'     => 'trigger_id',
			'filter'    => false,
			'sortable'    => false,
			'renderer'  => 'Cp_Dog_Block_Adminhtml_Trigger_Grid_Column_Renderer_Contacts',
		));
		
		
		
	}
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'trigger_id' => $row->getTriggerId()
        ));
    }        
}
