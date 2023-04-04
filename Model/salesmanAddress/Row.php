<?php 

class Model_SalesmanAddress_Row extends Model_Core_Table_Row
{
    function __construct()
	{
		parent::__construct();
		$this->setTableClass('Model_SalesmanAddress');
	}

	public function getStatus()
	{
		if($this->status)
		{
			return $this->status; 
		}
		return Model_SalesmanAddress::STATUS_DEFAULT;
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
}

?>