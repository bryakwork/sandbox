<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 05.02.2018
 * Time: 19:50
 */

namespace rollun\app;


use rollun\installer\Install\InstallerAbstract;

class File2DSInstaller extends InstallerAbstract
{

    /**
     * install
     * @return array
     */
    public function install()
    {
        return [
            'action_render_service' => [
                'file2DS' => [
                    'action_middleware_service' => 'file2DSAction',
                    'render_middleware_service' => 'dataStoreHtmlJsonRendererLLPipe',
                ],
            ],
            'middleware_pipe_abstract' => [
                'file2DSAction' => [
                    'middlewares' => [
                        'rollun\datastore\Middleware\ResourceResolver',
                        'rollun\app\File2DSRequestDecoder',
                        'file2dsLLPipe',
                    ],
                ],
            ],
            'LazyLoadPipe' => [
                'file2dsLLPipe' => 'rollun\app\LazyLoadFile2DSMiddlewareGetter',
                'dataStoreHtmlJsonRendererLLPipe' => 'dataStoreHtmlJsonRenderer',
            ],
            'dependencies' => [
                'factories' => [
                    rollun\app\Middleware\File2DSMiddleware::class => \rollun\app\Factories\File2DSMiddlewarefactory::class,
                ],
                'invokables' => [
                    'rollun\datastore\Middleware\ResourceResolver' => 'rollun\datastore\Middleware\ResourceResolver',
                    'rollun\app\File2DSRequestDecoder' => 'rollun\app\File2DSRequestDecoder',
                    'rollun\app\LazyLoadFile2DSMiddlewareGetter' => 'rollun\app\LazyLoadFile2DSMiddlewareGetter',
                ],
            ],
        ];
    }

    /**
     * Clean all installation
     * @return void
     */
    public function uninstall()
    {
        // TODO: Implement uninstall() method.
    }

    /**
     * Return true if install, or false else
     * @return bool
     */
    public function isInstall()
    {
        // TODO: Implement isInstall() method.
    }

    /**
     * Return string with description of installable functional.
     * @param string $lang ; set select language for description getted.
     * @return string
     */
    public function getDescription($lang = "en")
    {
        // TODO: Implement getDescription() method.
    }
}