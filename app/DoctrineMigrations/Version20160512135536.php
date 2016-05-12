<?php

namespace TestHub\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration
 */
class Version20160512135536 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $user = $schema->getTable('user');

        $attempt = $schema->createTable('attempt');
        $attempt->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $attempt->addColumn('started', 'datetime');
        $attempt->addColumn('user_id', 'integer');
        $attempt->addForeignKeyConstraint($user, ['user_id'], ['id'], [
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE',
        ]);

        $attempt->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('attempt');
    }
}
