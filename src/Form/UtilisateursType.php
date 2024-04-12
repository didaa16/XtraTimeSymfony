<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UtilisateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'USER' => 'ROLE_USER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    // Add more role choices as needed
                ],
                'multiple' => true, // Allow selecting multiple roles
                'expanded' => true, // Render roles as checkboxes
            ])
            ->add('password')
            ->add('cin')
            ->add('nom')
            ->add('prenom')
            ->add('age')
            ->add('numTel')
            ->add('email')
            ->add('isVerified')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}
