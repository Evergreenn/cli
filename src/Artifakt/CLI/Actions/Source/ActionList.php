<?php
declare(strict_types=1);

namespace Artifakt\CLI\Actions\Source;

/**
 * Class ActionList
 * @package Artifakt\CLI\Actions\Source
 */
class ActionList
{
    const CREATE = 'create';
    const UPDATE = 'update';
    const GET = 'get';
    const LIST = 'list';
    const DELETE = 'delete';

    /**
     * @return array
     */
    public static function getActions() : array
    {
        $reflection = new \ReflectionClass(get_called_class());

        return $reflection->getConstants();
    }
}
