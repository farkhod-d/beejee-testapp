<?php

declare(strict_types=1);

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190714164533 extends AbstractMigration
{
    public function getDescription(): string
    {
        $description = 'This is the initial migration which creates blog tables.';
        return $description;
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('issues');
        $table->addColumn('id', 'integer', ['autoincrement' => true, "unsigned" => true, 'notnull' => true]);
        $table->addColumn('user_name', 'string', ['notnull' => true, 'length' => 150]);
        $table->addColumn('user_email', 'string', ['notnull' => true, 'length' => 250]);
        $table->addColumn('status', 'smallint', ["unsigned" => true, 'notnull' => true, 'length' => 4]);
        $table->addColumn('created_at', 'datetime', ['notnull' => true]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('note', 'text', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('issues');
    }
}
