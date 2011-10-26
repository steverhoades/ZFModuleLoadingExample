<?php
/**
 *
 * @author Steve Rhoades
 * @see http://www.stephenrhoades.com
 */
class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initModule()
	{
		printf("%s Bootstrap got called<br />", __CLASS__);
	}
		
	public function _initPluginBrokers()
	{
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new Default_Plugin_PluginOne());
		$front->registerPlugin(new Default_Plugin_ModuleLoader());
	}
}