<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TaskRepository
 */
class TaskRepository extends EntityRepository
{

    public function getUnfinished(TaskSet $taskSet)
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Task t WHERE t.taskSet = :taskSet AND t.finished IS NULL')
            ->setParameter('taskSet', $taskSet)
            ->getResult();
    }

}
