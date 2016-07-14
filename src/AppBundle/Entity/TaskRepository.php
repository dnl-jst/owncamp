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

    public function getMaxUnfinishedPosition(TaskSet $taskSet)
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT COALESCE(MAX(t.position), 0) FROM AppBundle:Task t WHERE t.taskSet = :taskSet AND t.finished IS NULL')
            ->setParameter('taskSet', $taskSet)
            ->getSingleScalarResult();
    }

    public function getFinished(TaskSet $taskSet)
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT t FROM AppBundle:Task t WHERE t.taskSet = :taskSet AND t.finished IS NOT NULL')
            ->setParameter('taskSet', $taskSet)
            ->getResult();
    }

    public function getMaxFinishedPosition(TaskSet $taskSet)
    {
        return $this
            ->getEntityManager()
            ->createQuery('SELECT COALESCE(MAX(t.position), 0) FROM AppBundle:Task t WHERE t.taskSet = :taskSet AND t.finished IS NOT NULL')
            ->setParameter('taskSet', $taskSet)
            ->getSingleScalarResult();
    }

}
