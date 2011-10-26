<?php
/**
 *
 * @author Steve Rhoades
 * @see http://www.stephenrhoades.com
 */
class Application_Service_Module
{
	/**
	 * Instance of Application_Resource_Modules
	 * 
	 * @var Application_Resource_Modules
	 */
	protected static $_moduleLoader;
    
    /**
     * Application_Resource_Modules will add itself to this
     * class for further module lazy loading
     * 
     * @see Application_Resource_Module::init()
     * @param Application_Resource_Modules $loader
     */
    public static function setModuleLoader($loader)
    {
    	self::$_moduleLoader = $loader;
    }
    
    /**
     * Returns an instance of Application_Resource_Modules
     * 
     * @return Application_Resource_Modules
     */
    public static function getModuleLoader()
    {
    	return self::$_moduleLoader;
    }
    
    /**
     * This method will return the module name that is currently in the
     * request object.
     * 
     * @return String Module name of the current request
     */
    public static function getModuleNameFromRequest()
    {
		$front = Zend_Controller_Front::getInstance();
    	$module = $front->getRequest()->getModuleName();
    	
    	return $module;
    }
}
