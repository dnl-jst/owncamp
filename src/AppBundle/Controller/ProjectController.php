<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project/{id}", name="project_index")
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
            'project' => $project
        ]);
    }
}
