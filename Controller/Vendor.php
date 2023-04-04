<?php 

class Controller_Vendor extends Controller_Core_Action
{
	public function gridAction()
	{
		$modelVendor =Ccc::getModel('Vendor_Row');
		$sql = "SELECT * FROM `vendors`";
		$vendors =$modelVendor->fetchall($sql);	
		$this->getView()->setTemplete('vendor/grid.phtml')->setData(['vendors'=>$vendors]);
		$this->render();
	}
	public function addAction()
	{
		$vendor = Ccc::getModel('Vendor_Row');
		$vendorAddress = Ccc::getModel('VendorAddress_Row');
		$this->getView()->setTemplete('vendor/edit.phtml')->setData(['vendor'=>$vendor,'vendorAddress'=>$vendorAddress]);
		$this->render();
	}
	public function editAction()
	{
		try
		{
			$request = $this->getRequest();
			$id=(int)$request->getParam('vendor_id');

			if(!$id)
			{
			throw new Exception("Vendor id not found", 1);
			}
			$modelVendor =Ccc::getModel('Vendor_Row');
			$vendor =$modelVendor->load($id);
			if(!$vendor)
			{
				throw new Exception("invalid vendor id.", 1);
			}
			$modelVendorAddress =Ccc::getModel('VendorAddress_Row');
			$sql = "SELECT * FROM `vendor_address` WHERE `vendor_id`= {$id}";
			$vendorAddress =$modelVendorAddress->fetchRow($sql);
			if(!$vendorAddress)
			{
				throw new Exception("Address not found.", 1);
			}
			$this->getView()->setTemplete('vendor/edit.phtml')->setData(['vendor'=>$vendor,'vendorAddress'=>$vendorAddress]);
			$this->render();	
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage($e->getMessage() ,  Model_Core_Message::FAILURE);
			return $this->redirect('grid', null, null, true);
		}
	}

	public function saveAction()
	{
		try 
		{
			$request = $this->getRequest();
			if (!$request->isPost())
			{
				throw new Exception("invalid Request.", 1);
			}
			$vendor = $request->getPost('vendor');
			$vendorAddress = $request->getPost('address');

			$modelVendor =Ccc::getModel('Vendor_Row');
			$modelVendorAddress =Ccc::getModel('VendorAddress_Row');

			if($id=(int)$request->getParam('vendor_id'))
			{
				$vendorRow = $modelVendor->load($id);
				if(!$vendorRow)
				{
					throw new Exception("invalid id.", 1);
				}
				$vendor['vendor_id'] = $id;
				$sql = "SELECT * FROM `vendor_address` WHERE `vendor_id`= {$id}";
				$vendorAddressRow = $modelVendorAddress->fetchRow($sql);
				if(!$vendorAddressRow)
				{
					throw new Exception("invalid vendor address.", 1);
				}
				$vendorAddress['address_id'] = $vendorAddressRow->address_id;
			}
			$insertvendor = $modelVendor->setData($vendor)->save();
			if (!$insertvendor)
			{
				throw new Exception("vendor not inserted.", 1);
			}

			if(!$id)
			{
			$vendorAddress['vendor_id'] = $insertvendor;
			}
			$insertvendorAddress = $modelVendorAddress->setData($vendorAddress)->save();
			if (!$insertvendor) {
				throw new Exception("vendor Address not inserted.", 1);
			}

			$this->getMessage()->addMessage('vendor saved successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('vendor not saved.',  Model_Core_Message::FAILURE);
		}
		
		return $this->redirect('grid', null, null, true);
	}

	public function deleteAction()
	{
		try
		{
			$request = $this->getRequest();
			$id = (int)$request->getParam('vendor_id');
			if(!$id)
			{
				throw new Exception("invalid vendor ID", 1);
			}

			$modelVendor =Ccc::getModel('Vendor_Row');
			$delete = $modelVendor->load($id)->delete();
			if(!$delete)
			{
				throw new Exception("vendor not deleted", 1);
			}
			$this->getMessage()->addMessage('vendor deleted successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('vendor not deleted.',  Model_Core_Message::FAILURE);
		}

		return $this->redirect('grid', null, null, true);
	}
}
?>