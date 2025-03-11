<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306164346CreateFixedFeesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create fixed_fees table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fixed_fees (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            fee_id INTEGER NOT NULL,
            amount DOUBLE PRECISION NOT NULL,
            CONSTRAINT FK_FIXED_FEES__FEE FOREIGN KEY (fee_id) REFERENCES fees (id)
            )');

        $this->addSql('CREATE INDEX IDX_FIXED_FEES_FEE_ID ON fixed_fees (fee_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE fixed_fees');
    }
}
