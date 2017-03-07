<?php
declare(strict_types=1);

namespace Artifakt\CLI\Http\Factory;

use Artifakt\CLI\Http\Builder\RequestBuilder;
use Artifakt\CLI\Url\Generator\UrlGenerator;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class RequestFactory
 * @package Artifakt\CLI\Http\Factory
 */
class RequestFactory
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * RequestFactory constructor.
     *
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param array  $headers
     *
     * @return RequestInterface
     */
    public function create(string $method, string $uri, string $body, array $headers = []) : RequestInterface
    {
        return new Request($method, $uri, $headers, $body);
    }

    /**
     * @return RequestBuilder
     */
    public function createBuilder() : RequestBuilder
    {
        return new RequestBuilder($this, $this->urlGenerator);
    }
}
