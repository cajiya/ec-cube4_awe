<?php

namespace Plugin\AttachWysiwygEditor\Repository;


use Eccube\Repository\AbstractRepository;
use Plugin\AttachWysiwygEditor\Entity\AweConfig;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;



class AweConfigRepository extends AbstractRepository
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
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
            ->createQuery('SELECT m FROM Plugin\AttachWysiwygEditor\Entity\AweConfig m ORDER BY m.id DESC');
        $result = $query
            ->getResult(Query::HYDRATE_ARRAY);

        return $result;
    }
}
