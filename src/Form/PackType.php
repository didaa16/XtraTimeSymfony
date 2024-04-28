<?php

namespace App\Form;

use App\Entity\Pack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
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
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une description']),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('reduction', null, [
                'label' => 'Réduction',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir une réduction']),
                    new Range([
                        'min' => 10,
                        'max' => 100,
                        'notInRangeMessage' => 'La réduction doit être entre {{ min }} et {{ max }}',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Charger une image',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('duree', ChoiceType::class, [
                'label' => 'Durée',
                'choices' => [
                    '1 mois' => '1 mois',
                    '2 mois' => '2 mois',
                    '3 mois' => '3 mois',
                    '4 mois' => '4 mois',
                    '5 mois' => '5 mois',
                    '6 mois' => '6 mois',
                    '7 mois' => '7 mois',
                    '8 mois' => '8 mois',
                    '9 mois' => '9 mois',
                    '10 mois' => '10 mois',
                    '11 mois' => '11 mois',
                    '12 mois' => '12 mois',
                ],
                'placeholder' => 'Choisir la durée',
            ])
            // Ajoutez d'autres champs si nécessaire
        ;
    }

  



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pack::class,
            'ancienne_image' => null, // Par défaut, aucune ancienne image
        ]);
    }
}
