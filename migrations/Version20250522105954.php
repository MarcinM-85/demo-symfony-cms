<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250522105954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD auth_code_expires_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE roles roles LONGTEXT NOT NULL, CHANGE auth_code auth_code VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)
        SQL);

        $this->initalData();
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649F85E0677 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP auth_code_expires_at, CHANGE roles roles JSON NOT NULL COMMENT '(DC2Type:json)', CHANGE auth_code auth_code VARCHAR(255) NOT NULL
        SQL);
    }
    
    protected function initalData(): void
    {
        $this->addSql(<<<'SQL'
            INSERT INTO user (username, password, email, roles) VALUES ('headadmin', '$2y$13$RlaW3hcWv0oZBBeddj.EsO2P/IRA6U2rc7r0hBui1vKigdxFgNlTS', 'headadmin@example.com', '["ROLE_SUPER_ADMIN"]')
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user (username, password, email, roles) VALUES ('admin', '$2y$13$aJYtM/9HybZgQ/22wInfpe8lZpxWrDtIOFUBfDOoIJPh.MLSNkPba', 'admin@example.com', '["ROLE_ADMIN"]')
        SQL);
    }
}
