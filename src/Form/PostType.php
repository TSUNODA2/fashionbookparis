<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Texte',
                'attr' => [
                    'class' => 'new-publication-textarea'
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Une image'
            ])
            ->add('urlVideo', UrlType::class, [
                'label' => 'Lien vers la vidéo'
            ])
            ->add('visible', CheckboxType::class, [
                'label' => 'Visible'
            ])->add('submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'new-publication-submit'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
