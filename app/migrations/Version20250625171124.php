<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625171124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE adresses ADD type_biens_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresses ADD CONSTRAINT FK_EF192552BA8C12F0 FOREIGN KEY (type_biens_id) REFERENCES type_biens (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_EF192552BA8C12F0 ON adresses (type_biens_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_biens DROP FOREIGN KEY FK_119C832A85E14726
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_119C832A85E14726 ON type_biens
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_biens DROP adresses_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE adresses DROP FOREIGN KEY FK_EF192552BA8C12F0
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_EF192552BA8C12F0 ON adresses
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresses DROP type_biens_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_biens ADD adresses_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE type_biens ADD CONSTRAINT FK_119C832A85E14726 FOREIGN KEY (adresses_id) REFERENCES adresses (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_119C832A85E14726 ON type_biens (adresses_id)
        SQL);
    }
}
