<?php
/**
 *
 * @author Steve Rhoades
 * @see http://www.stephenrhoades.com
 */
class Default_Plugin_ModuleLoader extends Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
    	if($request->getModuleName() == "default") {
    		return;
    	}
    	
		$moduleBootstrap = Application_Service_Module::getModuleLoader();
		$moduleBootstrap->bootstrapModule($request->getModuleName());
    }
}