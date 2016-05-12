<?php

namespace TestHub\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migration
 */
class Version20160512133552 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $question = $schema->getTable('question');

        $variant = $schema->createTable('variant');
        $variant->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $variant->addColumn('value', 'string');
        $variant->addColumn('is_right', 'string');
        $variant->addColumn('question_id', 'integer');
        $variant->addForeignKeyConstraint($question, ['question_id'], ['id'], [
            'onUpdate' => 'CASCADE',
            'onDelete' => 'CASCADE',
        ]);

        $variant->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('variant');
    }
}
