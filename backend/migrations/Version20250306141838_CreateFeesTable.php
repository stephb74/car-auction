<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Enum\FeeName;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306141838CreateFeesTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create fees table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE fees (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR(50) NOT NULL CHECK (name IN (' .
                implode(', ', array_map(fn($e) => "'" . $e->value . "'", FeeName::cases())) .
                ')),
            type VARCHAR(50) NOT NULL
        )',
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEE_NAME ON fees (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fees');
    }
}
