<?php

class Update_Product_Model_Mysql4_Product extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the product_id refers to the key field in your database table.
        $this->_init('product/product', 'product_id');
    }
}