<?php 
namespace LfLanguageManager\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LanguageHelper extends AbstractHelper implements ServiceLocatorAwareInterface  
{
    
    protected $languageService;
    
     /** 
     * Set the service locator. 
     * 
     * @param ServiceLocatorInterface $serviceLocator 
     * @return CustomHelper 
     */  
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)  
    {  
        $this->serviceLocator = $serviceLocator;  
        return $this;  
    }  
    /** 
     * Get the service locator. 
     * 
     * @return \Zend\ServiceManager\ServiceLocatorInterface 
     */  
    public function getServiceLocator()  
    {  
        return $this->serviceLocator;  
    }  
    
    /**
     * Get current language
     */
    public function getLanguage()
    {
        return $this->languageService->getLanguage();
    }
    
    /**
     * Get current language
     */
    public function getLanguagesList()
    {
    	return $this->languageService->getLanguagesList();
    }
    
    
    /**
     * Get default language parameter
     */
    public function getDefaultLanguage()
    {
    	return $this->languageService->getDefaultRoutelanguage();
    }
    
    
    /**
     * Get default language parameter
     */
    public function getDefaultLanguageParameter()
    {
    	return $this->languageService->getDefaultRoutelanguageParameter();
    }
    
    
    /**
     * Get default language parameter
     */
    public function getLanguageRouteSeparator()
    {
    	return $this->languageService->getDefaultRoutelanguageSeparator();
    }
    
    
    public function getDefaultRoute()
    {
        return $this->languageService->getDefaultRoute();
    }
    
    
    public function getCurrentRoute()
    {
    	$sm = $this->getServiceLocator()->getServiceLocator();
    	$request = $sm->get('request');
    	$router = $sm->get('router');
    	 
    	// Récupération de la route courante
    	if( $router->match($request) )
    	{
    		$currentRoute = $router->match($request)->getMatchedRouteName();
    		$lastUnderscore = strrpos( $currentRoute, $this->languageService->getDefaultRoutelanguageSeparator(), -2 );
    	  
    		if( $lastUnderscore )
    		{
    			$route = substr( $currentRoute, 0, $lastUnderscore );
    		}
    	}
    	else
    	{
    		$route = "";
    	}
    	
    	return $route;
    }
    
    
    public function routeLanguageExists( $routeName )
    {
        $sm = $this->getServiceLocator()->getServiceLocator();
        $router = $sm->get('router');
        return $router->hasRoute( $routeName );
    }
    
    public function __invoke()  
    {  
        $languageService       =  $this->getServiceLocator()->getServiceLocator()->get("LfLanguageManagerService");
        $this->languageService = $languageService;
        return $this;
    }    
}
?>