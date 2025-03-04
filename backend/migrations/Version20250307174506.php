<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307174506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__percentage_fee_rates AS SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM percentage_fee_rates');
        $this->addSql('DROP TABLE percentage_fee_rates');
        $this->addSql('CREATE TABLE percentage_fee_rates (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, vehicle_type_id INTEGER NOT NULL, rate DOUBLE PRECISION NOT NULL, min_amount DOUBLE PRECISION DEFAULT NULL, max_amount DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO percentage_fee_rates (id, fee_id, vehicle_type_id, rate, min_amount, max_amount) SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM __temp__percentage_fee_rates');
        $this->addSql('DROP TABLE __temp__percentage_fee_rates');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__percentage_fee_rates AS SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM percentage_fee_rates');
        $this->addSql('DROP TABLE percentage_fee_rates');
        $this->addSql('CREATE TABLE percentage_fee_rates (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, vehicle_type_id INTEGER NOT NULL, rate DOUBLE PRECISION NOT NULL, min_amount DOUBLE PRECISION DEFAULT NULL, max_amount DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_91A5A778AB45AECA FOREIGN KEY (fee_id) REFERENCES fees (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO percentage_fee_rates (id, fee_id, vehicle_type_id, rate, min_amount, max_amount) SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM __temp__percentage_fee_rates');
        $this->addSql('DROP TABLE __temp__percentage_fee_rates');
        $this->addSql('CREATE INDEX IDX_91A5A778AB45AECA ON percentage_fee_rates (fee_id)');
    }
}
