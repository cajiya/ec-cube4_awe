<?php

namespace Plugin\WysiwygEditor\EventListener;

use Eccube\Common\EccubeConfig;
use Eccube\Request\Context;
use Plugin\WysiwygEditor\Entity\WysiwygEditorConfig;
use Plugin\WysiwygEditor\Repository\WysiwygEditorConfigRepository;
// use Symfony\Component\DependencyInjection\ContainerInterface;

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

    public function onKernelRequest(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        if ($this->requestContext->isAdmin()) {
          $wysiwyg_frag = false;
          $wysiwyg_config = $this->wysiwygEditorConfigRepository->findAll();
          
          log_info( '[AdminEditorWysiwyg]$wysiwyg_config' , [$wysiwyg_config] );

          $currentRequest = $this->requestStack->getCurrentRequest();
          $request_path = $currentRequest->getPathInfo();
          $setting_path;
          foreach( $wysiwyg_config as $config)
          {
            $setting_path = '/'.$this->eccubeConfig['eccube_admin_route'].'/'.$config['url_path'];
            if( $request_path === $setting_path )
            {
              $wysiwyg_frag = true;
            }
          }

          log_info( '[AdminEditorWysiwyg]$currentRequest' , [$currentRequest] );
          log_info( '[AdminEditorWysiwyg]$event' , [$event] );

          if( $wysiwyg_frag )
          {
            log_info( '[AdminEditorWysiwyg]$wysiwyg_frag is TRUE');
          }

          $response = $event->getResponse();
          $content = $response->getContent();
        //   $plugin_dir = $this->eccubeConfig['eccube_html_plugin_dir'] . "/WysiwygEditor/Resource/template/default/lib/summernote/dist/";
        //   log_info( '[AdminEditorWysiwyg]$plugin_dir' , [$plugin_dir] );
          $code = <<< EOD
          <!-- include summernote css/js -->
          <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
          <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
          <!-- Initialize Quill editor -->
          <script>
            $(document).ready(function() {
                var HelloButton = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                      contents: '<i class="fa fa-child"/> Hello',
                      click: function () {
                        context.invoke('editor.insertText', 'hello');
                      }
                    });
                    return button.render();   // return button as jquery object
                }
                $('#page_admin_product_product_new .c-primaryCol textarea.form-control').summernote({
                    height: 300, 
                    // toolbar: [
                    //     ['mybutton', ['hello']]
                    // ],
                    // buttons: {
                    //     hello: HelloButton
                    // },
                    minHeight: null, 
                    maxHeight: null, 
                    focus: true 
                  });
            });
          </script></body>
EOD;
        $content = str_replace( '</body>', $code, $content);
        log_info( '[AdminEditorWysiwyg]$content' , [$content] );
        $response->setContent($content);

        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => ['onKernelRequest', 512],
        ];
    }

}
