<?php

namespace Plugin\AttachWysiwygEditor\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;


/**
 * AweConfig
 *
 * @ORM\Table(name="plg_wysiwyg_editor_settings")
 * @ORM\Entity(repositoryClass="Plugin\AttachWysiwygEditor\Repository\AweConfigRepository")
 */
class AweConfig extends AbstractEntity
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
     * @return AweConfig
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
     * @return AweConfig
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
     * @return AweConfig
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