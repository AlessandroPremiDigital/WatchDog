<?php
Class Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Sales_Orderrate_Form
    extends Cp_Dog_Block_Adminhtml_Trigger_Template_Renderer_Form_Abstract
{
    protected function _construct() {
	$this->_action	= $this->getUrl('dog/adminhtml_customerparadigm_dog_trigger_edit'); //Into Abstract?	
	return parent::_construct();
    }
    protected function _prepareForm()
    {
	$this->_initForm();
	if(Mage::registry('current_trigger') instanceof Cp_Dog_Model_Trigger && count(Mage::registry('current_trigger')->getData()) != 0 ) {
		$ordersCreatedValue	= Mage::registry('current_trigger')->getTriggerable()->getOrdersCreated();
		$plusminusValue	= Mage::registry('current_trigger')->getTriggerable()->getPlusminus();
	} else {
		$ordersCreatedValue 	= 10;
		$plusminusValue		= '';
	}
	/**
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->_action,
            'method'    => 'post',
            'enctype'   => 'multipart/form-data'
        ));
	if(Mage::registry('current_trigger') !== null) {
		$this->_trigger = Mage::registry('current_trigger');

		if ($this->_trigger->getId()) {
		    $form->addField('trigger_id', 'hidden', array(
			'name' => 'trigger_id',
		    ));
		    $form->setValues($customer->getData());
		}
	} else
		$this->_trigger	= null;
	*/
	 $whenFieldset 	= $this->_form->addFieldset('when_fieldset', array('legend'=>Mage::helper('customer')->__('Notify me when')));
	 $name = $whenFieldset->addField('orders_created', 'text',
            array(
                'name'  => 'orders_created',
                'class' => 'ordersCreated',
                'label' => Mage::helper('customer')->__('Order(s) Created'),
                'title' => Mage::helper('customer')->__('Order(s) Created'),
                'note'  => Mage::helper('customer')->__('How many orders were created in given timespan.'),
                'required' => true,
		'value'	=> $ordersCreatedValue,
            )
        );
	$plusminus = $whenFieldset->addField('plusminus','select',
	    array(
		'name'	=> 'plusminus',
                'label' => Mage::helper('customer')->__('Orders(s) create is greater than or less than.'),
                'title' => Mage::helper('customer')->__('Orders(s) create is greater than or less than'),
                'note'  => Mage::helper('customer')->__('Notify me when order are greater than or less than the value specified above.'),
                'required' => true,
                'class'    => 'plusminus',
                
		'values' => array(
					'>=' 	=> 'Greater than or equal to',
					'>' 	=> 'Greater than',
					'<'	=> 'Less than',
					'<='	=> 'Less than or equal to',
			),
		'value' 	=> $plusminusValue,
	   )
	);
	$this->_addCronSelect($whenFieldset);
	
	$this->_form->setUseContainer(false);
        $this->setForm($this->_form);
        return parent::_prepareForm();
    }
     public function getFormHtml() {
         $js =<<<JS
<script type="text/javascript">
    trig.makeSentence();
</script>         
JS;

	return 
	'<div id="orderrate">'.parent::getFormHtml().' <br/> <div style="font-size:16px;"><b>Alert me when:</b> <span id="sentence"> </span></div>'.$js . '</div>';
     }
}
