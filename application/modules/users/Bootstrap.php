<?php
/**
 *
 * @author Steve Rhoades
 * @see http://www.stephenrhoades.com
 */
class Users_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initModule()
	{
		printf("%s Bootstrap got called <br />", __CLASS__);
	}	
	
	public function _initPluginBrokers()
	{
		$front = Zend_Controller_Front::getInstance();
		$front->registerPlugin(new Users_Plugin_PluginOne());
	}	
}