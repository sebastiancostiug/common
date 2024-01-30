<?php
/**
 *
 * @package     Common
 *
 * @subpackage  Singleton
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2023 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    common
 * @see
 *
 * @since       2023-10-29
 *
 */

namespace common;

/**
 * Implements the Singleton design pattern, allowing only one instance of a class to be created.
 */
trait Singleton
{
    /**
     * Singleton pattern implementation.
     *
     * @var object|null $instance The instance of the class.
     */
    protected static $instance = null;

    /**
     * Singleton constructor.
     *
     * @return mixed
     */
    abstract protected function __construct();

    /**
     * Get the instance of the class.
     *
     * @return object The instance of the class.
     */
    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Prevents the Singleton class from being cloned.
     *
     * @return void
     */
    private function __clone()
    {
        trigger_error('Class singleton ' . get_class($this) . ' cant be cloned.');
    }

    /**
     * Clears the instance of the Singleton class.
     *
     * @return void
     */
    protected function clearInstance(): void
    {
        self::$instance = null;
    }

    /**
     * The __wakeup() method is declared final to prevent any child classes from overriding it.
     * It triggers an error message when an attempt is made to serialize the singleton object.
     *
     * @return void
     */
    final public function __wakeup(): void
    {
        trigger_error('Classe singleton ' . get_class($this) . ' cant be serialized.');
    }
}
