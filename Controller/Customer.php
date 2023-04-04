<?php 

class Controller_Customer extends Controller_Core_Action
{
	public function gridAction()
	{
		$modelCustomer =Ccc::getModel('Customer_Row');
		$sql = "SELECT * FROM `customers`";
		$customers =$modelCustomer->fetchall($sql);	
		$this->getView()->setTemplete('customer/grid.phtml')->setData(['customers'=>$customers]);
		$this->render();
	}
	public function addAction()
	{
		$customer = Ccc::getModel('Customer_Row');
		$customerAddress = Ccc::getModel('CustomerAddress_Row');
		$this->getView()->setTemplete('customer/edit.phtml')->setData(['customer'=>$customer,'customerAddress'=>$customerAddress]);
		$this->render();
	}
	public function editAction()
	{
		try
		{
			$request = $this->getRequest();
			$id=(int)$request->getParam('customer_id');

			if(!$id)
			{
			throw new Exception("Customer id not found", 1);
			}
			$modelCustomer =Ccc::getModel('Customer_Row');
			$customer =$modelCustomer->load($id);
			if(!$customer)
			{
				throw new Exception("invalid customer id.", 1);
			}
			$modelCustomerAddress =Ccc::getModel('CustomerAddress_Row');
			$sql = "SELECT * FROM `customer_address` WHERE `customer_id`= {$id}";
			$customerAddress =$modelCustomerAddress->fetchRow($sql);
			if(!$customerAddress)
			{
				throw new Exception("Address not found.", 1);
			}
			$this->getView()->setTemplete('customer/edit.phtml')->setData(['customer'=>$customer,'customerAddress'=>$customerAddress]);
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
			$customer = $request->getPost('customer');
			$customerAddress = $request->getPost('address');

			$modelCustomer =Ccc::getModel('Customer_Row');
			$modelCustomerAddress =Ccc::getModel('CustomerAddress_Row');

			if($id=(int)$request->getParam('customer_id'))
			{
				$customerRow = $modelCustomer->load($id);
				if(!$customerRow)
				{
					throw new Exception("invalid id.", 1);
				}
				$customer['customer_id'] = $id;
				$sql = "SELECT * FROM `customer_address` WHERE `customer_id`= {$id}";
				$customerAddressRow = $modelCustomerAddress->fetchRow($sql);
				if(!$customerAddressRow)
				{
					throw new Exception("invalid Customer address.", 1);
				}
				$customerAddress['address_id'] = $customerAddressRow->address_id;
			}

			$insertCustomer = $modelCustomer->setData($customer)->save();
			if (!$insertCustomer) {
				throw new Exception("Customer not inserted.", 1);
			}

			if(!$id)
			{
			$customerAddress['customer_id'] = $insertCustomer;
			}
			$insertCustomerAddress = $modelCustomerAddress->setData($customerAddress)->save();
			if (!$insertCustomer) {
				throw new Exception("Customer Address not inserted.", 1);
			}

			$this->getMessage()->addMessage('Customer saved successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('Customer not saved.',  Model_Core_Message::FAILURE);
		}
		
		return $this->redirect('grid', null, null, true);
	}

	public function deleteAction()
	{
		try
		{
			$request = $this->getRequest();
			$id = (int)$request->getParam('customer_id');
			if(!$id)
			{
				throw new Exception("invalid customer ID", 1);
			}
			$modelCustomer =Ccc::getModel('Customer_Row');
			$delete = $modelCustomer->load($id)->delete();
			if(!$delete)
			{
				throw new Exception("Customer not deleted", 1);
			}
			$this->getMessage()->addMessage('Customer deleted successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('Customer not deleted.',  Model_Core_Message::FAILURE);
		}

		return $this->redirect('grid', null, null, true);
	}
}

?>