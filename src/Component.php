<?php
/**
 *
 * @package     Common
 *
 * @subpackage  Component
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2024 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    common classes
 *
 * @since       2024-02-03
 *
 */

namespace common;

/**
 * Component class
 *
 * Implements custom magic methods __get, __set, __isset, __unset, __call to allow dynamically set class properties.
 */
class Component
{
    /**
     * Magic method to get a property value by calling its corresponding getter method.
     *
     * @param string $name The name of the property to get.
     *
     * @return mixed The value of the property. Null if the property does not exist or is write-only.
     */
    public function __get($name): mixed
    {
        $getter = 'get' . ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else {
            try {
                return $this->$name;
            } catch (\Exception $e) {
                throw new \Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
            }
        }
    }

    /**
     * Sets the value of a component property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$component->property = $value;`.
     *
     * @param string $name  The property name or the event name
     * @param mixed  $value The property value
     *
     * @return void
     */
    public function __set($name, mixed $value)
    {
        $setter = 'set' . ucfirst($name);

        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            try {
                $this->$name = $value;
            } catch (\Exception $e) {
                throw new \Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
            }
        }
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `isset($component->property)`.
     *
     * @param string $name The property name or the event name
     *
     * @return boolean whether the named property is set
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }

        return false;
    }

    /**
     * Sets a component property to be null.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `unset($component->property)`.
     *
     * @param string $name The property name
     *
     * @return void
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (property_exists($this, $name)) {
            $this->$name = null;
        } elseif (method_exists($this, 'get' . $name)) {
            throw new \Exception('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new \Exception('Unsetting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Calls the named method which is not a class method.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when an unknown method is being invoked.
     *
     * @param string $name   The method name
     * @param array  $params Method parameters
     *
     * @return mixed the method return value
     */
    public function __call($name, array $params): mixed
    {
        if ($this->hasMethod($name)) {
            return call_user_func_array([$this, $name], $params);
        }

        throw new \Exception('Calling unknown method: ' . get_class($this) . "::$name()");
    }

    /**
     * Returns a value indicating whether a property is defined.
     *
     * @param string  $name              The property name
     * @param boolean $includeProperties Whether to check class properties
     *
     * @return boolean whether the property is defined
     */
    public function hasProperty($name, $includeProperties = true)
    {
        return $this->canGetProperty($name, $includeProperties) || $this->canSetProperty($name, false);
    }

    /**
     * Returns a value indicating whether a property can be read.
     *
     * @param string  $name              The property name
     * @param boolean $includeProperties Whether to check class properties
     *
     * @return boolean whether the property can be read
     */
    public function canGetProperty($name, $includeProperties = true)
    {
        return method_exists($this, 'get' . $name) || $includeProperties && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a property can be set.
     *
     * @param string  $name              The property name
     * @param boolean $includeProperties Whether to check class properties
     *
     * @return boolean whether the property can be written
     */
    public function canSetProperty($name, $includeProperties = true)
    {
        return method_exists($this, 'set' . $name) || $includeProperties && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a method is defined.
     *
     * The default implementation is a call to php function `method_exists()`.
     * You may override this method when you implemented the php magic method `__call()`.
     *
     * @param string $name The method name
     *
     * @return boolean whether the method is defined
     */
    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}
