<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\WysiwygEditor;

use Eccube\Common\EccubeConfig;
use Eccube\Plugin\AbstractPluginManager;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;


class PluginManager extends AbstractPluginManager
{

    private $original_file_dir = __DIR__ . "/Resource/template/default/";

    private $html_plugin_dir;

    // private $eccubeConfig;

    // private $file_system = new Filesystem();

    public function enable( array $meta, ContainerInterface $container )
    {
        $file_system = new Filesystem();
        
        // error_log( print_r('tsst', true)."\r\n" , '3' , __DIR__ . '/debug.log');
        $eccubeconfig = $container->get('Eccube\Common\EccubeConfig');

        var_dump($this->original_file_dir);
        var_dump($eccubeconfig->get('eccube_html_plugin_dir'));

        $file_system->mirror(
            $this->original_file_dir.'lib/summernote' ,
            $eccubeconfig->get('eccube_html_plugin_dir') . '/WysiwygEditor/summernote'
        );
    }

    public function disable( array $meta, ContainerInterface $container )
    {
        $file_system = new Filesystem();
        
        $eccubeconfig = $container->get('Eccube\Common\EccubeConfig');

        var_dump($this->original_file_dir);
        var_dump($eccubeconfig->get('eccube_html_plugin_dir'));

        $file_system->remove(
            $eccubeconfig->get('eccube_html_plugin_dir') . '/WysiwygEditor'
        );
    }


}
