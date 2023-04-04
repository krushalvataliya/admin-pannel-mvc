<?php 

class Model_Cetegory_Row  extends Model_Core_Table_Row
{
    function __construct()
	{
		parent::__construct();
		$this->setTableClass('Model_Cetegory');
	}

	public function getStatus()
	{
		if($this->status)
		{
			return $this->status; 
		}
		return Model_Cetegory::STATUS_DEFAULT;
	}

	public function getStatusText()
	{
		$statuses = $this->getTable()->getStatusOptions();
		if (array_key_exists($this->status, $statuses))
		{
			return $statuses[$this->status];
		}
			return $statuses[ Model_PaymentMethod::STATUS_DEFAULT];
	}

	public function updatePath()
	{
		if(!$this->getId())
		{
			return false;
		}
		$parent = Ccc::getModel('Cetegory_Row')->load($this->parent_id);
		$oldPath = $this->path;
		if(!$parent)
		{
			$this->path = $this->getId(); 
		}
		else
		{
			$this->path =$parent->path.'='.$this->getId(); 
		}
		$this->save();
		$sql = "UPDATE `category`
		SET `path` = REPLACE(`path`,'{$oldPath}=','{$this->path}=')
		WHERE `path` LIKE '{$oldPath}=%';";
		$this->getTable()->getAdapter()->update($sql);
		return $this;
	}

	public function getPathName()
	{
		$pathArry = explode('=', $this->path);
		$sql = "SELECT `category_id`,`name` FROM `category`;";
		$categoryNameArray = $this->getTable()->getAdapter()->fetchPairs($sql);
		foreach ($pathArry as $id2 => &$cetegoryId)
		{
			foreach ($categoryNameArray as $key => $cetegoryName)
			{
				if($cetegoryId == $key)
				{
					$cetegoryId = $cetegoryName ;
				}
			}
		}
		return implode('=>', $pathArry);
	}
}

?>