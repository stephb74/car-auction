<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250307025222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__fees AS SELECT id, name, type FROM fees');
        $this->addSql('DROP TABLE fees');
        $this->addSql('CREATE TABLE fees (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, type VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO fees (id, name, type) SELECT id, name, type FROM __temp__fees');
        $this->addSql('DROP TABLE __temp__fees');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fixed_fees AS SELECT id, fee_id, amount FROM fixed_fees');
        $this->addSql('DROP TABLE fixed_fees');
        $this->addSql('CREATE TABLE fixed_fees (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, amount DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO fixed_fees (id, fee_id, amount) SELECT id, fee_id, amount FROM __temp__fixed_fees');
        $this->addSql('DROP TABLE __temp__fixed_fees');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fixed_tier_fee AS SELECT id, fee_id, min_amount, max_amount, amount FROM fixed_tier_fee');
        $this->addSql('DROP TABLE fixed_tier_fee');
        $this->addSql('CREATE TABLE fixed_tier_fee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, min_amount DOUBLE PRECISION DEFAULT NULL, max_amount DOUBLE PRECISION DEFAULT NULL, amount DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO fixed_tier_fee (id, fee_id, min_amount, max_amount, amount) SELECT id, fee_id, min_amount, max_amount, amount FROM __temp__fixed_tier_fee');
        $this->addSql('DROP TABLE __temp__fixed_tier_fee');
        $this->addSql('CREATE TEMPORARY TABLE __temp__percentage_fee_rates AS SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM percentage_fee_rates');
        $this->addSql('DROP TABLE percentage_fee_rates');
        $this->addSql('CREATE TABLE percentage_fee_rates (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, vehicle_type_id INTEGER NOT NULL, rate DOUBLE PRECISION NOT NULL, min_amount DOUBLE PRECISION DEFAULT NULL, max_amount DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO percentage_fee_rates (id, fee_id, vehicle_type_id, rate, min_amount, max_amount) SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM __temp__percentage_fee_rates');
        $this->addSql('DROP TABLE __temp__percentage_fee_rates');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vehicle_types AS SELECT id, name FROM vehicle_types');
        $this->addSql('DROP TABLE vehicle_types');
        $this->addSql('CREATE TABLE vehicle_types (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO vehicle_types (id, name) SELECT id, name FROM __temp__vehicle_types');
        $this->addSql('DROP TABLE __temp__vehicle_types');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__fees AS SELECT id, name, type FROM fees');
        $this->addSql('DROP TABLE fees');
        $this->addSql('CREATE TABLE fees (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, type VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO fees (id, name, type) SELECT id, name, type FROM __temp__fees');
        $this->addSql('DROP TABLE __temp__fees');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEE_NAME ON fees (name)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fixed_fees AS SELECT id, fee_id, amount FROM fixed_fees');
        $this->addSql('DROP TABLE fixed_fees');
        $this->addSql('CREATE TABLE fixed_fees (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, amount DOUBLE PRECISION NOT NULL, CONSTRAINT FK_FIXED_FEES__FEE FOREIGN KEY (fee_id) REFERENCES fees (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO fixed_fees (id, fee_id, amount) SELECT id, fee_id, amount FROM __temp__fixed_fees');
        $this->addSql('DROP TABLE __temp__fixed_fees');
        $this->addSql('CREATE INDEX IDX_FIXED_FEES_FEE_ID ON fixed_fees (fee_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__fixed_tier_fee AS SELECT id, fee_id, min_amount, max_amount, amount FROM fixed_tier_fee');
        $this->addSql('DROP TABLE fixed_tier_fee');
        $this->addSql('CREATE TABLE fixed_tier_fee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, min_amount DOUBLE PRECISION DEFAULT NULL, max_amount DOUBLE PRECISION DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, CONSTRAINT FK_FIXED_TIER_FEE__FEE FOREIGN KEY (fee_id) REFERENCES fees (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO fixed_tier_fee (id, fee_id, min_amount, max_amount, amount) SELECT id, fee_id, min_amount, max_amount, amount FROM __temp__fixed_tier_fee');
        $this->addSql('DROP TABLE __temp__fixed_tier_fee');
        $this->addSql('CREATE INDEX IDX_FIXED_TIER_FEE_FEE_ID ON fixed_tier_fee (fee_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__percentage_fee_rates AS SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM percentage_fee_rates');
        $this->addSql('DROP TABLE percentage_fee_rates');
        $this->addSql('CREATE TABLE percentage_fee_rates (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, fee_id INTEGER NOT NULL, vehicle_type_id INTEGER NOT NULL, rate DOUBLE PRECISION NOT NULL, min_amount DOUBLE PRECISION DEFAULT NULL, max_amount DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_FEE_RATE_FEE_ID FOREIGN KEY (fee_id) REFERENCES fees (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_FEE_RATE_VEHICLE_TYPE_ID FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO percentage_fee_rates (id, fee_id, vehicle_type_id, rate, min_amount, max_amount) SELECT id, fee_id, vehicle_type_id, rate, min_amount, max_amount FROM __temp__percentage_fee_rates');
        $this->addSql('DROP TABLE __temp__percentage_fee_rates');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D3A3A3A4D1C8A3A8D93D649 ON percentage_fee_rates (fee_id, vehicle_type_id)');
        $this->addSql('CREATE INDEX IDX_5D3A3A3A8D93D649 ON percentage_fee_rates (vehicle_type_id)');
        $this->addSql('CREATE INDEX IDX_5D3A3A3A4D1C8A3A ON percentage_fee_rates (fee_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vehicle_types AS SELECT id, name FROM vehicle_types');
        $this->addSql('DROP TABLE vehicle_types');
        $this->addSql('CREATE TABLE vehicle_types (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO vehicle_types (id, name) SELECT id, name FROM __temp__vehicle_types');
        $this->addSql('DROP TABLE __temp__vehicle_types');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_VEHICLE_TYPE_NAME ON vehicle_types (name)');
    }
}
