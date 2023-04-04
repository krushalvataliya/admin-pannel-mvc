<?php 

class Controller_Salesman extends Controller_Core_Action
{
	public function gridAction()
	{
		$modelSalesman =Ccc::getModel('Salesman_Row');
		$sql = "SELECT * FROM `salesmen`";
		$salesmen =$modelSalesman->fetchall($sql);	
		$this->getView()->setTemplete('salesman/grid.phtml')->setData(['salesmen'=>$salesmen]);
		$this->render();
	}
	public function addAction()
	{
		$salesman = Ccc::getModel('Salesman_Row');
		$salesmanAddress = Ccc::getModel('salesmanAddress_Row');
		$this->getView()->setTemplete('salesman/edit.phtml')->setData(['salesman'=>$salesman,'salesmanAddress'=>$salesmanAddress]);
		$this->render();
	}
	public function editAction()
	{
		try
		{
			$request = $this->getRequest();
			$id=(int)$request->getParam('salesman_id');

			if(!$id)
			{
			throw new Exception("salesman id not found", 1);
			}
			$modelSalesman =Ccc::getModel('Salesman_Row');
			$salesman =$modelSalesman->load($id);
			if(!$salesman)
			{
				throw new Exception("invalid salesman id.", 1);
			}
			$modelSalesmanAddress =Ccc::getModel('salesmanAddress_Row');
			$sql = "SELECT * FROM `salesman_address` WHERE `salesman_id`= {$id}";
			$salesmanAddress =$modelSalesmanAddress->fetchRow($sql);
			if(!$salesmanAddress)
			{
				throw new Exception("Address not found.", 1);
			}
			$this->getView()->setTemplete('salesman/edit.phtml')->setData(['salesman'=>$salesman,'salesmanAddress'=>$salesmanAddress]);
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
			$salesman = $request->getPost('salesman');
			$salesmanAddress = $request->getPost('address');

			$modelSalesman =Ccc::getModel('Salesman_Row');
			$modelSalesmanAddress =Ccc::getModel('salesmanAddress_Row');

			if($id=(int)$request->getParam('salesman_id'))
			{
				$salesmanRow = $modelSalesman->load($id);
				if(!$salesmanRow)
				{
					throw new Exception("invalid id.", 1);
				}
				$salesman['salesman_id'] = $id;
				$sql = "SELECT * FROM `salesman_address` WHERE `salesman_id`= {$id}";
				$salesmanAddressRow = $modelSalesmanAddress->fetchRow($sql);
				if(!$salesmanAddressRow)
				{
					throw new Exception("invalid salesman address.", 1);
				}
				$salesmanAddress['address_id'] = $salesmanAddressRow->address_id;
			}

			$insertsalesman = $modelSalesman->setData($salesman)->save();
			if (!$insertsalesman) {
				throw new Exception("salesman not inserted.", 1);
			}

			if(!$id)
			{
			$salesmanAddress['salesman_id'] = $insertsalesman;
			}
			$insertsalesmanAddress = $modelSalesmanAddress->setData($salesmanAddress)->save();
			if (!$insertsalesman) {
				throw new Exception("salesman Address not inserted.", 1);
			}

			$this->getMessage()->addMessage('salesman saved successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('salesman not saved.',  Model_Core_Message::FAILURE);
		}
		
		return $this->redirect('grid', null, null, true);
	}

	public function deleteAction()
	{
		try
		{
			$request = $this->getRequest();
			$id = (int)$request->getParam('salesman_id');
			if(!$id)
			{
				throw new Exception("invalid salesman ID", 1);
			}

			$modelSalesman =Ccc::getModel('Salesman_Row');
			$delete = $modelSalesman->load($id)->delete();
			if(!$delete)
			{
				throw new Exception("salesman not deleted", 1);
			}
			$this->getMessage()->addMessage('salesman deleted successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('salesman not deleted.',  Model_Core_Message::FAILURE);
		}

		return $this->redirect('grid', null, null, true);
	}
}

?>