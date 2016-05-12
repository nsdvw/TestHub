<?php

namespace TestHub\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration
 */
class Version20160513005635 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $attempt = $schema->getTable('attempt');
        $attempt->addColumn('test_id', 'integer');
        $attempt->addForeignKeyConstraint('test', ['test_id'], ['id'], [
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE',
        ]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $attempt = $schema->getTable('attempt');
        $attempt->dropColumn('test_id');
    }
}
