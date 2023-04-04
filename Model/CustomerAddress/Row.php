<?php 

class Model_CustomerAddress_Row extends Model_Core_Table_Row
{
    function __construct()
	{
		parent::__construct();
		$this->setTableClass('Model_CustomerAddress');
	}

	public function getStatus()
	{
		if($this->status)
		{
			return $this->status; 
		}
		return Model_CustomerAddress::STATUS_DEFAULT;
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