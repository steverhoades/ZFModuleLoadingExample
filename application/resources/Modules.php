<?php
/**
 * 
 * @author Steve Rhoades 
 * @see http://www.stephenrhoades.com
 */
class Application_Resource_Modules extends Zend_Application_Resource_Modules
{
    /**
     * @var Zend_Controller_Front
     */
    protected $_front;
    
    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;
    
    /**
     * @var Array
     */
    protected $_modules;
    
    public function init()
    {
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('frontcontroller');
        $this->_front = $bootstrap->getResource('frontcontroller');
        
        /*
         * Uncommend the lines below to enable caching
         */
        //$bootstrap->bootstrap('cachemanager');
        //$this->_cache = $bootstrap->getResource('cachemanager')->getCache('default');
        
        /*
         * Get the list of modules
         */
        $this->_modules = $this->_front->getControllerDirectory();
        
        /*
         * Load the module configurations
         */
        $this->_loadModuleConfigs();
		
        /*
         * Bootstrap the Default module on every request
         */
        $this->bootstrapModule('default');
        
        /*
         * Register instance of this class with the application module service.  We
         * need to retain this class due to the fact that the Zend_Application_Resource_ResourceAbstract class
         * will create a new instance everytime a Zend_Application_Bootstrap_BootstrapAbstract::getPluginResource($type)
         * instead of returning the first instantiated class. 
         */
 		Application_Service_Module::setModuleLoader($this);
                
        return $this->_bootstraps;
    }
    
    /**
     * Bootstrap Modules on demand.
     * 
     * @param String $module	Name of the module to bootstrap
     * @throws Zend_Application_Resource_Exception
     */
    public function bootstrapModule($module)
    {
    	if(false === array_key_exists($module,$this->_bootstraps)) {
			$moduleDirectory = $this->_modules[strtolower($module)];
			$bootstrapClass = $this->_formatModuleName($module) . '_Bootstrap';
			if (!class_exists($bootstrapClass, false)) {
				$bootstrapPath  = dirname($moduleDirectory) . '/Bootstrap.php';
				if (file_exists($bootstrapPath)) {
					$eMsgTpl = 'Bootstrap file found for module "%s" but bootstrap class "%s" not found';
					include_once $bootstrapPath;
					if (('Default' != $module)
						&& !class_exists($bootstrapClass, false)
					) {
						throw new Zend_Application_Resource_Exception(sprintf(
							$eMsgTpl, $module, $bootstrapClass
						));
					} elseif ('Default' == $module) {
						if (!class_exists($bootstrapClass, false)) {
							$bootstrapClass = 'Bootstrap';
							if (!class_exists($bootstrapClass, false)) {
								throw new Zend_Application_Resource_Exception(sprintf(
									$eMsgTpl, $module, $bootstrapClass
								));
							}
						}
					}
				}
			}
			$moduleBootstrap = new $bootstrapClass($this->getBootstrap());
			$moduleBootstrap->bootstrap();
	 
			$this->_bootstraps[$module] = $moduleBootstrap;
    	}    	
    }
    
	/**
     * Load all module.ini files in the module's configs directory
     *
     * @return void
     */
    final private function _loadModuleConfigs()
    {
    	$ds = DIRECTORY_SEPARATOR;
    	$modules = array_keys($this->_modules);
    	
    	/*
    	 * Uncomment the lines at 106, 145, 146 to enable caching
    	 * 
    	 */
    	//if(false === ($appOptions = $this->_cache->load('module_configurations'))) {
			$appOptions = $this->getBootstrap()->getOptions();
	        foreach ($modules as $module) {
	            $filename  = $this->_front->getModuleDirectory($module) . $ds . 'configs'. $ds . 'module.ini';
	            if(file_exists($filename)) {
	            	$cfg = new Zend_Config_Ini($filename, $this->getBootstrap()
	            			->getEnvironment());
					$options = $cfg->toArray();
					if (empty($options)) {
						continue;
 					}
 
					if (array_key_exists($module, $appOptions) && is_array($appOptions[$module])) {
						foreach ($options as $key => $value) {
							if (array_key_exists($key, $appOptions[$module]) && is_array($appOptions[$module][$key])) {
								$appOptions[$module][$key] = array_merge($appOptions[$module][$key], $value);
							} else {
								$appOptions[$module][$key] = $value;
							}
						}
					} else {
						$appOptions[$module] = $options;
					}
				}
	        }
	        //$this->_cache->save($appOptions, 'module_configurations', array(), 9200);	        
    	//}

    	/*
    	 * This block checks the appOptions for routes defined in the module.ini and will
    	 * create a Zend_Config object for each to configure routing properly for Rest and
    	 * other custom routes per module.
    	 */
    	$router = $this->_front->getRouter();    	
		foreach($appOptions as $module => $options) {
			if(in_array($module, $modules) && isset($options['routes'])) {
				$routeName = sprintf("routes_%s", $module);
				$config = new Zend_Config(array($routeName => $options['routes']));
				$router->addConfig($config, $routeName);
			}    		
		}
    	    	
    	$this->getBootstrap()->setOptions($appOptions);
    }
}
