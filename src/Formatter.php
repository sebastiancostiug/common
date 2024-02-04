<?php
/**
 * @package     Common
 *
 * @subpackage  Formatter
 *
 * @author      Sebastian Costiug <sebastian@overbyte.dev>
 * @copyright   2019-2024 Sebastian Costiug
 * @license     https://opensource.org/licenses/BSD-3-Clause
 *
 * @category    common
 *
 * @since       2024-02-02
 *
 */

namespace common;

/**
 * Class Formatter
 *
 * This class is responsible for formatting data.
 */
class Formatter
{
    /**
     * Converts a duration string to a formatted string.
     *
     * @param string $duration The duration string to be formatted.
     *
     * @return string The formatted duration string.
     */
    public function asDuration(string $duration): string
    {
        switch (true) {
            case str_ends_with($duration, 's'):
                $seconds = (int) $duration;
                break;
            case str_ends_with($duration, 'm'):
                $seconds = (int) $duration * 60;
                break;
            case str_ends_with($duration, 'h'):
                $seconds = (int) $duration * 60 * 60;
                break;
            case str_ends_with($duration, 'd'):
                $seconds = (int) $duration * 60 * 60 * 24;
                break;
            case str_ends_with($duration, 'w'):
                $seconds = (int) $duration * 60 * 60 * 24 * 7;
                break;
            case str_ends_with($duration, 'M'):
                $seconds = (int) $duration * 60 * 60 * 24 * 30;
                break;
            case str_ends_with($duration, 'y'):
                $seconds = (int) $duration * 60 * 60 * 24 * 365;
                break;
            default:
                $duration = new \DateInterval('P1Y2M3DT4H5M6S');

                return $duration->format('%y years, %m months, %d days, %h hours, %i minutes, %s seconds');
        }
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");

        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes, %s seconds');
    }

    /**
     * Converts a string representation of time to a formatted time string.
     *
     * @param string $time   The string representation of time.
     * @param string $format The format of the output time string. Default is 'Y-m-d H:i:s'.
     *
     * @return string The formatted time string.
     */
    public function asTime(string $time, string $format = 'Y-m-d H:i:s'): string
    {
        $time = new \DateTime($time);

        return $time->format($format);
    }

    /**
     * Formats a DateTime object as a string representation of a date.
     *
     * @param \DateTime $date   The DateTime object to format.
     * @param string    $format The format string to use for formatting the date.
     *
     * @return string The formatted date string.
     */
    public function asDate(\DateTime $date, $format): string
    {
        switch ($format) {
            case 'short':
                return $date->format('m/d/Y');
                break;
            case 'long':
                return $date->format('F j, Y');
                break;
            case 'numeric':
                return $date->format('Ymd');
                break;
            case 'name':
                return $date->format('l, F j, Y');
                break;
            default:
                return $date->format('F j, Y');
                break;
        }
        return $date->format('F j, Y');
    }
}
