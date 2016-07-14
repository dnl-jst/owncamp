<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class InstallController extends Controller
{
    /**
     * @Route("/install", name="install")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userCount = $em->getRepository('AppBundle:User')->getCount();

        if ($userCount > 0) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createFormBuilder()
            ->add('firstName', TextType::class, array(
                'required' => true,
                'label' => 'Vorname'
            ))
            ->add('lastName', TextType::class, array(
                'required' => true,
                'label' => 'Nachname'
            ))
            ->add('email', EmailType::class, array(
                'required' => true,
                'label' => 'E-Mail'
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat password'),
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $user = new User();
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setPassword($this->get('security.password_encoder')->encodePassword($user, $data['plainPassword']));

            # add roles
            $user->addRole($em->getRepository('AppBundle:Role')->findOneByRole('ROLE_USER'));
            $user->addRole($em->getRepository('AppBundle:Role')->findOneByRole('ROLE_ADMIN'));

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('install/index.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
