<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Countrys;
use App\Entity\Shipping;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class,[
                'label'=> 'Prénom',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('lastName',TextType::class,[
                'label'=> 'Nom', 
                'attr' => [
                    'type'=>'text'
                ] 
            ])
            ->add('email',TextType::class,[
                'label'=> 'Email',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('address1',TextType::class,[
                'label'=> 'Adresse',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('complementAdr1',TextType::class,[
                'label'=> 'Complément adr.',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('city',TextType::class,[
                'label'=> 'Ville',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('codePostal',TextType::class,[
                'label'=> 'Code postal',
            ])
            ->add('phone',TextType::class,[
                'label'=> 'Téléphone',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('country', EntityType::class, [
                'class' => Countrys::class,
                'choice_label' => 'country',
                'label'=> 'Pays',
                'attr' => [
                    'type'=>'text'
                ]
            ])
            ->add('address2', ShippingType::class);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
