<?php

namespace rollun\app;

use rollun\amazonItemSearch\isual\ImporterViewHelper;
use Zend\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'templates' => $this->getTemplates(),
            'view_helpers' => [
                'factories' => [
                    RgridHelper::class => InvokableFactory::class,
                ],
                'aliases' => [
                    'rgrid' => RgridHelper::class,
                ]
            ]
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates()
    {
        return [
            'paths' => [
                'app-table' => [__DIR__ . '/templates/app-table'],
                'app-layout' => [__DIR__ . '/templates/app-layout'],
                'app-error' => [__DIR__ . '/templates/app-error'],
            ],
            'layout' => 'app-layout::admin-layout',
        ];
    }
}