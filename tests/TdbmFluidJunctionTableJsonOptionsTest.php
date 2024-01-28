<?php

namespace TheCodingMachine\FluidSchema;

use Doctrine\DBAL\Schema\Schema;
use PHPUnit\Framework\TestCase;

class TdbmFluidJunctionTableJsonOptionsTest extends TestCase
{
    public function testJson()
    {
        $schema = new Schema();
        $fluid = new TdbmFluidSchema($schema);

        $fluid->table('nodes')
            ->column('id')->integer()->primaryKey()->autoIncrement()->jsonSerialize()->ignore()
            ->column('alias_id')->references('nodes')->null()->jsonSerialize()->recursive()
            ->column('parent_id')->references('nodes')->null()->jsonSerialize()->include()
            ->column('root_id')->references('nodes')->null()->jsonSerialize()->ignore()
            ->column('owner_id')->references('nodes')->null()->jsonSerialize()->formatUsingProperty('name')->include()
            ->column('owner_country')->references('nodes')->null()->jsonSerialize()->formatUsingMethod('myMethod')->include()
            ->column('name')->string()->jsonSerialize()->key('basename')
            ->column('size')->integer()->notNull()->default(0)->jsonSerialize()->numericFormat(null, null, null, ' o')
            ->column('weight')->float()->null()->jsonSerialize()->numericFormat(2, ',', '.', 'g')
            ->column('created_at')->date()->null()->jsonSerialize()->datetimeFormat("Y-m-d")
            ->column('another_parent')->references('nodes')->comment('@JsonCollection("entries") @JsonFormat(property="entry")');

        $nodesTable = $schema->getTable('nodes');
        $this->assertStringContainsString('@JsonIgnore', $nodesTable->getColumn('id')->getComment());
        $this->assertStringContainsString('@JsonRecursive', $nodesTable->getColumn('alias_id')->getComment());
        $this->assertStringContainsString('@JsonInclude', $nodesTable->getColumn('parent_id')->getComment());
        $this->assertStringContainsString('@JsonIgnore', $nodesTable->getColumn('root_id')->getComment());
        $this->assertStringContainsString('@JsonFormat(property = "name")', $nodesTable->getColumn('owner_id')->getComment());
        $this->assertStringContainsString('@JsonFormat(method = "myMethod")', $nodesTable->getColumn('owner_country')->getComment());
        $this->assertStringContainsString('@JsonKey(key = "basename")', $nodesTable->getColumn('name')->getComment());
        $this->assertStringContainsString('@JsonFormat(unit = " o")', $nodesTable->getColumn('size')->getComment());
        $this->assertStringContainsString('@JsonFormat(decimals = 2, point = ",", separator = ".", unit = "g")', $nodesTable->getColumn('weight')->getComment());
        $this->assertStringContainsString('@JsonFormat(date = "Y-m-d")', $nodesTable->getColumn('created_at')->getComment());
        $this->assertStringContainsString('@JsonCollection("entries")', $nodesTable->getColumn('another_parent')->getComment());

        $fluid->table('node_entries')
            ->column('id')->integer()->primaryKey()->autoIncrement()
            ->column('node_id')->references('nodes')->jsonSerialize()->collection("entries")
            ->column('entry')->string()->null();

        $this->assertStringContainsString('@JsonCollection(key = "entries")', $schema->getTable('node_entries')->getColumn('node_id')->getComment());

        $anotherColumn = $fluid->table('nodes')->column('another_column')->integer();
        $this->assertSame($anotherColumn, $anotherColumn->jsonSerialize()->endJsonSerialize());
        $this->assertSame($fluid->table('nodes'), $anotherColumn->jsonSerialize()->then());
    }
}
