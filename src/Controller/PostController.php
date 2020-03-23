<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Services\FileUploader;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PostController extends AbstractController {

	/**
	 * @Route("/admin/posts", name="admin.posts")
	 */
	public function index( PostRepository $postRepository, Request $request, PaginatorInterface $paginator, UserInterface $user, UserRepository $userRespository ) {
		$posts = $postRepository->findAll();


		if ( ! $this->get( 'security.authorization_checker' )->isGranted( 'ROLE_ADMIN' ) ) {
			$posts = $postRepository->findByUserPosts( $user->getId() );
		}

		$res = $paginator->paginate(
			$posts,
			$request->query->getInt( 'page', 1 ),
			$request->query->getInt( 'limit', 5 )
		);	

		return $this->render(
			'post/index.html.twig',
			array(
				'posts' => $res,
			)
		);
	}

	 /**
	  * @Route("admin/post/create", name="admin.create.post")
	  */
	public function create( Request $request, FileUploader $fileUploader, UserInterface $user ) {
		$post = new Post();

		$userId = $user->getId();

		$form = $this->createForm( PostType::class, $post );

		$form->handleRequest( $request );

		$post->setAuthor( $userId );

		if ( $form->isSubmitted() ) {
			$em   = $this->getDoctrine()->getManager();
			$file = $request->files->get( 'post' )['image'];

			if ( $file ) {

				$fileName = $fileUploader->uploadFile( $file );

				$post->setImage( $fileName );
			} 
			$em->persist( $post );
			$em->flush();
			$this->addFlash( 'success', 'Post was successfully created' );
			return $this->redirect( $this->generateUrl( 'admin.posts' ) );

		}

		return $this->render(
			'post/create.html.twig',
			array(
				'form' => $form->createView(),
			)
		);
	}


	/**
	 * @Route("admin/post/edit/{id}", name="admin.edit.post")
	 */
	public function edit( PostRepository $postRepository, Request $request, $id, FileUploader $fileUploader ) {
		$post = new Post();
		$post = $postRepository->find( $id );
		$form = $this->createForm( PostType::class, $post );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() ) {
			$em   = $this->getDoctrine()->getManager();
			$file = $request->files->get( 'post' )['image'];

			if ( $file ) {

				$fileName = $fileUploader->uploadFile( $file );

				$post->setImage( $fileName );
			}
			
			$em->flush();
			$this->addFlash( 'success', 'Post was successfully updated' );
			return $this->redirect( $this->generateUrl( 'admin.posts' ) );

		}

		return $this->render(
			'post/edit.html.twig',
			array(
				'form' => $form->createView(),
				'post' => $post,
			)
		);
	}



	/**
	 * @Route("/post/show/{id}", name="show")
	 */
	public function show( PostRepository $postRepository, $id ) {
		$post = $postRepository->find( $id );
		return $this->render(
			'post/show.html.twig',
			array(
				'post' => $post,
			)
		);
	}

	/**
	 * @Route("admin/post/delete/{id}", name="admin.delete.post")
	 */
	public function delete( PostRepository $postRepository, $id ) {
		$filesystem = new Filesystem();

		$post = $postRepository->find( $id );

		$file = $this->getParameter( 'uploads_dir' ) . $post->getImage();
		$filesystem->remove( $file );

		$em = $this->getDoctrine()->getManager();
		$em->remove( $post );
		$em->flush();
		$this->addFlash( 'success', 'Post was successfully deleted' );
		return $this->redirect( $this->generateUrl( 'admin.posts' ) );
	}



}
