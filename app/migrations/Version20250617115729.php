<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617115729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B53231
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B67B3B43D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B97408E8A
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8B27C52B67B3B43D ON devis
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8B27C52B53231 ON devis
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8B27C52B97408E8A ON devis
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis ADD date_arriver_id INT NOT NULL, ADD date_depart_id INT NOT NULL, DROP users_id, DROP adresses_arriver_id, DROP adresses_depart_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis ADD CONSTRAINT FK_8B27C52BEB0D10C2 FOREIGN KEY (date_arriver_id) REFERENCES adresses (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B9174CDFF FOREIGN KEY (date_depart_id) REFERENCES adresses (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8B27C52BEB0D10C2 ON devis (date_arriver_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8B27C52B9174CDFF ON devis (date_depart_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis_prestations ADD prestations_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis_prestations ADD CONSTRAINT FK_1D5913D08BE96D0D FOREIGN KEY (prestations_id) REFERENCES prestations (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1D5913D08BE96D0D ON devis_prestations (prestations_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prestations DROP devisprestations_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status CHANGE nom nom VARCHAR(50) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52BEB0D10C2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B9174CDFF
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8B27C52BEB0D10C2 ON devis
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8B27C52B9174CDFF ON devis
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis ADD users_id INT NOT NULL, ADD adresses_arriver_id INT NOT NULL, ADD adresses_depart_id INT NOT NULL, DROP date_arriver_id, DROP date_depart_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B53231 FOREIGN KEY (adresses_depart_id) REFERENCES adresses (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B97408E8A FOREIGN KEY (adresses_arriver_id) REFERENCES adresses (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8B27C52B67B3B43D ON devis (users_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8B27C52B53231 ON devis (adresses_depart_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8B27C52B97408E8A ON devis (adresses_arriver_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis_prestations DROP FOREIGN KEY FK_1D5913D08BE96D0D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_1D5913D08BE96D0D ON devis_prestations
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE devis_prestations DROP prestations_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE prestations ADD devisprestations_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE status CHANGE nom nom VARCHAR(100) NOT NULL
        SQL);
    }
}
