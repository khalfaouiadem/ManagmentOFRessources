<?php

namespace App\Form;

use App\Entity\Effect;
use App\Entity\RessourcesHumaines;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EffectifsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
               
                'attr' => ['class' => 'form-control'],
            ])
            ->add('prenom', TextType::class, [
              
                'attr' => ['class' => 'form-control'],
            ])
            ->add('poste', TextType::class, [
                'label' => 'Poste',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', TextType::class, [
              
                'attr' => ['class' => 'form-control'],
            ])
            ->add('telephone', TextType::class, [
                
                'attr' => ['class' => 'form-control'],
            ])
            ->add('num_cin', IntegerType::class, [
               
                'attr' => ['class' => 'form-control'],
            ])
            ->add('id_ref', EntityType::class, [
                'class' => RessourcesHumaines::class,
                'choice_label' => 'code', // ou autre propriété à afficher
            
                'placeholder' => 'Choisir une référence',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('image_effect', FileType::class, [
          
                'mapped' => false,          // Ce champ n’est pas lié directement à l’entité
                'required' => false,
                'attr' => ['accept' => 'image/*', 'class' => 'form-control'],
            ])
              ->add('localisation', TextType::class, [
             
                'required' => true,
                'attr' => ['placeholder' => '36.8065,10.1815']
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Effect::class,
        ]);
    }
}
