<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161205093821 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE file_template_error (id BIGINT AUTO_INCREMENT NOT NULL, file_id BIGINT NOT NULL, run_id BIGINT DEFAULT NULL, created_at DATETIME NOT NULL, letter_content_template LONGTEXT DEFAULT NULL, twig_variables LONGTEXT DEFAULT NULL, error_message LONGTEXT DEFAULT NULL, error_code INT DEFAULT NULL, error_file LONGTEXT DEFAULT NULL, error_line INT DEFAULT NULL, INDEX IDX_F3D4D6E593CB796C (file_id), INDEX IDX_F3D4D6E584E3FEC4 (run_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file_template_error ADD CONSTRAINT FK_F3D4D6E593CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE file_template_error ADD CONSTRAINT FK_F3D4D6E584E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE file_template_error');
    }
}
