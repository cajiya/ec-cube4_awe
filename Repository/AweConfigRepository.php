<?php

namespace Plugin\AttachWysiwygEditor42\Repository;


use Eccube\Repository\AbstractRepository;
use Plugin\AttachWysiwygEditor42\Entity\AweConfig;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;



class AweConfigRepository extends AbstractRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AweConfig::class);
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
            ->createQuery('SELECT m FROM Plugin\AttachWysiwygEditor42\Entity\AweConfig m ORDER BY m.id DESC');
        $result = $query
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
