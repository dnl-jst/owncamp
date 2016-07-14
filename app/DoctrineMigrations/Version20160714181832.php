<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160714181832 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, taskId INT DEFAULT NULL, createdBy INT DEFAULT NULL, INDEX IDX_5F9E962AD34FCA37 (taskId), INDEX IDX_5F9E962AD3564642 (createdBy), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE files (id INT AUTO_INCREMENT NOT NULL, filename LONGTEXT DEFAULT NULL, value_blob LONGBLOB NOT NULL, value_blob_type VARCHAR(50) NOT NULL, value_blob_ext VARCHAR(10) NOT NULL, taskId INT DEFAULT NULL, commentId INT DEFAULT NULL, INDEX IDX_6354059D34FCA37 (taskId), INDEX IDX_63540596690C3F5 (commentId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, role VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_B63E2EC757698A6A (role), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, finished DATETIME NOT NULL, taskSetId INT DEFAULT NULL, createdBy INT DEFAULT NULL, assignedTo INT DEFAULT NULL, INDEX IDX_50586597A36330D6 (taskSetId), INDEX IDX_50586597D3564642 (createdBy), INDEX IDX_505865975B7F11F7 (assignedTo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_sets (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, projectId INT DEFAULT NULL, INDEX IDX_E73ED1AA6C9360F7 (projectId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, password_reset_hash VARCHAR(32) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_project (user_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_77BECEE4A76ED395 (user_id), INDEX IDX_77BECEE4166D1F9C (project_id), PRIMARY KEY(user_id, project_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD34FCA37 FOREIGN KEY (taskId) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD3564642 FOREIGN KEY (createdBy) REFERENCES users (id)');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_6354059D34FCA37 FOREIGN KEY (taskId) REFERENCES tasks (id)');
        $this->addSql('ALTER TABLE files ADD CONSTRAINT FK_63540596690C3F5 FOREIGN KEY (commentId) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597A36330D6 FOREIGN KEY (taskSetId) REFERENCES task_sets (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597D3564642 FOREIGN KEY (createdBy) REFERENCES users (id)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_505865975B7F11F7 FOREIGN KEY (assignedTo) REFERENCES users (id)');
        $this->addSql('ALTER TABLE task_sets ADD CONSTRAINT FK_E73ED1AA6C9360F7 FOREIGN KEY (projectId) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_project ADD CONSTRAINT FK_77BECEE4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_project ADD CONSTRAINT FK_77BECEE4166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_63540596690C3F5');
        $this->addSql('ALTER TABLE task_sets DROP FOREIGN KEY FK_E73ED1AA6C9360F7');
        $this->addSql('ALTER TABLE user_project DROP FOREIGN KEY FK_77BECEE4166D1F9C');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD34FCA37');
        $this->addSql('ALTER TABLE files DROP FOREIGN KEY FK_6354059D34FCA37');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597A36330D6');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD3564642');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_50586597D3564642');
        $this->addSql('ALTER TABLE tasks DROP FOREIGN KEY FK_505865975B7F11F7');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_project DROP FOREIGN KEY FK_77BECEE4A76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('DROP TABLE task_sets');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE user_project');
    }
}
