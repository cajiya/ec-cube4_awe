<?php

namespace Plugin\AttachWysiwygEditor\Controller\Admin;

use Eccube\Controller\AbstractController;

// use Plugin\AttachWysiwygEditor\Form\Type\Admin\ProductReviewConfigType;
use Plugin\AttachWysiwygEditor\Entity\AweConfig;
use Plugin\AttachWysiwygEditor\Repository\AweConfigRepository;
use Plugin\AttachWysiwygEditor\Form\Type\Admin\AweConfigCollectionType;
use Plugin\AttachWysiwygEditor\Form\Type\Admin\AweConfigType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AweConfigController.
 */
class AweConfigController extends AbstractController
{

    protected $aweConfigRepository;

    /**
     * AweController constructor.
     *
     * @param AweConfigRepository $aweConfigRepository
     */
    public function __construct(
        AweConfigRepository $aweConfigRepository
    ) {
        $this->aweConfigRepository = $aweConfigRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/awe/config", name="awe_admin_config")
     * @Template("@AttachWysiwygEditor/admin/config.twig")
     *
     * @return array
     */
    public function index(Request $request)
    {

        $data = [];
        try {
            $configs = $this->aweConfigRepository->findAll();
            $data['data'] = [];
            foreach ($configs as $value) {
                $data['data'][$value['id']]['id'] = $value['id'];
                $data['data'][$value['id']]['url_path'] = $value['url_path'];
                $data['data'][$value['id']]['selector'] = $value['selector'];
            }
            $data['data'][] = [
                'id' => count($configs)+1,
                'url_path' => '',
                'selector' => '',
            ];
        } catch (MappingException $e) {
        }


        $builder = $this->formFactory->createBuilder(AweConfigCollectionType::class, $data);
        $form = $builder->getForm();
        $form_view = $form->createView();

        return [
            'form' => $form->createView(),
        ];

    }


    /**
     * @Route("/%eccube_admin_route%/awe/config/edit", name="awe_admin_config_edit", methods={"GET", "POST"})
     * @Template("@AttachWysiwygEditor/admin/config.twig")
     */
    public function edit(Request $request)
    {
        $builder = $this->formFactory->createBuilder(AweConfigCollectionType::class);

        $form = $builder->getForm();

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $ids = array_filter(array_map(
                    function ($v) {
                        return $v['id'];
                    },
                    $data['data']
                ));

                $repository = $this->entityManager->getRepository(AweConfig::class);

                foreach ($data['data'] as $key => $value ) {
                    
                    if ($value['id'] !== null && $value['url_path'] !== null && $value['selector'] !== null) {
                        $config = $repository->find($value['id']);

                        if ($config === null) {
                            $config = new AweConfig();
                        }
                        $config->setId($value['id']);
                        $config->setUrlPath($value['url_path']);
                        $config->setSelector($value['selector']);
                        $this->entityManager->persist($config);

                    } elseif ( $value['url_path'] === null && $value['selector'] === null ) {

                        // remove
                        $delKey = $this->entityManager->getRepository(AweConfig::class)->find( $value['id'] );
                        if ($delKey) {
                            $this->entityManager->remove($delKey);
                        }
                    }
                }

                try {
                    $this->entityManager->flush();
                    $this->addSuccess('admin.common.save_complete', 'admin');
                } catch (\Exception $e) {
                    
                    // 外部キー制約などで削除できない場合に例外エラーになる
                    $this->addError('admin.common.save_error', 'admin');
                }

                return $this->redirectToRoute('awe_admin_config');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

}
