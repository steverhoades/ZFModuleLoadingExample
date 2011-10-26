<?php
/**
 * This controller is to demonstrate route usage for module on demand loading
 * within Zend Framework.
 * 
 * @see configs/module.ini
 * 
 * @author Steve Rhoades 
 * @see http://www.stephenrhoades.com
 */
class Users_CommentsController extends Zend_Controller_Action
{
	public function reviewAction()
	{
		$this->view->id = $this->_request->getParam('id');
	}
}