<?php

class Update_Product_Block_Adminhtml_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('productGrid');
      $this->setDefaultSort('product_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('product/product')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('product_id', array(
          'header'    => Mage::helper('product')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'product_id',
      ));

      
	$this->addColumn('Pfund', array(
          'header'    => Mage::helper('product')->__('Pfund'),
          'align'     =>'left',
          'index'     => 'Pfund',
      ));
	  
	  $this->addColumn('Euro', array(
          'header'    => Mage::helper('product')->__('Euro'),
          'align'     =>'left',
          'index'     => 'Euro',
      ));

	 $this->addColumn('USD', array(
          'header'    => Mage::helper('product')->__('USD'),
          'align'     =>'left',
          'index'     => 'USD',
      ));
	
	
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('product')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('product')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('product')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('product')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('product_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('product')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('product')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('product/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('product')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('product')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}