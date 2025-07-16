<?php

namespace App\Form;

use App\Entity\Devis;

use App\Entity\Prestations;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;


class DevisTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('volume', NumberType::class, [
                'required' => false,
                'label' => 'Volume approximatif (m²)',
                'attr' => ['placeholder' => 'Ex: 20m²',]

            ])
            ->add('date_demenagement', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,



            ])
            ->add('adressesArriver', AdressesTypeForm::class, [
                'label' => false,
            ])
            ->add('adressesDepart', AdressesTypeForm::class, [
                'label' => false,
            ])
            ->add('commentaire', TextareaType::class, [
                'required' => false,
                'label' => 'Commentaires ou précisions supplémentaires',
                'attr' => ['placeholder' => 'Ajouter  ici tout information utile...']
            ])
            ->add('prestations', EntityType::class, [
                'class' => Prestations::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'label' => 'Choisissez les prestations souhaitées',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,

        ]);
    }
}
