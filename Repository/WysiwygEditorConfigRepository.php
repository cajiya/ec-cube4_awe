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

namespace Plugin\WysiwygEditor\Repository;


use Eccube\Repository\AbstractRepository;
use Plugin\WysiwygEditor\Entity\WysiwygEditorConfig;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;



class WysiwygEditorConfigRepository extends AbstractRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WysiwygEditorConfig::class);
    }


    /**
     * find all.
     *
     * @return array
     */
    public function findAll()
    {
        $query = $this
            ->getEntityManager()
            ->createQuery('SELECT m FROM Plugin\WysiwygEditor\Entity\WysiwygEditorConfig m ORDER BY m.id DESC');
        $result = $query
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
