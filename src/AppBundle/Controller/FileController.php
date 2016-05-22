<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    /**
     * @Route("/file/{id}/{filename}.{ext}", name="file")
     */
    public function fileAction($id, $filename, $ext)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $user = $this->getUser();

        $response = new Response();

        /** @var File $file */
        $file = $em->getRepository('AppBundle:File')->findOneById($id);

        if (!$file->getTask() && !$file->getComment()) {
            throw $this->createNotFoundException();
        } else if ($file->getTask()) {
            if (!$user->getProjects()->contains($file->getTask()->getTaskSet()->getProject())) {
                throw $this->createNotFoundException();
            }
        } else if ($file->getComment()) {
            if (!$user->getProjects()->contains($file->getComment()->getTask()->getTaskSet()->getProject())) {
                throw $this->createNotFoundException();
            }
        }

        $response->headers->set('Content-Type', $file->getValueBlobType());
        $response->setContent(stream_get_contents($file->getValueBlob()));

        return $response;
    }
}
