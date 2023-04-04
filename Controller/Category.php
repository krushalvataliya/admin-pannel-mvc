<?php
class Controller_Category extends Controller_Core_Action
{
	public function gridAction()
	{
		$modelRowCetegory = Ccc::getModel('Cetegory_Row');
		$sql = "SELECT * FROM `category` WHERE category_id > 1 ORDER BY `parent_id` ASC;";
		$modelCetegory = Ccc::getModel('Cetegory');
		$categories = $modelRowCetegory->fetchAll($sql);	
		$categoriesArray = $modelCetegory->fetchAll($sql);
		$this->getView()->setTemplete('category/grid.phtml')->setData(['categories'=>$categories,'categoriesData'=>$categoriesArray]);
		$this->render();
	}
	public function addAction()
	{
		$modelCetegory = Ccc::getModel('Cetegory');
		$modelCetegoryRow = Ccc::getModel('Cetegory_Row');
		$sql = "SELECT * FROM `category` ORDER BY `category`.`parent_id` ASC";
		$categoriesData = $modelCetegoryRow->fetchAll($sql);
		if(!$categoriesData)
		{
			throw new Exception("data not found.", 1);
		}
		$this->getView()->setTemplete('category/edit.phtml')->setData(['category'=>$modelCetegoryRow,'categoriesData'=>$categoriesData]);
		$this->render();
	}
	public function editAction()
	{
		try 
		{
			$request = $this->getRequest();
			$id=(int)$request->getParam('category_id');
			if(!isset($id))
			{
			throw new Exception("invalid cetegory id.", 1);
			}
			$category = Ccc::getModel('Cetegory_Row')->load($id);
			echo $sql = "SELECT * FROM `category` WHERE `path` NOT LIKE '{$category->path}=%' AND `path` NOT LIKE '{$category->path}';";
			$categories = Ccc::getModel('Cetegory_Row')->fetchAll($sql);
			if(!$categories)
			{
				throw new Exception("data not found.", 1);
			}
			$this->getView()->setTemplete('category/edit.phtml')->setData(['category'=>$category,'categoriesData'=>$categories]);
			$this->render();
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage($e->getMessage(),  Model_Core_Message::FAILURE);
			return $this->redirect('grid', null, null, true);
		}
	}
	
	public function deleteAction()
	{
		try
		{
			$request = $this->getRequest();
			$id =(int)$request->getParam('category_id');
			if(!$id)
			{
				throw new Exception("invalid category ID.", 1);
			}
			$modelCetegory = Ccc::getModel('Cetegory');;
			$delete = $modelCetegory->delete($id);
			if(!$delete)
			{
				throw new Exception("cetegory not deleted.", 1);
			}
			$this->getMessage()->addMessage('category deleted successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('category not deleted.',  Model_Core_Message::FAILURE);
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

			$postData = $request->getPost('category');	
			if(!$postData)
			{
				throw new Exception("no data posted.", 1);
			}
			$modelRowCategory = Ccc::getModel('Cetegory_Row');
			if($id = (int)$request->getParam('category_id'))
			{
				$categoryRow = $modelRowCategory->load($id);
				if (!$categoryRow)
				{
					throw new Exception("invalid id", 1);
				}
				$postData['category_id'] =$categoryRow->category_id ;
				$postData['path'] =$categoryRow->path ;
			}
			$category = $modelRowCategory->setData($postData);
			$result =$modelRowCategory->save();

			if(!$result)
			{
				throw new Exception("unable to save Category", 1);
			}
			if(!$id)
			{
			$category->category_id = $result;
			}
			$category->updatePath();
			$this->getMessage()->addMessage('Category saved successfully.',  Model_Core_Message::SUCCESS);
		}
		catch (Exception $e)
		{
			$this->getMessage()->addMessage('category not saved.'.$e->getMessage(),  Model_Core_Message::FAILURE);
		}

		return $this->redirect('grid', null, null, true);
	}
	
}
