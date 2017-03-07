<?php
declare(strict_types=1);

namespace Artifakt\CLI\Utils;

/**
 * Class Pluralizer
 * @package Artifakt\CLI\Utils
 */
class Pluralizer
{
    /**
     * @param string $string
     *
     * @return string
     */
    public static function pluralize(string $string) : string
    {
        if (false !== \strpos($string, 's', \strlen($string) -1)) {
            return $string;
        }

        $plural = 's';
        if (false !== \strpos($string, 'y', \strlen($string) -1)) {
            $plural = 'ies';
            $string = \substr($string, 0, -1);
        }

        return $string.$plural;
    }
}
