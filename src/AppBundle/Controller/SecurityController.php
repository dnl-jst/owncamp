<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

	/**
	 * @Route("/login", name="security_login"),
	 */
	public function loginAction()
	{
		if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirectToRoute('projects_index');
		}

		$authenticationUtils = $this->get('security.authentication_utils');

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();

		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render(
			'login/login.html.twig',
			array(
				// last username entered by the user
				'last_username' => $lastUsername,
				'error'         => $error
			)
		);
	}

	/**
	 * @Route("/login_check", name="security_login_check")
	 */
	public function loginCheckAction()
	{
		// this controller will not be executed,
		// as the route is handled by the Security system
	}

	/**
	 * @Route("/logout", name="security_logout")
	 */
	public function logoutAction()
	{
		// this controller will not be executed,
		// as the route is handled by the Security system
	}

}
