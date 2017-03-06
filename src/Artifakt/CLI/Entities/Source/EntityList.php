<?php

namespace Artifakt\CLI\Entities\Source;

/**
 * Class EntityList
 * @package Artifakt\CLI\Entities\Source
 */
class EntityList
{
    const PROJECT = 'project';
    const USER = 'user';
    const PREFERENCES = 'preferences';
    // More entities coming soon...

    /**
     * @return array
     */
    public static function getEntities() : array
    {
        $reflection = new \ReflectionClass(get_called_class());

        return $reflection->getConstants();
    }
}
