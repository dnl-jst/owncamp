<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
* UserRepository
*/
class UserRepository extends EntityRepository
{

    public function getCount()
    {
        return (int) $this
            ->getEntityManager()
            ->createQuery('SELECT COUNT(u) FROM AppBundle:User u')
            ->getSingleScalarResult();
    }

}
