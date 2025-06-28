<?php


namespace App\DataFixtures;

use App\Entity\Prestations;
use App\Entity\TypeBiens;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;


class keywordsFixtures extends Fixture
{
    private SluggerInterface $slugger;
    public function __construct(SluggerInterface $slugger){
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager): void
    {
        // ===== Type de Biens ===== //
        $types = ['Appartement', 'Maison', 'Studio', 'Villa', 'Loft'];

        foreach ($types as $nomType) {
            $typeBien = new TypeBiens();
            $typeBien->setNom($nomType);


            $manager->persist($typeBien);
        }

        // ===== Prestations ===== //
        $prestations = [
            [
                'nom' => 'Emballage',
                'description' => 'Emballage des biens fragiles',
                'prix_de_base' => 50.00
            ],
            [
                'nom' => 'Montage',
                'description' => 'Montage et démontage de meubles',
                'prix_de_base' => 80.00
            ],
            [
                'nom' => 'Transport longue distance',
                'description' => 'Transport inter-régional des biens',
                'prix_de_base' => 200.00
            ],
            [
                'nom' => 'Service express',
                'description' => 'Déménagement effectué le jour même',
                'prix_de_base' => 100.00
            ],
            [
                'nom' => 'Nettoyage',
                'description' => 'Nettoyage des locaux après déménagement',
                'prix_de_base' => 60.00
            ],
        ];
        foreach ($prestations as $data) {
            $prestation = new Prestations();
            $prestation->setNom($data['nom']);
            $prestation->setDescription($data['description']);
            $prestation->setPrixDeBase($data['prix_de_base']);

            $manager->persist($prestation);
        }
        $manager->flush();
    }
}