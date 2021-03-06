<?php

namespace Plugin\AttachWysiwygEditor;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getNav()
    {
        return [
            'setting' => [
                'children' => [
                    'system' => [
                        'children' => [
                            'attachwysiwygeditor' => [
                                'name' => 'AttachWysiwygEditor',
                                'url' => 'awe_admin_config',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
