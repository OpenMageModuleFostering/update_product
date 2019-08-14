<?php

class Update_Product_Adminhtml_ProductController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('product/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('product/product')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('product_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('product/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('product/adminhtml_product_edit'))
				->_addLeft($this->getLayout()->createBlock('product/adminhtml_product_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('product')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
	
	public function updateAction(){
		
		$data = $this->getRequest()->getPost();
		$model = Mage::getModel('product/product');		
			$model->setData($data)->setId($this->getRequest()->getParam('id'));
				
				$model->save();
				
		$collection = Mage::getModel('product/product')->getCollection();
	
		foreach($collection as $row) {
	    		
		if (($row['Pfund'] !='') AND ($row['Euro']!=''))
		{
		//echo "<b>Pfund :</b>".$row['Pfund']."<br>".
		//"<b>Euro :</b>".$row['Euro']."<br>".
		//"<b>Dollar :</b>".$row['USD']."<br>";
		//echo "<br><br>";
		
		//echo"<h3>Pfund</h3>";
		$this->updateProducts('Pfund', $row['Pfund']);
		
		//echo"<h3>Euro</h3>";
		$this->updateProducts('Euro', $row['Euro']);
		
		//echo"<h3>US Dollar</h3>";
		$this->updateProducts('USD', $row['USD']);
		
		//echo"<h3>Australische Dollar</h3>";
		$this->updateProducts('dollar',15); //xchange rate is 1:1 but shipping cost need to be updated
		
		//inform Admin about update
		//informEmail('martin.steudter@konvis.de');
		}
		}
		 Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('product')->__('All Product Price Was Successfully Updated'));
    	 $this->_redirect('*/*/edit/id/1');
		
	}
	
	public function updateProducts($currency, $xchange_rate) {

	//load product collection
	$product_collection  = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*')
		->addFieldToFilter(
			'currency',
			array(
				'eq' => Mage::getResourceModel('catalog/product')
							->getAttribute('currency')
							->getSource()
							->getOptionId($currency)
			)
		);
														   
	$num = 0;
	foreach($product_collection as $product) {
			
		$num++;
		
		//print_r($product->getData());
		
		//echo"<b>Aktualisierte Produkte:</b><br>";
		
		//attributes for user information
		$id = $product->getResource()->getAttribute('entity_id')->getFrontend()->getValue($product);
		$name = $product->getResource()->getAttribute('name')->getFrontend()->getValue($product);
		
		//attributes for price calculation
		$basis_price = $product->getResource()->getAttribute('basis_preis')->getFrontend()->getValue($product);
		$shippingcost = $product->getResource()->getAttribute('versandkosten')->getFrontend()->getValue($product);	
		//echo $shippingcost;exit;
		//calulation of new price
		
		
		$new_price = $basis_price / $xchange_rate + $shippingcost;
		
		
		//information for user
	//	$output = $num." - #".$id."|".$name." - ".$basis_price." => ".$new_price. " AUD";
		
	/*	if ($shippingcost != '')
		{
			$output = $output." (inkl. Versand ". round($shippingcost,2)." AUD)"; 
		}  */
	//	echo $output."<br>";
	   
	   //updating product price
	   $product->setPrice($new_price)
			   ->save(); 	
		
	}  

  
	//echo "<b>Aktualisierter Produkte </b>".$num; //update Products
	//echo "<br><br>";
}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			
	  			
			$model = Mage::getModel('product/product');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('product')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('product')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('product/product');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $productIds = $this->getRequest()->getParam('product');
        if(!is_array($productIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($productIds as $productId) {
                    $product = Mage::getModel('product/product')->load($productId);
                    $product->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($productIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $productIds = $this->getRequest()->getParam('product');
        if(!is_array($productIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($productIds as $productId) {
                    $product = Mage::getSingleton('product/product')
                        ->load($productId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($productIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'product.csv';
        $content    = $this->getLayout()->createBlock('product/adminhtml_product_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'product.xml';
        $content    = $this->getLayout()->createBlock('product/adminhtml_product_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}