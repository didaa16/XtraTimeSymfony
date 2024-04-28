<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Pack;
use App\Entity\Terrain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class AbonnementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('date', DateType::class, [
            'label' => 'Date',
            'widget' => 'single_text',
            'required' => true,
         
        ])
        ->add('terrain', EntityType::class, [
            'class' => Terrain::class,
            'choice_label' => 'nom',
            'required' => true,
        ])

        
        ->add('nomuser', TextType::class, [
            'label' => 'Nom',
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir un nom']),
                new Length([
                    'max' => 20,
                    'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères',
                ]),
                new Regex([
                    'pattern' => '/^[a-zA-Z\s]+$/',
                    'message' => 'Le nom ne peut contenir que des lettres alphabétiques',
                ]),
            ],
        ])
        
        ->add('numtel', IntegerType::class, [
            'label' => 'Numéro de Téléphone',
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => 'Veuillez saisir le numéro de téléphone']),
                new Length([
                    'min' => 8,
                    'max' => 15,
                    'minMessage' => 'Le numéro de téléphone doit avoir au moins {{ limit }} chiffres.',
                    'maxMessage' => 'Le numéro de téléphone ne peut pas dépasser {{ limit }} chiffres.',
                ]),
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'Le numéro de téléphone ne peut contenir que des chiffres.',
                ]),
            ],
        ])

        ->add('nompack', TextType::class, [
            'label' => 'Nom du Pack',
            'disabled' => true, // Champ non modifiable
            'data' => isset($options['pack']) ? $options['pack']->getNom() : null, // Vérification de nullité
        ])
        
        

        ->add('idp', HiddenType::class, [
            'mapped' => false, // Ne pas mapper ce champ à une propriété de l'entité
        ])

        ->add('pack', EntityType::class, [
            'class' => Pack::class,
            'choice_label' => 'nom', // Choisir le champ à afficher dans la liste déroulante
            'attr' => [
                'style' => 'display: none;' // Cacher le champ visuellement
            ]
            
        ]);
        
}
            
        
    

    public function configureOptions(OptionsResolver $resolver): void
    {
         $resolver->setDefaults([
            'data_class' => Abonnement::class,
            'nompack' => null, // Ajouter l'option 'nompack' avec une valeur par défaut de null
            'pack' => null, // Définir l'option 'pack' avec une valeur par défaut de null
            'packs' => [], // Déclaration de l'option 'packs'
            'terrains' => [],
            
        ]);
    }
}
