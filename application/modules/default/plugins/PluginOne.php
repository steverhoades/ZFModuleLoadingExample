<?php
/**
 *
 * @author Steve Rhoades
 * @see http://www.stephenrhoades.com
 */
class Default_Plugin_PluginOne extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		if(strcmp($request->getModuleName(), "default") === 0) {
			printf("Plugin: %s got called<br />", __CLASS__);
		}
	}
}