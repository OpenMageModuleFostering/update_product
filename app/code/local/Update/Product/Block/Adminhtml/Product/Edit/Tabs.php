<?php

class Update_Product_Block_Adminhtml_Product_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('product_tabs');
      $this->setDestElementId('edit_form');
     // $this->setTitle(Mage::helper('product')->__('Update ALL Product Price'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('product')->__('Update ALL Product Price'),
          'title'     => Mage::helper('product')->__('Update ALL Product Price'),
          'content'   => $this->getLayout()->createBlock('product/adminhtml_product_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}