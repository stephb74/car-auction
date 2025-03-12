<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Enum\VehicleTypeName;
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

        foreach (VehicleTypeName::cases() as $vehicleType) {
            $this->addSql('INSERT INTO vehicle_types (name) VALUES (?)', [$vehicleType->value]);
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vehicle_types');
    }
}
