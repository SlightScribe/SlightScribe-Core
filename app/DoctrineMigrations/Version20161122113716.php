<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161122113716 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE access_point (id BIGINT AUTO_INCREMENT NOT NULL, project_version_id BIGINT NOT NULL, communication_id BIGINT NOT NULL, from_old_version_id BIGINT DEFAULT NULL, created_at DATETIME NOT NULL, public_id VARCHAR(250) NOT NULL, title_admin VARCHAR(250) NOT NULL, form LONGTEXT DEFAULT NULL, INDEX IDX_5E308F77531A5C37 (project_version_id), INDEX IDX_5E308F771C2D1E0C (communication_id), INDEX IDX_5E308F7799BD42BD (from_old_version_id), UNIQUE INDEX public_id (project_version_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE access_point_has_field (access_point_id BIGINT NOT NULL, field_id BIGINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BE6413AD3D0F93 (access_point_id), INDEX IDX_B6BE6413443707B0 (field_id), PRIMARY KEY(access_point_id, field_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE access_point_has_file (access_point_id BIGINT NOT NULL, file_id BIGINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7F3D5D90AD3D0F93 (access_point_id), INDEX IDX_7F3D5D9093CB796C (file_id), PRIMARY KEY(access_point_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_email (id BIGINT AUTO_INCREMENT NOT NULL, email VARCHAR(250) NOT NULL, email_clean VARCHAR(250) NOT NULL, started_at DATETIME NOT NULL, finished_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE block_ip (id BIGINT AUTO_INCREMENT NOT NULL, ip VARCHAR(250) NOT NULL, started_at DATETIME NOT NULL, finished_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication (id BIGINT AUTO_INCREMENT NOT NULL, project_version_id BIGINT NOT NULL, from_old_version_id BIGINT DEFAULT NULL, created_at DATETIME NOT NULL, sequence SMALLINT NOT NULL, public_id VARCHAR(250) NOT NULL, title_admin VARCHAR(250) NOT NULL, days_before SMALLINT DEFAULT NULL, email_content_text_template LONGTEXT DEFAULT NULL, email_content_html_template LONGTEXT DEFAULT NULL, email_subject_template LONGTEXT DEFAULT NULL, INDEX IDX_F9AFB5EB531A5C37 (project_version_id), INDEX IDX_F9AFB5EB99BD42BD (from_old_version_id), UNIQUE INDEX public_id (project_version_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE communication_has_file (communication_id BIGINT NOT NULL, file_id BIGINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5A08848C1C2D1E0C (communication_id), INDEX IDX_5A08848C93CB796C (file_id), PRIMARY KEY(communication_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE field (id BIGINT AUTO_INCREMENT NOT NULL, project_id BIGINT NOT NULL, created_at DATETIME NOT NULL, type VARCHAR(250) NOT NULL, public_id VARCHAR(250) NOT NULL, title_admin VARCHAR(250) NOT NULL, sort SMALLINT NOT NULL, `label` VARCHAR(250) NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_5BF54558B5B48B91 (public_id), INDEX IDX_5BF54558166D1F9C (project_id), UNIQUE INDEX public_id (project_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id BIGINT AUTO_INCREMENT NOT NULL, project_version_id BIGINT NOT NULL, from_old_version_id BIGINT DEFAULT NULL, created_at DATETIME NOT NULL, public_id VARCHAR(250) NOT NULL, title_admin VARCHAR(250) NOT NULL, filename VARCHAR(250) NOT NULL, type VARCHAR(250) NOT NULL, letter_content_template LONGTEXT DEFAULT NULL, INDEX IDX_8C9F3610531A5C37 (project_version_id), INDEX IDX_8C9F361099BD42BD (from_old_version_id), UNIQUE INDEX public_id (project_version_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id BIGINT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, created_at DATETIME NOT NULL, public_id VARCHAR(250) NOT NULL, title_admin VARCHAR(250) NOT NULL, UNIQUE INDEX UNIQ_2FB3D0EEB5B48B91 (public_id), INDEX IDX_2FB3D0EE7E3C61F9 (owner_id), UNIQUE INDEX public_id (public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_version (id BIGINT AUTO_INCREMENT NOT NULL, project_id BIGINT NOT NULL, from_old_version_id BIGINT DEFAULT NULL, created_at DATETIME NOT NULL, public_id VARCHAR(250) NOT NULL, title_admin VARCHAR(250) NOT NULL, redirect_user_to_after_manual_stop LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_2902DFA6B5B48B91 (public_id), INDEX IDX_2902DFA6166D1F9C (project_id), INDEX IDX_2902DFA699BD42BD (from_old_version_id), UNIQUE INDEX public_id (project_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_version_has_default_access_point (project_version_id BIGINT NOT NULL, access_point_id BIGINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_662E0664AD3D0F93 (access_point_id), PRIMARY KEY(project_version_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_version_published (id BIGINT AUTO_INCREMENT NOT NULL, project_version_id BIGINT NOT NULL, published_by_id INT DEFAULT NULL, published_at DATETIME NOT NULL, comment_published_admin LONGTEXT DEFAULT NULL, INDEX IDX_52584E46531A5C37 (project_version_id), INDEX IDX_52584E465B075477 (published_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE run (id BIGINT AUTO_INCREMENT NOT NULL, project_id BIGINT NOT NULL, project_version_id BIGINT NOT NULL, public_id VARCHAR(250) NOT NULL, security_key VARCHAR(250) NOT NULL, email VARCHAR(250) NOT NULL, email_clean VARCHAR(250) NOT NULL, created_by_ip VARCHAR(250) NOT NULL, created_at DATETIME NOT NULL, finished_naturally_at DATETIME DEFAULT NULL, stopped_manually_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_5076A4C0B5B48B91 (public_id), INDEX IDX_5076A4C0166D1F9C (project_id), INDEX IDX_5076A4C0531A5C37 (project_version_id), UNIQUE INDEX public_id (project_id, public_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE run_has_communication (run_id BIGINT NOT NULL, communication_id BIGINT NOT NULL, created_at DATETIME NOT NULL, sent_at DATETIME DEFAULT NULL, email_content_text LONGTEXT DEFAULT NULL, email_content_html LONGTEXT DEFAULT NULL, email_subject LONGTEXT DEFAULT NULL, INDEX IDX_665C09FF84E3FEC4 (run_id), INDEX IDX_665C09FF1C2D1E0C (communication_id), PRIMARY KEY(run_id, communication_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE run_has_communication_file (run_id BIGINT NOT NULL, communication_id BIGINT NOT NULL, file_id BIGINT NOT NULL, created_at DATETIME NOT NULL, sent_at DATETIME DEFAULT NULL, letter_content LONGTEXT DEFAULT NULL, filename VARCHAR(250) DEFAULT NULL, INDEX IDX_30D7D5F884E3FEC4 (run_id), INDEX IDX_30D7D5F81C2D1E0C (communication_id), INDEX IDX_30D7D5F893CB796C (file_id), PRIMARY KEY(run_id, communication_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE run_has_field (run_id BIGINT NOT NULL, field_id BIGINT NOT NULL, created_at DATETIME NOT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_65FD581A84E3FEC4 (run_id), INDEX IDX_65FD581A443707B0 (field_id), PRIMARY KEY(run_id, field_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE run_has_file (run_id BIGINT NOT NULL, file_id BIGINT NOT NULL, created_at DATETIME NOT NULL, sent_at DATETIME DEFAULT NULL, letter_content LONGTEXT DEFAULT NULL, filename VARCHAR(250) DEFAULT NULL, INDEX IDX_EAF5AFF784E3FEC4 (run_id), INDEX IDX_EAF5AFF793CB796C (file_id), PRIMARY KEY(run_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE run_used_access_point (access_point_id BIGINT NOT NULL, run_id BIGINT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_7D8644BCAD3D0F93 (access_point_id), INDEX IDX_7D8644BC84E3FEC4 (run_id), PRIMARY KEY(access_point_id, run_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE access_point ADD CONSTRAINT FK_5E308F77531A5C37 FOREIGN KEY (project_version_id) REFERENCES project_version (id)');
        $this->addSql('ALTER TABLE access_point ADD CONSTRAINT FK_5E308F771C2D1E0C FOREIGN KEY (communication_id) REFERENCES communication (id)');
        $this->addSql('ALTER TABLE access_point ADD CONSTRAINT FK_5E308F7799BD42BD FOREIGN KEY (from_old_version_id) REFERENCES access_point (id)');
        $this->addSql('ALTER TABLE access_point_has_field ADD CONSTRAINT FK_B6BE6413AD3D0F93 FOREIGN KEY (access_point_id) REFERENCES access_point (id)');
        $this->addSql('ALTER TABLE access_point_has_field ADD CONSTRAINT FK_B6BE6413443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE access_point_has_file ADD CONSTRAINT FK_7F3D5D90AD3D0F93 FOREIGN KEY (access_point_id) REFERENCES access_point (id)');
        $this->addSql('ALTER TABLE access_point_has_file ADD CONSTRAINT FK_7F3D5D9093CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EB531A5C37 FOREIGN KEY (project_version_id) REFERENCES project_version (id)');
        $this->addSql('ALTER TABLE communication ADD CONSTRAINT FK_F9AFB5EB99BD42BD FOREIGN KEY (from_old_version_id) REFERENCES communication (id)');
        $this->addSql('ALTER TABLE communication_has_file ADD CONSTRAINT FK_5A08848C1C2D1E0C FOREIGN KEY (communication_id) REFERENCES communication (id)');
        $this->addSql('ALTER TABLE communication_has_file ADD CONSTRAINT FK_5A08848C93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE field ADD CONSTRAINT FK_5BF54558166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610531A5C37 FOREIGN KEY (project_version_id) REFERENCES project_version (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361099BD42BD FOREIGN KEY (from_old_version_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_version ADD CONSTRAINT FK_2902DFA6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_version ADD CONSTRAINT FK_2902DFA699BD42BD FOREIGN KEY (from_old_version_id) REFERENCES project_version (id)');
        $this->addSql('ALTER TABLE project_version_has_default_access_point ADD CONSTRAINT FK_662E0664531A5C37 FOREIGN KEY (project_version_id) REFERENCES project_version (id)');
        $this->addSql('ALTER TABLE project_version_has_default_access_point ADD CONSTRAINT FK_662E0664AD3D0F93 FOREIGN KEY (access_point_id) REFERENCES access_point (id)');
        $this->addSql('ALTER TABLE project_version_published ADD CONSTRAINT FK_52584E46531A5C37 FOREIGN KEY (project_version_id) REFERENCES project_version (id)');
        $this->addSql('ALTER TABLE project_version_published ADD CONSTRAINT FK_52584E465B075477 FOREIGN KEY (published_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE run ADD CONSTRAINT FK_5076A4C0166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE run ADD CONSTRAINT FK_5076A4C0531A5C37 FOREIGN KEY (project_version_id) REFERENCES project_version (id)');
        $this->addSql('ALTER TABLE run_has_communication ADD CONSTRAINT FK_665C09FF84E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
        $this->addSql('ALTER TABLE run_has_communication ADD CONSTRAINT FK_665C09FF1C2D1E0C FOREIGN KEY (communication_id) REFERENCES communication (id)');
        $this->addSql('ALTER TABLE run_has_communication_file ADD CONSTRAINT FK_30D7D5F884E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
        $this->addSql('ALTER TABLE run_has_communication_file ADD CONSTRAINT FK_30D7D5F81C2D1E0C FOREIGN KEY (communication_id) REFERENCES communication (id)');
        $this->addSql('ALTER TABLE run_has_communication_file ADD CONSTRAINT FK_30D7D5F893CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE run_has_field ADD CONSTRAINT FK_65FD581A84E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
        $this->addSql('ALTER TABLE run_has_field ADD CONSTRAINT FK_65FD581A443707B0 FOREIGN KEY (field_id) REFERENCES field (id)');
        $this->addSql('ALTER TABLE run_has_file ADD CONSTRAINT FK_EAF5AFF784E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
        $this->addSql('ALTER TABLE run_has_file ADD CONSTRAINT FK_EAF5AFF793CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE run_used_access_point ADD CONSTRAINT FK_7D8644BCAD3D0F93 FOREIGN KEY (access_point_id) REFERENCES access_point (id)');
        $this->addSql('ALTER TABLE run_used_access_point ADD CONSTRAINT FK_7D8644BC84E3FEC4 FOREIGN KEY (run_id) REFERENCES run (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE access_point DROP FOREIGN KEY FK_5E308F7799BD42BD');
        $this->addSql('ALTER TABLE access_point_has_field DROP FOREIGN KEY FK_B6BE6413AD3D0F93');
        $this->addSql('ALTER TABLE access_point_has_file DROP FOREIGN KEY FK_7F3D5D90AD3D0F93');
        $this->addSql('ALTER TABLE project_version_has_default_access_point DROP FOREIGN KEY FK_662E0664AD3D0F93');
        $this->addSql('ALTER TABLE run_used_access_point DROP FOREIGN KEY FK_7D8644BCAD3D0F93');
        $this->addSql('ALTER TABLE access_point DROP FOREIGN KEY FK_5E308F771C2D1E0C');
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EB99BD42BD');
        $this->addSql('ALTER TABLE communication_has_file DROP FOREIGN KEY FK_5A08848C1C2D1E0C');
        $this->addSql('ALTER TABLE run_has_communication DROP FOREIGN KEY FK_665C09FF1C2D1E0C');
        $this->addSql('ALTER TABLE run_has_communication_file DROP FOREIGN KEY FK_30D7D5F81C2D1E0C');
        $this->addSql('ALTER TABLE access_point_has_field DROP FOREIGN KEY FK_B6BE6413443707B0');
        $this->addSql('ALTER TABLE run_has_field DROP FOREIGN KEY FK_65FD581A443707B0');
        $this->addSql('ALTER TABLE access_point_has_file DROP FOREIGN KEY FK_7F3D5D9093CB796C');
        $this->addSql('ALTER TABLE communication_has_file DROP FOREIGN KEY FK_5A08848C93CB796C');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361099BD42BD');
        $this->addSql('ALTER TABLE run_has_communication_file DROP FOREIGN KEY FK_30D7D5F893CB796C');
        $this->addSql('ALTER TABLE run_has_file DROP FOREIGN KEY FK_EAF5AFF793CB796C');
        $this->addSql('ALTER TABLE field DROP FOREIGN KEY FK_5BF54558166D1F9C');
        $this->addSql('ALTER TABLE project_version DROP FOREIGN KEY FK_2902DFA6166D1F9C');
        $this->addSql('ALTER TABLE run DROP FOREIGN KEY FK_5076A4C0166D1F9C');
        $this->addSql('ALTER TABLE access_point DROP FOREIGN KEY FK_5E308F77531A5C37');
        $this->addSql('ALTER TABLE communication DROP FOREIGN KEY FK_F9AFB5EB531A5C37');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610531A5C37');
        $this->addSql('ALTER TABLE project_version DROP FOREIGN KEY FK_2902DFA699BD42BD');
        $this->addSql('ALTER TABLE project_version_has_default_access_point DROP FOREIGN KEY FK_662E0664531A5C37');
        $this->addSql('ALTER TABLE project_version_published DROP FOREIGN KEY FK_52584E46531A5C37');
        $this->addSql('ALTER TABLE run DROP FOREIGN KEY FK_5076A4C0531A5C37');
        $this->addSql('ALTER TABLE run_has_communication DROP FOREIGN KEY FK_665C09FF84E3FEC4');
        $this->addSql('ALTER TABLE run_has_communication_file DROP FOREIGN KEY FK_30D7D5F884E3FEC4');
        $this->addSql('ALTER TABLE run_has_field DROP FOREIGN KEY FK_65FD581A84E3FEC4');
        $this->addSql('ALTER TABLE run_has_file DROP FOREIGN KEY FK_EAF5AFF784E3FEC4');
        $this->addSql('ALTER TABLE run_used_access_point DROP FOREIGN KEY FK_7D8644BC84E3FEC4');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE7E3C61F9');
        $this->addSql('ALTER TABLE project_version_published DROP FOREIGN KEY FK_52584E465B075477');
        $this->addSql('DROP TABLE access_point');
        $this->addSql('DROP TABLE access_point_has_field');
        $this->addSql('DROP TABLE access_point_has_file');
        $this->addSql('DROP TABLE block_email');
        $this->addSql('DROP TABLE block_ip');
        $this->addSql('DROP TABLE communication');
        $this->addSql('DROP TABLE communication_has_file');
        $this->addSql('DROP TABLE field');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_version');
        $this->addSql('DROP TABLE project_version_has_default_access_point');
        $this->addSql('DROP TABLE project_version_published');
        $this->addSql('DROP TABLE run');
        $this->addSql('DROP TABLE run_has_communication');
        $this->addSql('DROP TABLE run_has_communication_file');
        $this->addSql('DROP TABLE run_has_field');
        $this->addSql('DROP TABLE run_has_file');
        $this->addSql('DROP TABLE run_used_access_point');
        $this->addSql('DROP TABLE user');
    }
}
