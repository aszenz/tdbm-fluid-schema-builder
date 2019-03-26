<?php

namespace TheCodingMachine\FluidSchema;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class TdbmFluidTableTest extends TestCase
{
    public function testColumn()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $column = $posts->column('foo');

        $this->assertTrue($schema->getTable('posts')->hasColumn('foo'));

        $this->assertSame($column, $posts->column('foo'), 'Failed asserting that the same instance is returned.');
    }

    public function testExistingColumn()
    {
        $schema = new Schema();
        $postsSchemaTable = $schema->createTable('posts');
        $postsSchemaTable->addColumn('foo', 'string');
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->column('foo')->integer();

        $this->assertSame(Type::getType(Type::INTEGER), $schema->getTable('posts')->getColumn('foo')->getType());
    }

    public function testIndex()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->column('foo')->integer()->then()->index(['foo']);

        $this->assertCount(1, $schema->getTable('posts')->getIndexes());
    }

    public function testUnique()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->column('foo')->integer()->then()->unique(['foo']);

        $this->assertCount(1, $schema->getTable('posts')->getIndexes());
    }

    public function testPrimaryKey()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->column('id')->integer()->then()->primaryKey(['id'], 'pkname');

        $this->assertTrue($schema->getTable('posts')->hasPrimaryKey());
        $this->assertTrue($schema->getTable('posts')->hasIndex('pkname'));
    }

    public function testId()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->id();

        $this->assertTrue($schema->getTable('posts')->hasPrimaryKey());
        $this->assertTrue($schema->getTable('posts')->hasColumn('id'));
    }

    public function testUuid()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->uuid();

        $this->assertTrue($schema->getTable('posts')->hasPrimaryKey());
        $this->assertTrue($schema->getTable('posts')->hasColumn('uuid'));
        $this->assertSame("\n@UUID(\"v4\")", $schema->getTable('posts')->getColumn('uuid')->getComment());
    }

    public function testUuidBadType()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $this->expectException(FluidSchemaException::class);
        $posts->uuid('v2');
    }

    public function testCustomBeanName()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->customBeanName('Article');

        $this->assertSame("\n@Bean(name = \"Article\")", $schema->getTable('posts')->getOptions()['comment']);
    }

    public function testImplementsInterface()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->implementsInterface('Foo\\Bar');
        $posts->implementsInterface('Foo\\Bar2');

        $this->assertSame("\n@AddInterface(name = \"Foo\\Bar\")\n@AddInterface(name = \"Foo\\Bar2\")", $schema->getTable('posts')->getOptions()['comment']);
    }

    public function testImplementsInterfaceOnDao()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $posts = $fluid->table('posts');

        $posts->implementsInterfaceOnDao('Foo\\Bar');

        $this->assertSame("\n@AddInterfaceOnDao(name = \"Foo\\Bar\")", $schema->getTable('posts')->getOptions()['comment']);
    }

    public function testTimestamps()
    {
        if (defined('Doctrine\\DBAL\\Types\\Type::DATE_IMMUTABLE')) {
            $schema = new Schema();
            $fluid = new TdbmFluidSchema($schema);

            $posts = $fluid->table('posts');

            $posts->timestamps();

            $this->assertTrue($schema->getTable('posts')->hasColumn('created_at'));
            $this->assertTrue($schema->getTable('posts')->hasColumn('updated_at'));
        } else {
            $this->markTestSkipped("Only available from Doctrine DBAL 2.6");
        }
    }

    public function testInherits()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $contacts = $fluid->table('contacts');
        $contacts->id();

        $fluid->table('users')->extends('contacts');

        $dbalColumn = $schema->getTable('users')->getColumn('id');

        $this->assertSame(Type::getType(Type::INTEGER), $dbalColumn->getType());
        $fks = $schema->getTable('users')->getForeignKeys();
        $this->assertCount(1, $fks);
        $fk = array_pop($fks);
        $this->assertSame('users', $fk->getLocalTableName());
        $this->assertSame('contacts', $fk->getForeignTableName());
        $this->assertSame(['id'], $fk->getLocalColumns());
    }

    public function testGetDbalTable()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $contacts = $fluid->table('contacts');
        $this->assertSame('contacts', $contacts->getDbalTable()->getName());
    }
}
