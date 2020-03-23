<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository {

	public function __construct( ManagerRegistry $registry ) {
		parent::__construct( $registry, Category::class );
	}


	public function getRecentCategories( $amount ) {
		return $this->createQueryBuilder( 'c' )
			->select( 'c' )
			->orderBy( 'c.id', 'DESC' )
			->setMaxResults( $amount )
			->getQuery()
			->getResult();
	}


	public function getCategories() {
		return $this->createQueryBuilder( 'c' )
			->select( 'c' )
			->orderBy( 'c.id', 'DESC' )
			->getQuery()
			->getResult();
	}


	public function getCategoryById( $id ) {
		return $this->createQueryBuilder( 'c' )
		->andWhere( 'c.id = :val' )
		->setParameter( 'val', $id )
		->getQuery()
		->getOneOrNullResult();
	}




	// /**
	// * @return Category[] Returns an array of Category objects
	// */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('c')
			->andWhere('c.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('c.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Category
	{
		return $this->createQueryBuilder('c')
			->andWhere('c.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
