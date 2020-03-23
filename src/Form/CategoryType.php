<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CategoryType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
		->add( 'name' )
		->add(
			'description',
			TextareaType::class,
			array(
				'attr' => array(
					'class'      => 'tinymce',
					'data-theme' => 'modern',
				),
			)
		)
		->add(
			'save',
			SubmitType::class,
			array(
				'attr' => array(
					'class' => 'btn btn-primary btn-md btn-block',
				),
			)
		);
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(
			array(
				'data_class' => Category::class,
			)
		);
	}
}
