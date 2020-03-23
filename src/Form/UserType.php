<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder
		->add(
			'roles',
			ChoiceType::class,
			array(
				'choices'  => array(
					'Admministrator' => 'ROLE_ADMIN',
					'User'           => 'ROLE_USER',
				),
				'expanded' => true,
				'multiple' => true,
			)
		)
			->add(
				'name',
				TextType::class,
				array(
					'required' => false,
				)
			)
			->add(
				'password',
				PasswordType::class,
				array(
					'required' => false,
				)
			)
			->add(
				'email',
				EmailType::class,
				array(
					'required' => false,
				)
			)
			->add(
				'bio',
				TextareaType::class,
				array(
					'required' => false,
				)
			)
			->add(
				'birthday',
				TextType::class,
				array(
					'required' => false,
				)
			)
			->add(
				'phone',
				NumberType::class,
				array(
					'required' => false,
				)
			)
			->add(
				'image',
				FileType::class,
				array(
					'required'   => false,
					'data_class' => null,
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
				'data_class' => User::class,
			)
		);
	}
}
