<?php

class Update_Product_Block_Adminhtml_Product_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'product';
        $this->_controller = 'adminhtml_product';
        
        $this->_updateButton('save', 'label', Mage::helper('product')->__('Save Item'));
      //  $this->_updateButton('delete', 'label', Mage::helper('product')->__('Delete Item'));
     	 $this->_removeButton('delete');
		 $this->_removeButton('reset');
		 $this->_removeButton('back');
		
      /*  $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100); */

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('product_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'product_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'product_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('product_data') && Mage::registry('product_data')->getId() ) {
            return Mage::helper('product')->__("Fill Form To Update Product Price");
        } else {
            return Mage::helper('product')->__('Add Item');
        }
    }
}