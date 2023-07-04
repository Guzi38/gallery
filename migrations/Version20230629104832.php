<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629104832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, photo_id INT DEFAULT NULL, author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5F9E962A7E9E4C8C (photo_id), INDEX IDX_5F9E962AF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galleries (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, slug VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, gallery_id INT NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(120) NOT NULL, content LONGTEXT NOT NULL, filename VARCHAR(191) DEFAULT NULL, INDEX IDX_876E0D94E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos_tags (photo_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F8A626C77E9E4C8C (photo_id), INDEX IDX_F8A626C7BAD26311 (tag_id), PRIMARY KEY(photo_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', title VARCHAR(255) NOT NULL, slug VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, login VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7E9E4C8C FOREIGN KEY (photo_id) REFERENCES photos (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D94E7AF8F FOREIGN KEY (gallery_id) REFERENCES galleries (id)');
        $this->addSql('ALTER TABLE photos_tags ADD CONSTRAINT FK_F8A626C77E9E4C8C FOREIGN KEY (photo_id) REFERENCES photos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photos_tags ADD CONSTRAINT FK_F8A626C7BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A7E9E4C8C');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B');
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D94E7AF8F');
        $this->addSql('ALTER TABLE photos_tags DROP FOREIGN KEY FK_F8A626C77E9E4C8C');
        $this->addSql('ALTER TABLE photos_tags DROP FOREIGN KEY FK_F8A626C7BAD26311');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE galleries');
        $this->addSql('DROP TABLE photos');
        $this->addSql('DROP TABLE photos_tags');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
