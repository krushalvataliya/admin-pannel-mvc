<?php 

class Controller_PaymentMethod extends Controller_Core_Action
{
	public function gridAction()
	{
		$modelPaymentMethod =Ccc::getModel('PaymentMethod_Row');
		$sql = "SELECT * FROM `payment_methods`";
		$paymentMethods =$modelPaymentMethod->fetchAll($sql);
		$this->getView()->setTemplete('payment_method/grid.phtml')->setData(['paymentMethods'=>$paymentMethods]);
		$this->render();
	}
	
	public function addAction()
	{
		$paymentMethods = Ccc::getModel('PaymentMethod_Row');
		$this->getView()->setTemplete('payment_method/edit.phtml')->setData(['PaymentMethod'=>$paymentMethods]);
		$this->render();
	}

	public function editAction()
	{
		try
		{
			$request = $this->getRequest();
			$id=(int)$request->getParam('payment_method_id');
			if(!isset($id))
			{
			  throw new Exception("invalid payment_method id.", 1);
			}
			$modelPaymentMethod = Ccc::getModel('PaymentMethod_Row');
			$PaymentMethod =$modelPaymentMethod->load($id);
			$this->getView()->setTemplete('payment_method/edit.phtml')->setData(['PaymentMethod'=>$PaymentMethod]);
			$this->render();
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage($e->getMessage() ,  Model_Core_Message::FAILURE);
			return $this->redirect('grid', null, null, true);
		}
	}

	public function deleteAction()
	{
		try
		{
			$request = $this->getRequest();
			if(!$id = (int) $request->getParam('payment_method_id'))
			{
				throw new Exception("invalid id.", 1);
			}
			$modelPaymentMethod = Ccc::getModel('PaymentMethod_Row');
			$delete = $modelPaymentMethod->load($id)->delete();
			if(!$delete)
			{
				throw new Exception("payment_method not deleted.", 1);
			}
			$this->getMessage()->addMessage('payment_method deleted successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('payment_method not deleted.',  Model_Core_Message::FAILURE);
		}

		return $this->redirect('grid', null, null, true);
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

			$postData = $request->getPost('payment_method');
			if(!$postData)
			{
				throw new Exception("no data posted.", 1);
			}

			$modelRowPaymentMethod = Ccc::getModel('PaymentMethod_Row');
			if($id = (int)$request->getParam('payment_method_id'))
			{
				$PaymentMethodRow = $modelRowPaymentMethod->load($id);
				if (!$PaymentMethodRow)
				{
					throw new Exception("invalid id", 1);
				}
				$postData['payment_method_id'] =$PaymentMethodRow->payment_method_id ;
			}

			$modelRowPaymentMethod->setData($postData);
			$result =$modelRowPaymentMethod->save();
			if(!$result)
			{
				throw new Exception("unable to save payment_method", 1);
				
			}
			$this->getMessage()->addMessage('payment_method saved successfully.',  Model_Core_Message::SUCCESS);

		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('payment_method not saved.',  Model_Core_Message::FAILURE);
		}

		return $this->redirect('grid', null, null, true);

	}

   
}

?>