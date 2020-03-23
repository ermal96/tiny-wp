<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController {

	/**
	 * @Route("/admin", name="admin")
	 */
	public function index( PostRepository $postRepository, CategoryRepository $categoryRepository, UserRepository $userRepository ) {
		$posts      = $postRepository->getPosts();
		$categories = $categoryRepository->getCategories();
		$users      = $userRepository->getUsers();

		$recentCategories = $categoryRepository->getRecentCategories( 5 );
		$recentUsers      = $userRepository->getRecentUsers( 5 );
		$recentPosts      = $postRepository->getRecentPosts( 5 );

		return $this->render(
			'dashboard/dashboard.html.twig',
			array(
				'controller_name'  => 'Dashboard',
				'totalPosts'       => count( $posts ),
				'totalCategories'  => count( $categories ),
				'totalUsers'       => count( $users ),
				'recentCategories' => $recentCategories,
				'recentPosts'      => $recentPosts,
				'recentUsers'      => $recentUsers,
			)
		);
	}
}
