<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectsController extends Controller
{
    /**
     * @Route("/projects", name="projects_index")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        return $this->render('projects/index.html.twig', [
            'user' => $user
        ]);
    }
}
