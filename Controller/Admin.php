<?php 

class Controller_Admin extends Controller_Core_Action
{

	public function gridAction()
	{	
		$modelProduct = Ccc::getModel('admin_Row');
		$sql = "SELECT * FROM `admins`";
		$admins =$modelProduct->fetchAll($sql);
		$this->getView()->setTemplete('admin/grid.phtml')->setData($admins);
		$this->render();
	}

	public function addAction()
	{
		$modelRowAdmin = Ccc::getModel('admin_Row');
		$this->getView()->setTemplete('admin/edit.phtml')->setData($modelRowAdmin);;
		$this->render();
	}

	public function editAction()
	{
		try 
		{
			$request = $this->getRequest();
			$adminId=(int)$request->getParam('admin_id');
			if($adminId == null)
			{
			throw new Exception("invalid admin Id.", 1);
			}
			$modelRowAdmin = Ccc::getModel('admin_Row');
			$sql = "SELECT * FROM `admins` WHERE `admin_id`= {$adminId}";
			$admin =$modelRowAdmin->load($adminId);
			if(!$admin)
			{
				throw new Exception("data not found", 1);
			}
			$this->getView()->setTemplete('admin/edit.phtml')->setData($admin);
			$this->render();
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage($e->getMessage(),  Model_Core_Message::FAILURE);
			return $this->redirect('grid', null, null, true);
		}

	}

	public function saveAction()
	{
		try
		{	
			$request = $this->getRequest();
			if (!$request->isPost()) {
				throw new Exception("invalid Request.", 1);
			}
	
			$postData = $request->getPost('admin');
			
			if(!$postData)
			{
				throw new Exception("no data posted.", 1);
			}
			$modelAdmin = Ccc::getModel('Admin_Row');
			if($id =(int) $request->getParam('admin_id'))
			{
				$adminRow = $modelAdmin->load($id);
				if(!$adminRow)
				{
					throw new Exception("invalid id.", 1);
				}
				$postData['admin_id']=$id;
			}
	
			$modelAdmin->setData($postData);
			$result =$modelAdmin->save();
			if(!$result)
			{
				throw new Exception("Error Processing Request", 1);
			}
			$this->getMessage()->addMessage('admin saved successfully.',  Model_Core_Message::SUCCESS);
			}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('admin not saved.',  Model_Core_Message::FAILURE);
		}
		return $this->redirect('grid', null, null, true);
	
	}

	public function deleteAction()
	{
		try
		{
			$request = $this->getRequest();
			if (!$request->isGet()) {
				throw new Exception("invalid Request.", 1);
			}

			$id =(int) $request->getParam('admin_id');
			$modelAdmin = Ccc::getModel('admin_Row');
			$modelAdmin->load($id);
			$result =$modelAdmin->delete($id);
			if(!$result)
			{
				throw new Exception("Error Processing Request", 1);
			}
			$this->getMessage()->addMessage('admin deleted successfully.',  Model_Core_Message::SUCCESS);
		}
		catch(Exception $e)
		{
			$this->getMessage()->addMessage('admin not deleted.',  Model_Core_Message::FAILURE);
		}

		return $this->redirect('grid', null, null, true);
	}
    
}

?>