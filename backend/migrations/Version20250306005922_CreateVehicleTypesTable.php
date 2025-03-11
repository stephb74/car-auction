<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306005922CreateVehicleTypesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create vehicle_types table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE vehicle_types (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL)',
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_VEHICLE_TYPE_NAME ON vehicle_types (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vehicle_types');
    }
}
