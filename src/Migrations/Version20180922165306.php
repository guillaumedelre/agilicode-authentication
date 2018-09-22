<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180922165306 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE privilege (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_87209A87EA750E8 (label), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_privilege (user_id INT NOT NULL, privilege_id INT NOT NULL, INDEX IDX_87C01763A76ED395 (user_id), UNIQUE INDEX UNIQ_87C0176332FB8AEA (privilege_id), PRIMARY KEY(user_id, privilege_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(500) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_privilege ADD CONSTRAINT FK_87C01763A76ED395 FOREIGN KEY (user_id) REFERENCES privilege (id)');
        $this->addSql('ALTER TABLE user_privilege ADD CONSTRAINT FK_87C0176332FB8AEA FOREIGN KEY (privilege_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C01763A76ED395');
        $this->addSql('ALTER TABLE user_privilege DROP FOREIGN KEY FK_87C0176332FB8AEA');
        $this->addSql('DROP TABLE privilege');
        $this->addSql('DROP TABLE user_privilege');
        $this->addSql('DROP TABLE user');
    }
}
