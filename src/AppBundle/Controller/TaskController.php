<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Task;
use AppBundle\Entity\TaskSet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/task/{id}", name="task_index", requirements={"id": "\d+"})
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Task $task */
        $task = $em->getRepository('AppBundle:Task')->findOneById($id);

        $user = $this->getUser();

        if (!$task->getTaskSet()->getProject()->getUsers()->contains($user)) {
            throw $this->createNotFoundException();
        }

        return $this->render('task/index.html.twig', [
            'user' => $user,
            'task' => $task
        ]);
    }

    /**
     * @Route("/task/add", name="task_add")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$request->get('task_set_id') || !$request->get('task_name')) {
            return new JsonResponse(false);
        }

        $user = $this->getUser();

        /** @var TaskSet $taskSet */
        $taskSet = $em->getRepository('AppBundle:TaskSet')->findOneById($request->get('task_set_id'));

        if (!$taskSet || !$taskSet->getProject()->getUsers()->contains($user)) {
            throw $this->createNotFoundException();
        }

        $task = new Task();
        $task->setName($request->get('task_name'));
        $task->setCreated(new \DateTime());
        $task->setTaskSet($taskSet);
        $task->setCreatedBy($user);
        $task->setPosition($em->getRepository('AppBundle:Task')->getMaxUnfinishedPosition($taskSet) + 1);

        $em->persist($task);
        $em->flush();

        return new JsonResponse(array('id' => $task->getId()));
    }

    /**
     * @Route("/task/edit", name="task_edit")
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$request->get('id')) {
            return new JsonResponse(false);
        }

        $user = $this->getUser();

        /** @var Task $task */
        $taskRepo = $em->getRepository('AppBundle:Task');
        $task = $taskRepo->findOneById($request->get('id'));

        if (!$task || !$task->getTaskSet()->getProject()->getUsers()->contains($user)) {
            return new JsonResponse(false);
        }

        $keys = $request->get('keys', array());

        foreach ($keys as $key) {

            $setter = 'set' . ucfirst($key);
            $value = $request->get($key);

            if ($key === 'assignedTo') {

                $user = $em->getRepository('AppBundle:User')->findOneById($value);

                if (!$user || !$task->getTaskSet()->getProject()->getUsers()->contains($user)) {
                    return new JsonResponse(false);
                }

                $value = $user;
            }

            if (method_exists($task, $setter)) {
                $task->$setter($value);
            }
        }

        $em->persist($task);
        $em->flush();

        return new JsonResponse(true);
    }

    /**
     * @Route("/task/finish", name="task_finish")
     */
    public function finishAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$request->get('id')) {
            return new JsonResponse(false);
        }

        $user = $this->getUser();

        /** @var Task $task */
        $taskRepo = $em->getRepository('AppBundle:Task');
        $task = $taskRepo->findOneById($request->get('id'));

        if (!$task || !$task->getTaskSet()->getProject()->getUsers()->contains($user)) {
            throw $this->createNotFoundException();
        }

        $finished = $request->get('finished', 0);
        $maxPosition = ($finished) ? ($taskRepo->getMaxFinishedPosition($task->getTaskSet()) + 1) : ($taskRepo->getMaxUnfinishedPosition($task->getTaskSet()) + 1);

        $task->setFinished($finished ? new \DateTime() : null);
        $task->setPosition($maxPosition + 1);

        $em->persist($task);
        $em->flush();

        return new JsonResponse(true);
    }
}
