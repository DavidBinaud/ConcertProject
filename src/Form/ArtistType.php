<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Band;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $year = date("Y");
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('sceneName', TextType::class,[
                'label' => 'Nom de scène',
                'required' => false
            ])
            ->add('birthDate', DateType::class,[
                'widget' => 'choice',
                'required' => false,
                'years' => range($year-50,$year+50)
            ])
            ->add('bands', EntityType::class, [
                'class' => Band::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'required' => false
            ])
/*            ->add('bands', CollectionType::class, [
                // each entry in the array will be an "email" field
                'entry_type' => EntityType::class,
                // these options are passed to each "email" type
                'required' => false,
                'entry_options' => [
                    'attr' => ['class' => Band::class],
                ],
            ])*/
            ->add('picture', FileType::class, [
                'label' => 'picture',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image, either a png or jpg',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
