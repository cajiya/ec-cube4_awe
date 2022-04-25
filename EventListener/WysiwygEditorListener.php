<?php

namespace Plugin\WysiwygEditor\EventListener;

use Eccube\Common\EccubeConfig;
use Eccube\Request\Context;
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

    public function __construct(RequestStack $requestStack, EccubeConfig $eccubeConfig, Context $requestContext)
    {
        $this->requestStack = $requestStack;
        $this->eccubeConfig = $eccubeConfig;
        $this->requestContext = $requestContext;
    }

    public function onKernelRequest(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        if ($this->requestContext->isAdmin()) {

            
          log_info( '[AdminEditorWysiwyg]$event' , [$event] );
          $response = $event->getResponse();
          log_info( '[AdminEditorWysiwyg]$response' , [$response] );
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
            // let selector = Array(
            //   '#page_admin_product_product_new .c-primaryCol textarea.form-control',
            //   '#page_admin_product_product_edit .c-primaryCol textarea.form-control',
            //   '#page_admin_content_news_edit .c-primaryCol textarea.form-control',
            //   '#page_admin_content_news .c-primaryCol textarea.form-control',
            // );
            // selector = selector.join();
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
