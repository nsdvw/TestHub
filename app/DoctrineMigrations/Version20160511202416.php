<?php

namespace TestHub\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration
 */
class Version20160511202416 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $userTable = $schema->getTable('user');

        $testTable = $schema->createTable('test');
        $testTable->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $testTable->addColumn('user_id', 'integer');
        $testTable->addForeignKeyConstraint($userTable, ['user_id'], ['id'], [
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE',
        ]);
        $testTable->addColumn('title', 'string');
        $testTable->addColumn('description', 'text', [
            'notnull' => false,
        ]);
        $testTable->addColumn('time_limit', 'integer', [
            'default' => 0,
        ]);
        $testTable->addColumn('added', 'datetime');

        $testTable->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('test');
    }
}
