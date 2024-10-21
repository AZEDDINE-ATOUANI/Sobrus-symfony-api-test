<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241020000100 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE blog_article (
            id INT AUTO_INCREMENT NOT NULL,
            author_id INT NOT NULL,
            title VARCHAR(100) NOT NULL,
            publication_date DATETIME NOT NULL,
            creation_date DATETIME NOT NULL,
            content TEXT NOT NULL,
            keywords JSON NOT NULL,
            slug VARCHAR(255) NOT NULL,
            cover_picture_ref VARCHAR(255) DEFAULT NULL,
            status ENUM(\'draft\', \'published\', \'deleted\') NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE blog_article');
    }
}
