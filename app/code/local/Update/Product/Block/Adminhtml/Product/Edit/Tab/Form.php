<?php

class Update_Product_Block_Adminhtml_Product_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('product_form', array('legend'=>Mage::helper('product')->__('Update ALL Product Price')));
     
      $fieldset->addField('Pfund', 'text', array(
          'label'     => Mage::helper('product')->__('Pfund'),
          'name'      => 'Pfund',
      ));
	  
	  $fieldset->addField('Euro', 'text', array(
          'label'     => Mage::helper('product')->__('Euro'),
          'name'      => 'Euro',
      ));
	  
	   $fieldset->addField('USD', 'text', array(
          'label'     => Mage::helper('product')->__('USD'),         
          'name'      => 'USD',
      ));
	 
      if ( Mage::getSingleton('adminhtml/session')->getProductData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getProductData());
          Mage::getSingleton('adminhtml/session')->setProductData(null);
      } elseif ( Mage::registry('product_data') ) {
          $form->setValues(Mage::registry('product_data')->getData());
      }
      return parent::_prepareForm();
  }
}