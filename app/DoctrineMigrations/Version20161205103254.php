<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161205103254 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE communication_template_error (id BIGINT AUTO_INCREMENT NOT NULL, communication_id BIGINT NOT NULL, run_id BIGINT DEFAULT NULL, created_at DATETIME NOT NULL, email_content_text_template LONGTEXT DEFAULT NULL, email_content_html_template LONGTEXT DEFAULT NULL, email_subject_template LONGTEXT DEFAULT NULL, twig_variables LONGTEXT DEFAULT NULL, error_message LONGTEXT DEFAULT NULL, error_code INT DEFAULT NULL, error_file LONGTEXT DEFAULT NULL, error_line INT DEFAULT NULL, INDEX IDX_21798BFE1C2D1E0C (communication_id), INDEX IDX_21798BFE84E3FEC4 (run_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE communication_template_error ADD CONSTRAINT FK_21798BFE1C2D1E0C FOREIGN KEY (communication_id) REFERENCES communication (id)');
        $this->addSql('ALTER TABLE communication_template_error ADD CONSTRAINT FK_21798BFE84E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE communication_template_error');
    }
}
