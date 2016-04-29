<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/task/{id}", name="task_index")
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
}
