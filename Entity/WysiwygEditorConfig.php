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

namespace Plugin\WysiwygEditor\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;


/**
 * WysiwygEditorConfig
 *
 * @ORM\Table(name="plg_wysiwyg_editor_settings")
 * @ORM\Entity(repositoryClass="Plugin\WysiwygEditor\Repository\WysiwygEditorConfigRepository")
 */
class WysiwygEditorConfig extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="url_path", type="string")
     */
    private $url_path;

    
    /**
     * @var string
     *
     * @ORM\Column(name="selector", type="string")
     */
    private $selector;


   /**
     *
     * @param int $id
     *
     * @return WysiwygEditorConfig
     */
    public function setId(int $id = null)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



   /**
     *
     * @param string $url_path
     *
     * @return WysiwygEditorConfig
     */
    public function setUrlPath( $url_path )
    {
        $this->url_path = $url_path;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getUrlPath()
    {
        return $this->url_path;
    }

   /**
     *
     * @param string $selector
     *
     * @return WysiwygEditorConfig
     */
    public function setSelector( $selector )
    {
        $this->selector = $selector;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getSelector()
    {
        return $this->selector;
    }



}