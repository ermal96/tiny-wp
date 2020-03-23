<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationController extends AbstractController {

	/**
	 * @Route("/register", name="register")
	 */
	public function register( Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository ) {
		$form = $this->createFormBuilder()
			->add(
				'name',
				TextType::class,
				array(
					'attr' => array(
						'class'    => 'form-control-user',
						'required' => true,
					),
				)
			)
			->add(
				'username',
				TextType::class,
				array(
					'attr' => array(
						'class'    => 'form-control-user',
						'required' => true,
					),
				)
			)
			->add(
				'password',
				RepeatedType::class,
				array(
					'type'            => PasswordType::class,
					'invalid_message' => 'The password fields must match.',
					'options'         => array( 'attr' => array( 'class' => 'form-control-user' ) ),
					'required'        => true,
					'first_options'   => array( 'label' => 'Password' ),
					'second_options'  => array( 'label' => 'Repeat Password' ),

				)
			)
		->add(
			'register',
			SubmitType::class,
			array(
				'attr' => array(
					'class' => 'btn btn-primary btn-block',
				),
			)
		)
			->getForm();

		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$data = $form->getData();
			$user = new User();
			$user->setUsername( $data['username'] );

			$users = $userRepository->findAll();

			if ( ! $users ) {
				$user->setRoles( array( 'ROLE_ADMIN' ) );
			} else {
				$user->setRoles( array( 'ROLE_USER' ) );
			}

			$user->setName( $data['name'] );
			$user->setPassword(
				$passwordEncoder->encodePassword( $user, $data['password'] )
			);
			$em = $this->getDoctrine()->getManager();
			$em->persist( $user );

			$userExists = $userRepository->findOneByUsername( $data['username'] );

			if ( ! $userExists ) {
				$em->flush();
				return $this->redirect( $this->generateUrl( 'app_login' ) );

			} else {
				$this->addFlash( 'userExists', 'Sorry that username is taken' );
			}
		}

		return $this->render(
			'registration/registration.html.twig',
			array(
				'form' => $form->createView(),
			)
		);
	}
}
