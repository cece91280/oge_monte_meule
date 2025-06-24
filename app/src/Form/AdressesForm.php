<?php

namespace App\Form;

use App\Entity\Adresses;
use App\Entity\Devis;
use App\Entity\TypeBiens;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse_nom', TextType::class, ['label' => 'Nom de la rue'])
            ->add('ville', TextType::class, ['label' => 'Ville'])
            ->add('code_postal', TextType::class, ['label' => 'Code postal'])
            ->add('etage', IntegerType::class, ['label' => 'Etage (0 si RDC)',
                'required' => false,
                'empty_data' => 0])
            ->add('ascenseur', null, [
                'label' => 'Présence d\'ascenseur',
                'required' => false,])
            ->add('typeBien', EntityType::class, [
                'class' => TypeBiens::class,
                'choice_label' => 'nom',
                'label' => 'Type de bien',
                'placeholder' => 'Choisissez un type de bien',
                'required' => true,
            ])
            ->add('devis_arriver', EntityType::class, [
                'class' => Devis::class,
                'choice_label' => 'id',
            ])
            ->add('devis_depart', EntityType::class, [
                'class' => Devis::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresses::class,
        ]);
    }
}
