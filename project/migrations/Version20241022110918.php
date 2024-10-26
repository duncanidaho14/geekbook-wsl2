<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022110918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE invoice_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE plan_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE invoice (id INT NOT NULL, subscription_id INT NOT NULL, stripe_id VARCHAR(255) NOT NULL, amount_paid DOUBLE PRECISION NOT NULL, reference VARCHAR(255) NOT NULL, reference_url VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_906517449A1887DC ON invoice (subscription_id)');
        $this->addSql('COMMENT ON COLUMN invoice.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE plan (id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stripe_id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517449A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription ADD plan_id INT NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD stripe_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD current_period_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE subscription ADD current_period_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE subscription DROP choice_subscriber');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E899029B FOREIGN KEY (plan_id) REFERENCES plan (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A3C664D3E899029B ON subscription (plan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE subscription DROP CONSTRAINT FK_A3C664D3E899029B');
        $this->addSql('DROP SEQUENCE invoice_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE plan_id_seq CASCADE');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517449A1887DC');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE plan');
        $this->addSql('DROP INDEX IDX_A3C664D3E899029B');
        $this->addSql('ALTER TABLE subscription ADD choice_subscriber JSON NOT NULL');
        $this->addSql('ALTER TABLE subscription DROP plan_id');
        $this->addSql('ALTER TABLE subscription DROP stripe_id');
        $this->addSql('ALTER TABLE subscription DROP current_period_start');
        $this->addSql('ALTER TABLE subscription DROP current_period_end');
    }
}
