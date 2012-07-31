<?php

namespace SpeckCatalog;

use Zend\ModuleManager\ModuleManager;
use Zend\Navigation;
use Application\Extra\Page;
use Service\Installer;
use Catalog\Model\Mapper;
use Catalog\Service\FormServiceAwareInterface;
use Catalog\Service\CatalogServiceAwareInterface;

class Module
{
    protected $view;
    protected $viewListener;

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Catalog' => __DIR__ . '/src/Catalog',
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $app          = $e->getParam('application');
        $locator      = $app->getServiceManager();
        $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
        $renderer->plugin('url')->setRouter($locator->get('Router'));
        $renderer->plugin('headScript')->appendFile('/assets/speck-catalog/js/speck-catalog-manager.js');
        $renderer->plugin('headLink')->appendStylesheet('/assets/speck-catalog/css/speck-catalog.css');
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'speckCatalogRenderChildren' => 'Catalog\View\Helper\ChildViewRenderer',
                'speckCatalogRenderForm' => 'Catalog\View\Helper\RenderForm',
                'speckCatalogAdderHelper' => 'Catalog\View\Helper\AdderHelper',
            ),
            'initializers' => array(
                function($instance, $sm){
                    if($instance instanceof FormServiceAwareInterface){
                        $sm = $sm->getServiceLocator();
                        $formService = $sm->get('catalog_form_service');
                        $instance->setFormService($formService);
                    }
                },
                function($instance, $sm){
                    if($instance instanceof CatalogServiceAwareInterface){
                        $sm = $sm->getServiceLocator();
                        $catalogService = $sm->get('catalog_generic_service');
                        $instance->setCatalogService($catalogService);
                    }
                },
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'initializers' => array(
                function($instance, $sm){
                    if($instance instanceof FormServiceAwareInterface){
                        $sm = $sm->getServiceLocator();
                        $formService = $sm->get('catalog_form_service');
                        $instance->setFormService($formService);
                    }
                },
                function($instance, $sm){
                    if($instance instanceof CatalogServiceAwareInterface){
                        $sm = $sm->getServiceLocator();
                        $catalogService = $sm->get('catalog_generic_service');
                        $instance->setCatalogService($catalogService);
                    }
                },
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'catalog_availability_form' => function ($sm) {
                    $form = new \Catalog\Form\Availability;
                    $form->setCompanyService($sm->get('catalog_company_service'));
                    return $form->init();
                },
                'catalog_product_form' => function ($sm) {
                    $form = new \Catalog\Form\Product;
                    $form->setCompanyService($sm->get('catalog_company_service'));
                    return $form->init();
                },
                'catalog_product_uom_form' => function ($sm) {
                    $form = new \Catalog\Form\ProductUom;
                    $form->setUomService($sm->get('catalog_uom_service'));
                    return $form->init();
                },
                'catalog_db' => function ($sm) {
                    return $sm->get('Zend\Db\Adapter\Adapter');
                },
            ),
            'initializers' => array(
                function($instance, $sm){
                    if($instance instanceof Mapper\DbAdapterAwareInterface){
                        $dbAdapter = $sm->get('catalog_db');
                        return $instance->setDbAdapter($dbAdapter);
                    }
                },
            ),
        );
    }

}
