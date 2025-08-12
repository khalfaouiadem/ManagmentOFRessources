<?php

namespace App\Form;

use App\Entity\Materiel;
use App\Entity\RessourcesHumaines;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_m', TextType::class, [
                'label' => 'Nom du matériel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Ordinateur portable'
                ]
            ])
            ->add('code_m', TextType::class, [
                'label' => 'Code du matériel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: MTR-001'
                ]
            ])
            ->add('ressource', EntityType::class, [
                'class' => RessourcesHumaines::class,
                'choice_label' => 'code', // ou autre propriété pour le label
                'placeholder' => 'Sélectionner une ressource',
                'attr' => ['class' => 'form-select'],
                'choice_attr' => function (?RessourcesHumaines $res) {
                    return $res ? ['data-image' => '/uploads/images/' . $res->getImage()] : [];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
