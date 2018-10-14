<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181015121456 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE preference (id INT AUTO_INCREMENT NOT NULL, application_id INT NOT NULL, user_id INT NOT NULL, data JSON NOT NULL COMMENT \'(DC2Type:json_document)\', INDEX IDX_5D69B0533E030ACD (application_id), INDEX IDX_5D69B053A76ED395 (user_id), UNIQUE INDEX preference__unique__user_application (user_id, application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, application_id INT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_57698A6A3E030ACD (application_id), UNIQUE INDEX role__unique__label__application (label, application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, application_id INT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_E04992AA3E030ACD (application_id), UNIQUE INDEX permission__unique__label_application (label, application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE privilege (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, application_id INT NOT NULL, INDEX IDX_87209A87A76ED395 (user_id), INDEX IDX_87209A873E030ACD (application_id), UNIQUE INDEX privilege__unique__user_application (user_id, application_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE privilege_role (privilege_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_97F8DF5F32FB8AEA (privilege_id), INDEX IDX_97F8DF5FD60322AC (role_id), PRIMARY KEY(privilege_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(500) NOT NULL, enabled TINYINT(1) DEFAULT \'0\' NOT NULL, service TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX user__unique__username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, enabled TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX application__unique__label (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B0533E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B053A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role ADD CONSTRAINT FK_57698A6A3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AA3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE privilege ADD CONSTRAINT FK_87209A87A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE privilege ADD CONSTRAINT FK_87209A873E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE privilege_role ADD CONSTRAINT FK_97F8DF5F32FB8AEA FOREIGN KEY (privilege_id) REFERENCES privilege (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE privilege_role ADD CONSTRAINT FK_97F8DF5FD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE privilege_role DROP FOREIGN KEY FK_97F8DF5FD60322AC');
        $this->addSql('ALTER TABLE privilege_role DROP FOREIGN KEY FK_97F8DF5F32FB8AEA');
        $this->addSql('ALTER TABLE preference DROP FOREIGN KEY FK_5D69B053A76ED395');
        $this->addSql('ALTER TABLE privilege DROP FOREIGN KEY FK_87209A87A76ED395');
        $this->addSql('ALTER TABLE preference DROP FOREIGN KEY FK_5D69B0533E030ACD');
        $this->addSql('ALTER TABLE role DROP FOREIGN KEY FK_57698A6A3E030ACD');
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AA3E030ACD');
        $this->addSql('ALTER TABLE privilege DROP FOREIGN KEY FK_87209A873E030ACD');
        $this->addSql('DROP TABLE preference');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE privilege');
        $this->addSql('DROP TABLE privilege_role');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE application');
    }
}
