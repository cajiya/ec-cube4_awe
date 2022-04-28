<?php

namespace Plugin\WysiwygEditor\EventListener;

use Eccube\Common\EccubeConfig;
use Eccube\Request\Context;
use Eccube\Event\TemplateEvent;

use Plugin\WysiwygEditor\Entity\WysiwygEditorConfig;
use Plugin\WysiwygEditor\Repository\WysiwygEditorConfigRepository;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class WysiwygEditorListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var Context
     */
    protected $requestContext;

    protected $wysiwygEditorConfigRepository;

    public function __construct(
      RequestStack $requestStack,
      EccubeConfig $eccubeConfig,
      Context $requestContext,
      WysiwygEditorConfigRepository $wysiwygEditorConfigRepository
    )
    {
        $this->requestStack = $requestStack;
        $this->eccubeConfig = $eccubeConfig;
        $this->requestContext = $requestContext;
        $this->wysiwygEditorConfigRepository = $wysiwygEditorConfigRepository;
    }

    public function adminInsertWysiwygEditorTag(TemplateEvent $event)
    {
      $output;
      $selector_list = [];
      $wysiwyg_frag = false;
      $wysiwyg_config = $this->wysiwygEditorConfigRepository->findAll();
      $currentRequest = $this->requestStack->getCurrentRequest();
      $request_path = $currentRequest->getPathInfo();
      $setting_path;
      foreach( $wysiwyg_config as $config)
      {
        $setting_path = '/'.$this->eccubeConfig['eccube_admin_route'].'/'.$config['url_path'];
        if(  false !== strpos($request_path, $setting_path) )
        {
          $wysiwyg_frag = true;
          $selector_list[] = $config['selector'];
        }
      }
      if( $wysiwyg_frag )
      {
        $fileDir = $this->eccubeConfig['eccube_html_dir'] . '/WysiwygEditor/summernote/dist';
        $output = '<script>$(document).ready(function() {';
        foreach( $selector_list as $selector )
        {
          $output .= <<< EOD
              $('{$selector}').summernote({
                height: 300,
                // callbacks: {
                //   onEnter: function (c) {
                //     c.preventDefault();
                //     console.log(this);
                //     console.log(c.target.innerHTML);
                //     // c.target.innerHTML.replace("<p><br></p><p><br></p>","<p><br></p>");
                //   }
                // }
             });
EOD;
        }
        $output .= '});</script>';

        $event->addSnippet( '@WysiwygEditor/admin/wysiwygeditor.twig' );
        $event->addSnippet( $output , false);

      }
    }

    public static function getSubscribedEvents()
    {

        return [
          '@admin/default_frame.twig' => ['adminInsertWysiwygEditorTag'],
        ];
    }

}
