<?php

namespace App\Form;

use App\Entity\Terrain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('capacite', ChoiceType::class,['choices'=>['2'=>'2','4'=>'4','10'=>'10','10'=>'10','11'=>'11','12'=>'12','13'=>'13',]])
            ->add('type', ChoiceType::class,['choices'=>['Football'=>'Football','Handball'=>'Handball','Basketball'=>'Basketball','Tennis'=>'Tennis',]])
            ->add('prix')
            ->add('disponibilite', ChoiceType::class,['choices'=>['12/12 ET 7 SUR 7 '=>'12/12 ET 7 SUR 7','24/24 ET 7 SUR 7'=>'24/24 ET 7 SUR 7',]])
            ->add('img')
            ->add('ref')
            ->add('save',SubmitType::class);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,
        ]);
    }
}
