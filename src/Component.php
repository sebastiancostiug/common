<?php
/**
 *
 * @package     slim-base
 *
 * @subpackage  Component
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2024 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    slim-base
 * @see
 *
 * @since       2024-02-03
 *
 */

namespace common;

/**
 * Component class
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
        throw_when(!property_exists($this, $name) && !method_exists($this, 'get' . $name) && method_exists($this, 'set' . $name), 'Getting write-only property: ' . get_class($this) . '::' . $name);
        throw_when(!property_exists($this, $name) && !method_exists($this, 'get' . $name), 'Getting unknown property: ' . get_class($this) . '::' . $name);

        $getter = 'get' . $name;

        return $this->$getter();
    }

    /**
     * Sets the value of a component property.
     *
     * This method will check in the following order and act accordingly:
     *
     *  - a property defined by a setter: set the property value
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$component->property = $value;`.
     *
     * @param string $name  The property name or the event name
     * @param mixed  $value The property value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        throw_when(!property_exists($this, $name) && !method_exists($this, 'set' . $name) && method_exists($this, 'get' . $name), 'Setting read-only property: ' . get_class($this) . '::' . $name);
        throw_when(!property_exists($this, $name) && !method_exists($this, 'set' . $name), 'Setting unknown property: ' . get_class($this) . '::' . $name);

        $setter = 'set' . $name;
        $this->$setter($value);
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     *
     * This method will check in the following order and act accordingly:
     *
     *  - a property defined by a setter: return whether the property is set
     *  - return `false` for non existing properties
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
     * This method will check in the following order and act accordingly:
     *
     *  - a property defined by a setter: set the property value to be null
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `unset($component->property)`.
     * @param string $name The property name
     *
     * @return void
     */
    public function __unset($name)
    {
        throw_when(!property_exists($this, $name) && !method_exists($this, 'set' . $name) && method_exists($this, 'get' . $name), 'Unsetting read-only property: ' . get_class($this) . '::' . $name);
        throw_when(!property_exists($this, $name) && !method_exists($this, 'get' . $name), 'Unsetting unknown property: ' . get_class($this) . '::' . $name);

        $setter = 'set' . $name;
        $this->$setter(null);
    }

        /**
     * Calls the named method which is not a class method.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when an unknown method is being invoked.
     * @param string $name   The method name
     * @param array  $params Method parameters
     *
     * @return mixed the method return value
     */
    public function __call($name, $params)
    {
        throw_when(!$this->hasMethod($name), 'Calling unknown method: ' . get_class($this) . "::$name()");

        return call_user_func_array([$this, $name], $params);
    }

    /**
     * Returns a value indicating whether a property is defined.
     *
     * A property is defined if:
     *
     * - the class has a getter or setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string  $name      the property name
     * @param boolean $checkVars whether to treat member variables as properties
     * @return boolean whether the property is defined
     * @see canGetProperty()
     * @see canSetProperty()
     */
    public function hasProperty($name, $checkVars = true)
    {
        return $this->canGetProperty($name, $checkVars) || $this->canSetProperty($name, false);
    }

    /**
     * Returns a value indicating whether a property can be read.
     *
     * A property is readable if:
     *
     * - the class has a getter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string  $name      the property name
     * @param boolean $checkVars whether to treat member variables as properties
     * @return boolean whether the property can be read
     * @see canSetProperty()
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a property can be set.
     *
     * A property is writable if:
     *
     * - the class has a setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * @param string  $name      the property name
     * @param boolean $checkVars whether to treat member variables as properties
     * @return boolean whether the property can be written
     * @see canGetProperty()
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a method is defined.
     *
     * The default implementation is a call to php function `method_exists()`.
     * You may override this method when you implemented the php magic method `__call()`.
     * @param string $name the method name
     * @return boolean whether the method is defined
     */
    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}
