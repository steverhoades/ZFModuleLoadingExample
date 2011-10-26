<?php
/**
 *
 * @author Steve Rhoades
 * @see http://www.stephenrhoades.com
 */
class Admin_Plugin_PluginOne extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		printf("Plugin: %s got called<br />", __CLASS__);
	}
}