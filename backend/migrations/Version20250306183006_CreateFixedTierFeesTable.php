<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306183006CreateFixedTierFeesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create fixed_tier_fees table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fixed_tier_fee (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, 
            fee_id INTEGER NOT NULL, 
            min_amount DOUBLE PRECISION DEFAULT NULL, 
            max_amount DOUBLE PRECISION DEFAULT NULL, 
            amount DOUBLE PRECISION NOT NULL,
            CONSTRAINT FK_FIXED_TIER_FEE__FEE FOREIGN KEY (fee_id) REFERENCES fees (id)
            )
        ');

        $this->addSql('CREATE INDEX IDX_FIXED_TIER_FEE_FEE_ID ON fixed_tier_fee (fee_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE fixed_tier_fee');
    }
}
