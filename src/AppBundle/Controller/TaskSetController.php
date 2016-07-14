<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\TaskSet;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class TaskSetController extends Controller
{
    /**
     * @Route("/task-set/{id}", name="taskSet_index", requirements={"id": "\d+"})
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var TaskSet $taskSet */
        $taskSet = $em->getRepository('AppBundle:TaskSet')->findOneById($id);
        $taskRepo = $em->getRepository('AppBundle:Task');

        $user = $this->getUser();

        if (!$taskSet->getProject()->getUsers()->contains($user)) {
            throw $this->createNotFoundException();
        }

        return $this->render('task-set/index.html.twig', [
            'user' => $user,
            'project' => $taskSet->getProject(),
            'taskSet' => $taskSet,
            'unfinishedTasks' => $taskRepo->getUnfinished($taskSet),
            'finishedTasks' => $taskRepo->getFinished($taskSet)
        ]);
    }

    /**
     * @Route("/task-set/add/{projectId}", name="taskSet_add")
     */
    public function addAction(Request $request, $projectId)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->getUser();

        /** @var Project $project */
        $project = $em->getRepository('AppBundle:Project')->findOneById($projectId);

        if (!$project->getUsers()->contains($user)) {
            throw $this->createNotFoundException();
        }

        $taskSet = new TaskSet();

        $form = $this->createFormBuilder($taskSet)
            ->add('name', TextType::class, array(
                'required' => true,
                'label' => 'Task set name'
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'label' => 'Task set description'
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $taskSet->setCreated(new \DateTime());
            $taskSet->setProject($project);

            $em->persist($taskSet);
            $em->flush();

            return $this->redirectToRoute('project_index', array('id' => $project->getId()));
        }

        return $this->render('task-set/add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
