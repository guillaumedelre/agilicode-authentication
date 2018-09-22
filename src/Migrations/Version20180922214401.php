<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180922214401 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE privilege (id INT AUTO_INCREMENT NOT NULL, application_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_87209A873E030ACD (application_id), INDEX IDX_87209A87A76ED395 (user_id), UNIQUE INDEX privilege_unique_application_user (application_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, UNIQUE INDEX role_unique_label (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_privilege (user_id INT NOT NULL, privilege_id INT NOT NULL, INDEX IDX_87C01763A76ED395 (user_id), UNIQUE INDEX UNIQ_87C0176332FB8AEA (privilege_id), PRIMARY KEY(user_id, privilege_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(500) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX user_unique_username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX application_unique_label (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE privilege ADD CONSTRAINT FK_87209A873E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE privilege ADD CONSTRAINT FK_87209A87A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_privilege ADD CONSTRAINT FK_87C01763A76ED395 FOREIGN KEY (user_id) REFERENCES user_role (id)');
        $this->addSql('ALTER TABLE user_privilege ADD CONSTRAINT FK_87C0176332FB8AEA FOREIGN KEY (privilege_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C01763A76ED395');
        $this->addSql('ALTER TABLE privilege DROP FOREIGN KEY FK_87209A87A76ED395');
        $this->addSql('ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C0176332FB8AEA');
        $this->addSql('ALTER TABLE privilege DROP FOREIGN KEY FK_87209A873E030ACD');
        $this->addSql('DROP TABLE privilege');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE user_privilege');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE application');
    }
}
