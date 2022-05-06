<?php

namespace Plugin\AttachWysiwygEditor\EventListener;

use Eccube\Common\EccubeConfig;
use Eccube\Request\Context;
use Eccube\Event\TemplateEvent;

use Plugin\AttachWysiwygEditor\Entity\AweConfig;
use Plugin\AttachWysiwygEditor\Repository\AweConfigRepository;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

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
      $snipets = $event->getParameter('plugin_snippets');
      if( $snipets )
      {
        foreach( $snipets as $snippet => $include )
        {
          $event->addSnippet( $snippet , $include);
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
        $output = '<script>$(document).ready(function() {';
        foreach( $selector_list as $selector )
        {
          $output .= <<< EOD
              $('{$selector}').summernote({
                height: 300,
                toolbar: [
                  // [groupName, [list of button]]
                  
                  ['style', ['style' ,'bold', 'italic', 'underline', 'strikethrough',  'clear']],
                  ['color', ['color']],
                  ['para', ['ul', 'ol', 'paragraph']],
                  ['table', ['table']],
                  ['insert', ['link', 'picture', 'video']],
                  ['view', ['codeview']]
                ],
                styleTags: [
                  'p', 'h1', 'h2', 'h3', 'h4'
                ],
                paragraphTags: []
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
        
        $event->addSnippet( '@AttachWysiwygEditor/admin/awe.twig' );
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
