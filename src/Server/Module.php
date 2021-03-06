<?php
/**
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2013 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\Apigility\Doctrine\Server;

use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\ModuleManager;

class Module
    implements DependencyIndicatorInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/server.config.php';
    }

    public function init(ModuleManager $moduleManager)
    {
        $sm = $moduleManager->getEvent()->getParam('ServiceManager');
        $serviceListener = $sm->get('ServiceListener');

        $serviceListener->addServiceManager(
            'ZfOrmCollectionFilterManager',
            'zf-orm-collection-filter',
            'ZF\Apigility\Doctrine\Server\Collection\Filter\FilterInterface',
            'getZfOrmFilterConfig'
        );

        $serviceListener->addServiceManager(
            'ZfOdmCollectionFilterManager',
            'zf-odm-collection-filter',
            'ZF\Apigility\Doctrine\Server\Collection\Filter\FilterInterface',
            'getZfOdmFilterConfig'
        );
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return ['Phpro\DoctrineHydrationModule'];
    }
}
