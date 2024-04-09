<?php

namespace App\Form;

use App\Entity\Hotel;
use App\Entity\Image;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomhotel', null, [
            'label' => 'Nom de hotel' 
        ])
            ->add('capacite')
            ->add('price')
            
            ->add('image', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'url',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter Hotel', // Set the label explicitly
                'attr' => ['class' => 'btn btn-primary'], // Add Bootstrap classes if needed
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}
