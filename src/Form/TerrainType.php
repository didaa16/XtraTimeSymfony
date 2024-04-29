<?php

namespace App\Form;

use App\Entity\Terrain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File; // Add this line
class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom ne peut pas être vide']),
                    new Length(['min' => 1,
                    'minMessage' => 'Le nom ne peut pas être vide',
                        'max' => 255,
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'Le nom ne peut contenir que des lettres.'
                    ]),
                ],
                'error_bubbling' => false, // Ajoutez cette ligne
            ])
            ->add('capacite', ChoiceType::class, [
                'choices' => [
                    '2' => '2',
                    '4' => '4',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                ],
                'placeholder' => 'Choisir une capacité',
                'required' => true, // Rend le champ obligatoire
                'invalid_message' => 'Veuillez sélectionner une capacité.', // Message d'erreur

            ])
            
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Football' => 'Football',
                    'Handball' => 'Handball',
                    'Basketball' => 'Basketball',
                    'Tennis' => 'Tennis',
                ],
                'placeholder' => 'Choisir un champ',
                'required' => true, // Set the field as required
            ])
            ->add('prix', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prix ne peut pas être vide']),
                    new Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'Le prix doit contenir uniquement des chiffres.'
                    ]),
                ],
            ])
            ->add('disponibilite', ChoiceType::class, [
                'choices' => [
                    '12/12 ET 7 SUR 7 ' => '12/12 ET 7 SUR 7',
                    '24/24 ET 7 SUR 7' => '24/24 ET 7 SUR 7',
                ],
                'placeholder' => 'Choisir un champ',
                'required' => true, // Set the field as required
            ])
            ->add('img', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false])
            ->add('ref')
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,
        ]);
    }
}
