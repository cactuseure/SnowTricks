<?php

namespace App\Form\Type;

use App\Entity\Figure;
use App\Entity\FigureGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigureType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Figure $figure */
        $figure = $builder->getData();


        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('figureGroup', EntityType::class, [
                'class' => FigureGroup::class,
                'choice_label'=> 'name',
                'label' => 'Groupe',
                'required' => false
            ])
            ->add('cover', FileType::class, [
                'label' => 'Image de profil',
                'mapped' => false,
            ])
            ->add('pictures', CollectionType::class, [
                'entry_type' => MediaObjectType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'mapped' => false,
                'label' => false
            ])
            ->add('youtubeVideos', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label' => false,
                'delete_empty' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'data_class' => Figure::class,
            'currentPictures' => null,
        ]);
    }
}
