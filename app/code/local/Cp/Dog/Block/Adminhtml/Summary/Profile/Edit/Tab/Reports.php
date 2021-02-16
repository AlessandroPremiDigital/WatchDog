<?php
Class Cp_Dog_Block_Adminhtml_Summary_Profile_Edit_Tab_Reports
    extends Cp_Dog_Block_Adminhtml_Summary_Profile_Edit_Tab_Abstract
{
    public function __construct()
    {
        parent::__construct();
       // $this->setTemplate('cp/dog/summary/profile/edit/reports/form.phtml');
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        Varien_Data_Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock('dog/adminhtml_summary_profile_edit_report_form_renderer_fieldset_element')
        );
    }
    protected function _prepareForm()
    {
        $_isNew = $this->isNew();
        
        $_args = array();
        
        if (!$_isNew)
        {
            $_args = array("profile_id" => $this->getRequest()->getParam("profile_id"));
        }
        
        $form = new Varien_Data_Form(array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/*', $_args),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
        ));
 
      #  $form->setUseContainer(true); !Important that this is commented out
 
        $this->setForm($form);
        
        $fieldset = $form->addFieldset('reports', array(
            'legend' =>Mage::helper("dog")->__('Summary Reports')
        ));
        
        $data = array();
        
        $fieldset->addType('report','Cp_Dog_Block_Adminhtml_Form_Element_Report');
        
        foreach(Mage::helper("dog/summary")->getReports() as $report)
        {
            $args = array(
                'label' => Mage::helper('dog')->__($report->getTitle()),
                'description' => $report->getDescriptionHtml(),
                'name'        => 'reports[]',
                'value'       => $report->getCode()
            );
            if ($this->isNew())
            {
                $args["checked"] = true;
            }            
            elseif (!$this->isNew())
            {
                $model    = $this->getProfile();
                $reports  = $model->getData('reports');
                if (in_array($report->getCode(), $reports)) 
                    $args["checked"] = true;
            }              
            $fieldset->addField('report_'.$report->getCode(), 'report', $args);
            
        }
        
      #  $form->setValues($data);
                
        return parent::_prepareForm();
    }    
}    