<?php 

class Model_PaymentMethod_Row extends Model_Core_Table_Row
{
	public function __construct()
	{
		parent::__construct();
		$this->setTableClass('Model_PaymentMethod');
	}

	public function getStatus()
	{
		if($this->status)
		{
			return $this->status;
		}
		return Model_PaymentMethod::STATUS_DEFAULT;
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