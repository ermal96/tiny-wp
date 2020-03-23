<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\FileUploader;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin", name="admin.")
 */
class UserController extends AbstractController {

	/**
	 * @Route("/users", name="users")
	 */
	public function index( UserRepository $userRepository, Request $request, PaginatorInterface $paginator ) {
		$users = $userRepository->findAll();
		$res   = $paginator->paginate(
			$users,
			$request->query->getInt( 'page', 1 ),
			$request->query->getInt( 'limit', 5 )
		);

		return $this->render(
			'user/users.html.twig',
			array(
				'users' => $res,
			)
		);
	}

	/**
	 * @Route("/user/edit/{id}", name="edit.user")
	 */
	public function edit( UserRepository $userRepository, Request $request, $id, FileUploader $fileUploader ) {
		$user = new User();
		$user = $userRepository->find( $id );
		$form = $this->createForm( UserType::class, $user );

		if ( $this->get( 'security.authorization_checker' )->isGranted( 'ROLE_USER' ) ) {
			$form->remove( 'roles' );
		}

		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) {
			$em   = $this->getDoctrine()->getManager();
			$file = $request->files->get( 'user' )['image'];
			if ( $file ) {

				$fileName = $fileUploader->uploadFile( $file );

				$user->setImage( $fileName );
			}
			$em->flush();
			if ( $this->get( 'security.authorization_checker' )->isGranted( 'ROLE_ADMIN' ) ) {
				$this->addFlash( 'success', 'User was successfully updated' );
				return $this->redirect( $this->generateUrl( 'admin.users' ) );
			}
		}

		return $this->render(
			'user/edit.html.twig',
			array(
				'form' => $form->createView(),
				'user' => $user,
			)
		);
	}



	/**
	 * @Route("/user/delete/{id}", name="delete.user")
	 */
	public function delete( UserRepository $userRepository, $id ) {

		$filesystem = new Filesystem();

		$user = $userRepository->find( $id );

		$file = $this->getParameter( 'uploads_dir' ) . $user->getImage();
		$filesystem->remove( $file );

		$em = $this->getDoctrine()->getManager();
		$em->remove( $user );
		$em->flush();
		$this->addFlash( 'success', 'User was successfully deleted' );
		return $this->redirect( $this->generateUrl( 'admin.users' ) );
	}



}
