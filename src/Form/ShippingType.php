<?php

namespace App\Form;

use App\Entity\Countrys;
use App\Entity\Shipping;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShippingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address',TextType::class,[
                'required' => false,
                'label'=> 'Adresse',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('complementAdr',TextType::class,[
                'required' => false,
                'label'=> 'Complément adr.',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('city',TextType::class,[
                'required' => false,
                'label'=> 'Ville',
            ])
            ->add('codePostal',TextType::class,[
                'required' => false,
                'label'=> 'Code postal',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('phone',TextType::class,[
                'required' => false,
                'label'=> 'Téléphone',
            ])
            ->add('prenom',TextType::class,[
                'required' => false,
                'label'=> 'Prénom',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('nom',TextType::class,[
                'required' => false,
                'label'=> 'Nom',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('country', EntityType::class, [
                'required' => false,
                'class' => Countrys::class,
                'choice_label' => 'country',
                'label'=>'Pays',
                'attr' => [
                    'type'=>'text'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shipping::class,
        ]);
    }
}
