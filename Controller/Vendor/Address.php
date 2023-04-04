<?php 
class Controller_Vendor_Address extends Controller_Core_Action
{
	public function gridAction()
	{
		$request = $this->getRequest();
		$vendorId=(int) $request->getParam('vendor_id');
		if(!$vendorId)
		{
		throw new Exception("invalid vendor_id.", 1);
		}
		$modelVendorAddress =Ccc::getModel('VendorAddress_Row');
		$sql = "SELECT * FROM `vendor_address` WHERE `vendor_id` = {$vendorId}";
		$vendorAddress =$modelVendorAddress->fetchRow($sql);
		$this->getView()->setTemplete('vendor/address/grid.phtml')->setData(['vendorAddress'=>$vendorAddress]);
		$this->render();
	}
}

?>