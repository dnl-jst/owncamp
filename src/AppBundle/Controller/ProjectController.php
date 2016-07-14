<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project/{id}", name="project_index", requirements={"id": "\d+"})
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var Project $project */
        $project = $em->getRepository('AppBundle:Project')->findOneById($id);

        $user = $this->getUser();

        if (!$project->getUsers()->contains($user)) {
            throw $this->createNotFoundException();
        }

        return $this->render('project/index.html.twig', [
            'user' => $user,
            'project' => $project,
            'taskRepo' => $em->getRepository('AppBundle:Task')
        ]);
    }

    /**
     * @Route("/project/add", name="project_add")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->getUser();

        if (!$user->hasRole('ROLE_ADMIN')) {
            return $this->redirectToRoute('projects_index');
        }

        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class, array(
                'required' => true,
                'label' => 'Project name'
            ))
            ->add('description', TextareaType::class, array(
                'required' => true,
                'label' => 'Project description'
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $project->setCreated(new \DateTime());
            $user->addProject($project);

            $em->persist($project);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('projects_index');
        }

        return $this->render('project/add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
