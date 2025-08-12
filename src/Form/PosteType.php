<?php

namespace App\Form;

use App\Entity\Poste;
use App\Entity\Effect;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_P')
            ->add('Code_P')
            ->add('poste_e', EntityType::class, [
                'class' => Effect::class,
                'choice_label' => fn(Effect $effect) => $effect->getNom() . ' ' . $effect->getPrenom(),
                'placeholder' => 'Sélectionner un effectif',
                'required' => false
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false, // pas lié directement à l'entité
                'required' => false,
                'label' => 'Image du poste'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poste::class,
        ]);
    }
}
