<?php

namespace Artifakt\CLI\Url\Generator;

use Artifakt\CLI\Actions\Source\ActionList;
use Artifakt\CLI\Utils\Pluralizer;

/**
 * Class UrlGenerator
 * @package Artifakt\CLI\Url\Generator
 */
class UrlGenerator
{
    /**
     * @var string
     */
    private $baseUri;

    /**
     * UrlGenerator constructor.
     *
     * @param string $baseUri
     */
    public function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @param string      $action
     * @param string      $entity
     * @param string|null $param
     *
     * @return string
     */
    public function generate(string $action, string $entity, string $param = null) : string
    {
        $url = $this->baseUri.Pluralizer::pluzalize($entity);
        if (!in_array($action, [ActionList::CREATE, ActionList::LIST]) && null !== $param) {
            $url .= '/'.$param;
        }

        return $url;
    }
}
