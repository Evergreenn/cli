<?php

namespace App;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 * @package App
 */
class Application extends BaseApplication
{
    const NAME = 'artifakt-cli';
    const VERSION = '0.0.1';
    const VERSION_ID = 001;
    const MAJOR_VERSION = 0;
    const MINOR_VERSION = 0;
    const RELEASE_VERSION = 1;
    const URI = 'https://console.artifakt.io/api/';

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);
    }
}
