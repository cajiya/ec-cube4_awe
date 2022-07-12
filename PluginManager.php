<?php

namespace Plugin\AttachWysiwygEditor42;

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
        
        $eccubeconfig = $container->get('Eccube\Common\EccubeConfig');

        $file_system->mirror(
            $this->original_file_dir.'lib/summernote' ,
            $eccubeconfig->get('eccube_html_plugin_dir') . '/awe/summernote'
        );
        $file_system->mirror(
            $this->original_file_dir.'css' ,
            $eccubeconfig->get('eccube_html_plugin_dir') . '/awe/css'
        );
    }

    public function disable( array $meta, ContainerInterface $container )
    {
        $file_system = new Filesystem();
        
        $eccubeconfig = $container->get('Eccube\Common\EccubeConfig');

        $file_system->remove(
            $eccubeconfig->get('eccube_html_plugin_dir') . '/awe'
        );
    }


}
