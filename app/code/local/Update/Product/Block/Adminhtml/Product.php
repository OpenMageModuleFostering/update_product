<?php
class Update_Product_Block_Adminhtml_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_product';
    $this->_blockGroup = 'product';
    $this->_headerText = Mage::helper('product')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('product')->__('Add Item');
    parent::__construct();
	$collection=Mage::getModel('product/product')->getCollection();
	if(count($collection) == 0)
	{
		//$this->_removeButton('add');
	}
	else{
		
		$this->_removeButton('add');
	}
	
	
  }
}