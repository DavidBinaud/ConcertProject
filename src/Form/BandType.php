<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\Band;
use App\Entity\Concert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class BandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $year = date("Y");
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('creationDate', DateType::class,[
                'widget' => 'choice',
                'required' => false,
                'years' => range($year-50,$year+50)
            ])
            ->add('artists', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
            ->add('concerts', EntityType::class, [
                'class' => Concert::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])->add('picture', FileType::class, [
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
            'data_class' => Band::class,
        ]);
    }
}
