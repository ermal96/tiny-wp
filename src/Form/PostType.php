<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
			->add( 'title', TextType::class )
			->add(
				'image',
				FileType::class,
				array(
					'required'   => false,
					'data_class' => null,
					'mapped'     => false,
				)
			)
			->add(
				'category',
				EntityType::class,
				array(
					'class' => Category::class,
				)
			)
			->add(
				'description',
				TextareaType::class,
				array(
					'attr' => array(
						'class' => 'tinymce mt-5',
					),
				)
			)
			->add(
				'save',
				SubmitType::class,
				array(
					'attr' => array(
						'class' => 'btn btn-primary btn-md ',
					),
				)
			);
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults(
			array(
				'data_class'        => Post::class,
				'validation_groups' => false,
			)
		);
	}
}
