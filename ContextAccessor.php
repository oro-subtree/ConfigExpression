<?php

namespace Oro\Component\ConfigExpression;

use Symfony\Component\PropertyAccess\PropertyPathInterface;

use Oro\Component\PropertyAccess\PropertyAccessor;

class ContextAccessor implements ContextAccessorInterface
{
    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * Set value to context.
     *
     * @param object|array $context
     * @param string|PropertyPathInterface $property
     * @param mixed $value
     */
    public function setValue($context, $property, $value)
    {
        $this->getPropertyAccessor()->setValue(
            $context,
            $property,
            $this->getValue($context, $value)
        );
    }

    /**
     * Get value from context
     *
     * @param mixed $context
     * @param mixed $value
     * @return mixed
     */
    public function getValue($context, $value)
    {
        if ($value instanceof PropertyPathInterface) {
            try {
                return $this->getPropertyAccessor()->getValue($context, $value);
            } catch (\Exception $e) {
                return null;
            }
        } else {
            return $value;
        }
    }

    /**
     * Checks whether context has value
     *
     * @param mixed $context
     * @param mixed $value
     * @return bool
     */
    public function hasValue($context, $value)
    {
        if ($value instanceof PropertyPathInterface) {
            try {
                $key = $value->getElement($value->getLength() - 1);
                $parentValue = $value->getParent()
                    ? $this->getPropertyAccessor()->getValue($context, $value->getParent())
                    : null;
                if (is_array($parentValue)) {
                    return array_key_exists($key, $parentValue);
                } elseif ($parentValue instanceof \ArrayAccess) {
                    return isset($parentValue[$key]);
                } else {
                    return $this->getPropertyAccessor()->getValue($context, $value) !== null;
                }
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get PropertyAccessor
     *
     * @return PropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        if ($this->propertyAccessor === null) {
            $this->propertyAccessor = new PropertyAccessor();
        }

        return $this->propertyAccessor;
    }
}
