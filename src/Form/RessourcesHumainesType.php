<?php

namespace App\Form;

use App\Entity\RessourcesHumaines;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Admin;

class RessourcesHumainesType extends AbstractType
{

public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('code', TextType::class, [
            'label' => 'Code de la ressource humaine',
            'attr' => ['placeholder' => 'Ex: RH-001', 'class' => 'form-control'],
        ])
        ->add('id_ref', EntityType::class, [
            'class' => Admin::class,
            'choice_label' => 'nom',
            'placeholder' => 'Choisir un admin',
            'required' => false,
            'attr' => ['class' => 'form-select'],
        ])
        ->add('image', FileType::class, [
            'label' => 'Image (JPEG ou PNG)',
            'mapped' => false,
            'required' => false,
            'attr' => ['class' => 'form-control'],
            'constraints' => [
                new File([
                    'maxSize' => '2M',
                    'mimeTypes' => ['image/jpeg', 'image/png'],
                    'mimeTypesMessage' => 'Merci de télécharger une image valide (JPEG ou PNG).',
                ])
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RessourcesHumaines::class,
        ]);
    }
}
