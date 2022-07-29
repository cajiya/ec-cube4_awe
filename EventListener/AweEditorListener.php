<?php

namespace Plugin\AttachWysiwygEditor42\EventListener;

use Eccube\Common\EccubeConfig;
use Eccube\Request\Context;
use Eccube\Event\TemplateEvent;

use Plugin\AttachWysiwygEditor42\Entity\AweConfig;
use Plugin\AttachWysiwygEditor42\Repository\AweConfigRepository;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpFoundation\RequestStack;
// use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class AweEditorListener implements EventSubscriberInterface
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

    protected $aweConfigRepository;

    public function __construct(
      RequestStack $requestStack,
      EccubeConfig $eccubeConfig,
      Context $requestContext,
      AweConfigRepository $aweConfigRepository
    )
    {
        $this->requestStack = $requestStack;
        $this->eccubeConfig = $eccubeConfig;
        $this->requestContext = $requestContext;
        $this->aweConfigRepository = $aweConfigRepository;
    }

    public function adminInsertWysiwygEditorTag(TemplateEvent $event)
    {
      // 拡張されるファイルに充てたSnipetsの退避
      if( $event->hasParameter('plugin_snippets') )
      {
        $snipets = $event->getParameter('plugin_snippets');
        if( $snipets )
        {
          foreach( $snipets as $snippet => $include )
          {
            $event->addSnippet( $snippet , $include);
          }
        }
      }

      $output;
      $selector_list = [];
      $wysiwyg_frag = false;
      $wysiwyg_config = $this->aweConfigRepository->findAll();
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
        $fileDir = $this->eccubeConfig['eccube_html_dir'] . '/AttachWysiwygEditor/summernote/dist';
        $output = "<script>$(document).ready(function() {\r\n";        foreach( $selector_list as $selector )
        {
          $output .= "$('{$selector}').summernote(window.summernote_option);\r\n";
        }
        $output .= "});</script>";

        
        $event->addSnippet( '@AttachWysiwygEditor42/admin/awe.twig' );
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
