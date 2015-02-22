<?php

namespace Oro\Component\ConfigExpression\Tests\Unit\PropertyAccess\Fixtures;

class TestClassMagicGet
{
    private $magicProperty;

    public function __construct($value)
    {
        $this->magicProperty = $value;
    }

    public function __set($property, $value)
    {
        if ('magicProperty' === $property) {
            $this->magicProperty = $value;
        }
    }

    public function __get($property)
    {
        if ('magicProperty' === $property) {
            return $this->magicProperty;
        }

        if ('constantMagicProperty' === $property) {
            return 'constant value';
        }
    }
}
