<?php

namespace Plugin\AttachWysiwygEditor42;

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
                                'name' => 'リッチエディタ設定',
                                'url' => 'awe_admin_config',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
