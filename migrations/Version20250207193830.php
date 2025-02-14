<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207193830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reviews (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, product_id_id INT DEFAULT NULL, rating INT NOT NULL, review_text VARCHAR(1023) NOT NULL, status INT NOT NULL, INDEX IDX_6970EB0F9D86650F (user_id_id), INDEX IDX_6970EB0FDE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0F9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reviews ADD CONSTRAINT FK_6970EB0FDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD8092D97F');
        $this->addSql('DROP INDEX IDX_D34A04AD8092D97F ON product');
        $this->addSql('ALTER TABLE product DROP reviews_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498092D97F');
        $this->addSql('DROP INDEX IDX_8D93D6498092D97F ON user');
        $this->addSql('ALTER TABLE user DROP reviews_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0F9D86650F');
        $this->addSql('ALTER TABLE reviews DROP FOREIGN KEY FK_6970EB0FDE18E50B');
        $this->addSql('DROP TABLE reviews');
        $this->addSql('ALTER TABLE user ADD reviews_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498092D97F FOREIGN KEY (reviews_id) REFERENCES reviews (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D6498092D97F ON user (reviews_id)');
        $this->addSql('ALTER TABLE product ADD reviews_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD8092D97F FOREIGN KEY (reviews_id) REFERENCES reviews (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D34A04AD8092D97F ON product (reviews_id)');
    }
}
