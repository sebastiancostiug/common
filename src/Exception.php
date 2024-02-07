<?php
/**
 * @package     Common
 *
 * @subpackage  Exception
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2024 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    exceptions
 *
 * @since       2024-02-05
 */

namespace common;

/**
 * Exception class
 */
class Exception extends \Exception
{
    /**
     * @var array $debugInfo The errors that occurred during the connection.
     */
    private array $_errors;

    /**
     * DatabaseException constructor.
     *
     * @param string          $message  The exception message.
     * @param array           $errors   The debug info for the error that occurred during the connection.
     * @param integer         $code     The exception code.
     * @param \Throwable|null $previous The previous exception used for the exception chaining.
     *
     * @return void
     */
    public function __construct(string $message, array $errors = [], int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->_errors = $errors;
    }

    /**
     * Get the errors that occurred during the connection.
     *
     * @return array The errors that occurred during the connection.
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }

    /**
     * Get the exception name.
     *
     * @return string The exception name.
     */
    public function getName(): string
    {
        return 'Base application exception';
    }
}
