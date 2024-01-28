<?php


namespace TheCodingMachine\FluidSchema;


use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;

class TdbmFluidColumn
{
    /**
     * @var TdbmFluidTable
     */
    private $tdbmFluidTable;
    /**
     * @var FluidColumn
     */
    private $fluidColumn;
    /**
     * @var NamingStrategyInterface
     */
    private $namingStrategy;

    public function __construct(TdbmFluidTable $tdbmFluidTable, FluidColumn $fluidColumn, NamingStrategyInterface $namingStrategy)
    {
        $this->tdbmFluidTable = $tdbmFluidTable;
        $this->fluidColumn = $fluidColumn;
        $this->namingStrategy = $namingStrategy;
    }

    private function getOptions(FluidColumnOptions $options): TdbmFluidColumnOptions
    {
        return new TdbmFluidColumnOptions($this->tdbmFluidTable, $this->fluidColumn, $options);
    }

    public function integer(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->integer();
        return $this->getOptions($options);
    }

    public function smallInt(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->smallInt();
        return $this->getOptions($options);
    }

    public function bigInt(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->bigInt();
        return $this->getOptions($options);
    }

    public function decimal(int $precision = 10, int $scale = 0): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->decimal($precision, $scale);
        return $this->getOptions($options);
    }

    public function float(int $precision = 10, int $scale = 0): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->float($precision, $scale);
        return $this->getOptions($options);
    }

    public function string(?int $length = null, bool $fixed = false): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->string($length, $fixed);
        return $this->getOptions($options);
    }

    public function text(?int $length = null): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->text($length);
        return $this->getOptions($options);
    }

    public function guid(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->guid();
        return $this->getOptions($options);
    }

    public function binary(?int $length = null, bool $fixed = false): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->binary($length, $fixed);
        return $this->getOptions($options);
    }

    public function blob(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->blob();
        return $this->getOptions($options);
    }

    public function boolean(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->boolean();
        return $this->getOptions($options);
    }

    public function date(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->date();
        return $this->getOptions($options);
    }

    public function dateImmutable(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->dateImmutable();
        return $this->getOptions($options);
    }

    public function datetime(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->datetime();
        return $this->getOptions($options);
    }

    public function datetimeImmutable(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->datetimeImmutable();
        return $this->getOptions($options);
    }

    public function datetimeTz(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->datetimeTz();
        return $this->getOptions($options);
    }

    public function datetimeTzImmutable(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->datetimeTzImmutable();
        return $this->getOptions($options);
    }

    public function time(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->time();
        return $this->getOptions($options);
    }

    public function timeImmutable(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->timeImmutable();
        return $this->getOptions($options);
    }

    public function dateInterval(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->dateInterval();
        return $this->getOptions($options);
    }

    public function array(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->array();
        return $this->getOptions($options);
    }

    public function simpleArray(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->simpleArray();
        return $this->getOptions($options);
    }

    public function json(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->json();
        return $this->getOptions($options);
    }

    /**
     * @deprecated Use json() instead
     */
    public function jsonArray(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->jsonArray();
        return $this->getOptions($options);
    }

    public function object(): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->object();
        return $this->getOptions($options);
    }

    public function references(string $tableName, ?string $constraintName = null, string $onUpdate = 'RESTRICT', string $onDelete = 'RESTRICT'): TdbmFluidColumnOptions
    {
        $options = $this->fluidColumn->references($tableName, $constraintName, $onUpdate, $onDelete);
        return $this->getOptions($options);
    }

    public function getDbalColumn(): Column
    {
        return $this->fluidColumn->getDbalColumn();
    }
}
