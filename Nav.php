<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
