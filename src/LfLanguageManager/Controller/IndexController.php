<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace LfLanguageManager\Controller;

use Zend\View\Model\ViewModel;
use LfLibrary\AbstractControllerClass;
use LfLibrary\PreviousUrl;
use LfLibrary\Utils;


/**
 *
 * @author ludo
 *        
 */
class IndexController extends AbstractControllerClass
{
    /**
     *
     * @method indexAction
     */
    public function indexAction()
    { 
        $languageService = $this->getServiceLocator()->get('LfLanguageManagerService');
        $languages = $languageService->getLanguagesList();

        //get config and set language selector view
        $config = $this->getServiceLocator()->get('Config');
        $this->view->setTemplate( $config["languages_settings"]["default_language_view_template"] );
        
        $this->view->setVariable('languages', $languages);
        return $this->view;
    }
}
