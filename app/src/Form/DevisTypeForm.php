<?php

namespace App\Form;

use App\Entity\Adresses;
use App\Entity\Devis;
use App\Entity\Prestations;
use App\Entity\TypeBiens;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeBien', EntityType::class, [
                'class' => TypeBiens::class ,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez un type de bien',
            ])
            ->add('volume', NumberType::class, [
                'required' => false,
                'label' => 'Volume approximatif (m²)',
            ])
            ->add('date_demenagement', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('adresses_arriver', EntityType::class, [
                'class' => Adresses::class,
                'choice_label' => 'adressesNom',
            ])
            ->add('adresses_depart', EntityType::class, [
                'class' => Adresses::class,
                'choice_label' => 'adressesNom',
            ])
            ->add('prestations', EntityType::class, [
                'class' => Prestations::class,
                'choice_label' => 'Nom',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'id',
            ])
            ->add('prixEstime', MoneyType::class, [
                'currency' => 'EUR',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
