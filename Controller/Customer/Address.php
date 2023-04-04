<?php 
class Controller_Customer_Address extends Controller_Core_Action
{
	public function gridAction()
	{
		$request = $this->getRequest();
		$customerId=(int) $request->getParam('customer_id');
		if(!$customerId)
		{
		throw new Exception("invalid customer_id.", 1);
		}
		$modelCustomerAddress =Ccc::getModel('CustomerAddress_Row');
		$sql = "SELECT * FROM `customer_address` WHERE `customer_id` = {$customerId}";
		$customerAddress =$modelCustomerAddress->fetchRow($sql);
		$this->getView()->setTemplete('customer/address/grid.phtml')->setData(['customerAddress'=>$customerAddress]);
		$this->render();
	}
}
?>