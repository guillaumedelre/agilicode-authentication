<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180928235507 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, application_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_E04992AA3E030ACD (application_id), INDEX IDX_E04992AAA76ED395 (user_id), UNIQUE INDEX permission_unique_application_user (application_id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, UNIQUE INDEX user_role_unique_label (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles_users (user_id INT NOT NULL, user_role_id INT NOT NULL, INDEX IDX_3D80FB2CA76ED395 (user_id), UNIQUE INDEX UNIQ_3D80FB2C8E0E3CA6 (user_role_id), PRIMARY KEY(user_id, user_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(500) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX user_unique_username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX application_unique_label (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AA3E030ACD FOREIGN KEY (application_id) REFERENCES application (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE roles_users ADD CONSTRAINT FK_3D80FB2CA76ED395 FOREIGN KEY (user_id) REFERENCES user_role (id)');
        $this->addSql('ALTER TABLE roles_users ADD CONSTRAINT FK_3D80FB2C8E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE roles_users DROP FOREIGN KEY FK_3D80FB2CA76ED395');
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AAA76ED395');
        $this->addSql('ALTER TABLE roles_users DROP FOREIGN KEY FK_3D80FB2C8E0E3CA6');
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AA3E030ACD');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE roles_users');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE application');
    }
}
