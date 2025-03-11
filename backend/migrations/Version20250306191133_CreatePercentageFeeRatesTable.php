<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306191133CreatePercentageFeeRatesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create percentage_fee_rates table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE percentage_fee_rates (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    fee_id INTEGER NOT NULL,
                    vehicle_type_id INTEGER NOT NULL,
                    rate DOUBLE PRECISION NOT NULL,
                    min_amount DOUBLE PRECISION DEFAULT NULL,
                    max_amount DOUBLE PRECISION DEFAULT NULL,
                    CONSTRAINT FK_FEE_RATE_FEE_ID FOREIGN KEY (fee_id) REFERENCES fees (id),
                    CONSTRAINT FK_FEE_RATE_VEHICLE_TYPE_ID FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types (id)  
            )',
        );

        $this->addSql('CREATE INDEX IDX_5D3A3A3A4D1C8A3A ON percentage_fee_rates (fee_id)');
        $this->addSql('CREATE INDEX IDX_5D3A3A3A8D93D649 ON percentage_fee_rates (vehicle_type_id)');
        $this->addSql(
            'CREATE UNIQUE INDEX UNIQ_5D3A3A3A4D1C8A3A8D93D649 ON percentage_fee_rates (fee_id, vehicle_type_id)',
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE percentage_fee_rates');
    }
}
