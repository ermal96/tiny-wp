<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController {

	/**
	 * @Route("admin/categories", name="admin.categories")
	 */
	public function index( CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator ) {
		$categories = $categoryRepository->findAll();
		$res        = $paginator->paginate(
			$categories,
			$request->query->getInt( 'page', 1 ),
			$request->query->getInt( 'limit', 5 )
		);
		return $this->render(
			'category/categories.html.twig',
			array(
				'categories' => $res,
			)
		);
	}


	/**
	 * @Route("admin/category/create", name="admin.create.category")
	 */
	public function create( Request $request ) {
		$category = new Category();

		$form = $this->createForm( CategoryType::class, $category );

		$form->handleRequest( $request );

		if ( $form->isSubmitted() ) {
			$em = $this->getDoctrine()->getManager();
			$category->setDate( date( 'Y-m-d' ) );
			$em->persist( $category );
			$em->flush();
			$this->addFlash( 'success', 'Category was successfully created' );
			return $this->redirect( $this->generateUrl( 'admin.categories' ) );

		}

		return $this->render(
			'category/create.html.twig',
			array(
				'form' => $form->createView(),
			)
		);
	}


	/**
	 * @Route("admin/category/edit/{id}", name="admin.edit.category")
	 */
	public function edit( CategoryRepository $categoryRepository, Request $request, $id ) {
		$category = new Category();
		$category = $categoryRepository->find( $id );
		$form     = $this->createForm( CategoryType::class, $category );

		$form->handleRequest( $request );

		if ( $form->isSubmitted() ) {
			$em = $this->getDoctrine()->getManager();
			$em->flush();
			$this->addFlash( 'success', 'Category was successfully updated' );
			return $this->redirect( $this->generateUrl( 'admin.categories' ) );

		}

		return $this->render(
			'category/edit.html.twig',
			array(
				'form'     => $form->createView(),
				'category' => $category,
			)
		);
	}


	/**
	 * @Route("admin/category/delete/{id}", name="admin.delete.category")
	 */
	public function delete( CategoryRepository $categoryRepository, $id ) {
		$category = $categoryRepository->find( $id );
		$em       = $this->getDoctrine()->getManager();
		$em->remove( $category );
		$em->flush();
		$this->addFlash( 'success', 'Category was successfully deleted' );
		return $this->redirect( $this->generateUrl( 'admin.categories' ) );
	}



}
