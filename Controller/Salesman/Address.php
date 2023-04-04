<?php 
class Controller_Salesman_Address extends Controller_Core_Action
{
	public function gridAction()
	{
		$request = $this->getRequest();
		$salesmanId=(int) $request->getParam('salesman_id');
		if(!$salesmanId)
		{
		throw new Exception("invalid salesman_id.", 1);
		}
		$modelSalesmanAddress =Ccc::getModel('SalesmanAddress_Row');
		$sql = "SELECT * FROM `salesman_address` WHERE `salesman_id` = {$salesmanId}";
		$salesmanAddress =$modelSalesmanAddress->fetchRow($sql);
		$this->getView()->setTemplete('salesman/address/grid.phtml')->setData(['salesmanAddress'=>$salesmanAddress]);
		$this->render();
	}
}

?>