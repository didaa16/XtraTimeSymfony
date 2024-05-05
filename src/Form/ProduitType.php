<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('ref')
            ->add('nom')
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'rows' => 3],
            ])
            ->add('prix')
            ->add('marque', ChoiceType::class, [
                'label' => 'Marque',
                'choices' => [
                    'nike' => 'nike',
                    'adidas' => 'adidas',
                    'underArmour' => 'underArmour',
                    'puma' => 'puma',
                ],
                'placeholder' => 'Choisir une marque', // Placeholder ajouté ici

                'attr' => ['class' => 'form-select'],
                
            ])
            ->add('taille')
            ->add('typesport', ChoiceType::class, [ // Add 'typesport' field
                'label' => 'Type',
                'choices' => [
                    'handball' => 'handball',
                    'football' => 'football',
                    'basketball' => 'basketball',
                    'volleyball' => 'volleyball',
                    'tennis' => 'tennis',
                ],
                'placeholder' => 'Choisir un type de sport', // Placeholder ajouté ici

                'attr' => ['class' => 'form-select'],
            ])
            
            
            ->add('quantite')
            

            ->add('image', FileType::class, [ // Add 'image' field
                'label' => 'Charger une image',
                'required' => false,
                'data_class' => null, // Set data_class to null
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}