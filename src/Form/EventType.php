<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Sponso;
use App\Entity\Terrain;
use App\Entity\Utilisateurs; // Assuming Utilisateurs is the entity representing users
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Import FileType

use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\SponsoRepository;
use App\Repository\TerrainRepository;
use App\Repository\UserRepository;


class EventType extends AbstractType
{

    private $sponsoRepository;
    private $terrainRepository;
    private $userRepository;

    public function __construct(SponsoRepository $sponsoRepository, TerrainRepository $terrainRepository, UserRepository $userRepository)
    {
        $this->sponsoRepository = $sponsoRepository;
        $this->terrainRepository = $terrainRepository;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
           

         

            ->add('datedebut', DateTimeType::class)
            ->add('datefin', DateTimeType::class)
            // Sponsors
            ->add('idsponso', EntityType::class, [
                'class' => Sponso::class,
                'choice_label' => 'nom', // Use the 'nom' property as the choice label
                // Add other options as needed
            ])
            
            ->add('idterrain', EntityType::class, [
                'class' => Terrain::class,
                'choice_label' => 'nom', // Use the 'nom' property as the choice label
               
            ])
          
            ->add('iduser', EntityType::class, [
                'class' => Utilisateurs::class,
                'choice_label' => 'nom', 
            ]);
            $builder->add('image', FileType::class, [
                'label' => 'Charger une image',
                'required' => false,
                'mapped' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'sponso_choices' => [], // Default empty array
            'terrain_choices' => [], // Default empty array
            'user_choices' => [], // Default empty array
            'previousImage' => null, // Par défaut, aucune ancienne image
        ]);
    }
}
