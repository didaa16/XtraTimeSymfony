<?php

namespace App\Form;

use App\Entity\Sponso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType; 

class SponsoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('tel')
            ->add('email')
            
        ;
        $builder->add('image', FileType::class, [
            'label' => 'Charger une image',
            'required' => false,
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sponso::class,
            'previousImage' => null, // Par défaut, aucune ancienne image

        ]);
    }
}
