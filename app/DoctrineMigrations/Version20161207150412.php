<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161207150412 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file ADD letter_content_template_header_right LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE file_template_error ADD letter_content_template_header_right LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE run_has_communication_file ADD letter_content_header_right LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE run_has_file ADD letter_content_header_right LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE file DROP letter_content_template_header_right');
        $this->addSql('ALTER TABLE file_template_error DROP letter_content_template_header_right');
        $this->addSql('ALTER TABLE run_has_communication_file DROP letter_content_header_right');
        $this->addSql('ALTER TABLE run_has_file DROP letter_content_header_right');
    }
}
