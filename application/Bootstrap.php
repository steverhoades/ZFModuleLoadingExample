<?php
/**
 * This class is responsible for setting up the Zend environment and making sure all the default skeleton is set up properly
 * see the constructor for the steps it takes.  
 * 
 * @author Steve Rhoades 
 * @see http://www.stephenrhoades.com
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initTimezone ()
    {
        date_default_timezone_set('America/Los_Angeles');
    }
}