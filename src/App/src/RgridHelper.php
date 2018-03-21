<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 20.03.2018
 * Time: 18:48
 */

namespace rollun\app;


use Zend\View\Helper\AbstractHelper;

class RgridHelper extends AbstractHelper
{
    public function __invoke($url)
    {
        $view = $this->getView();
        $view->bootstrap();
        $this->addDojo($view);
        $this->addDojoStyles($view);
        $dataViewerHtml = "
            <script>
                require([
                    \"dojo/dom\",
                    \"dojo/dom-class\",
                    \"dojo/_base/window\",
                    \"Rscript/DataStoreViewer/widget/AdvDatastoreViewer\",
                    'dstore/Memory',
                ], function (dom,
                             domClass,
                             win,
                             AdvDatastoreViewer,
                             Memory) {
                    
                    domClass.add(win.body(), \"claro\");
                    var myFilterStore = new Memory();
                    var dataStoreViewer = new AdvDatastoreViewer({url: '$url', gridConfig: {filterStore: myFilterStore}});
                    dataStoreViewer.placeAt(dom.byId('data_store_viewer')).startup();
                })
            </script>
            <div id=\"data_store_viewer\" ></div>
        ";

        return $dataViewerHtml;
    }

    protected function addDojoStyles($view)
    {
        $view->headLink()
            ->appendStylesheet('assets/js/dojo/resources/dojo.css')
            ->appendStylesheet('assets/js/dijit/themes/claro/claro.css')
            ->appendStylesheet('assets/js/dojox/highlight/resources/highlight.css')
            ->appendStylesheet('assets/js/dojox/highlight/resources/pygments/colorful.css')
            ->appendStylesheet('assets/js/dgrid/css/dgrid.css')
            ->appendStylesheet('assets/js/dgrid/css/skins/claro.css')
            ->appendStylesheet('assets/js/dojox/grid/enhanced/resources/claro/EnhancedGrid.css')
            ->appendStylesheet('assets/js/dojox/grid/enhanced/resources/EnhancedGrid_rtl.css')
            ->appendStylesheet('assets/js/dojo-rql/lib/FilterEditor/resources/css/FilterEditor.css')
            ->appendStylesheet('assets/js/dojo-rql/lib/DataStoreViewer/resource/bootstrap/css/bootstrap.css')
            ->appendStylesheet('assets/js/dojo-rql/lib/DataStoreViewer/resource/bootstrap/css/bootstrap-theme.css')
            ->appendStylesheet('assets/js/dojo/resources/dnd.css')
            ->appendStylesheet('assets/js/dojo-rql/lib/DataStoreViewer/resource/bootstrap/css/dashboard.css');
    }

    protected function addDojo($view)
    {
        $view->inlineScript()->appendScript("
            var dojoConfig = {
                async: true,
                isDebug: true,
                packages: [
                    {
                        name: \"Rscript\",
                        location: '../dojo-rql/lib'
                    },
                    {
                        name: \"dstore\",
                        location: '../dojo-dstore'
                    }, 
                    {
                        name: \"dgrid\",
                        location: '../dgrid'
                    }, 
                    {
                        name: \"dijit\",
                        location: '../dijit'
                    }, 
                    {
                        name: \"dojox\",
                        location: '../dojox'
                    }, 
                    {
                        name: \"promised-io\",
                        location: '../promised-io'
                    }, 
                    {
                        name: \"rql\",
                        location: '../rollun-rql'
                    }
                ]
            };
        ");
        $view->inlineScript()->appendFile("/assets/js/dojo/dojo.js");
    }
}