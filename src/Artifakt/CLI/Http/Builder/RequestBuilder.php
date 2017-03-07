<?php

namespace Artifakt\CLI\Http\Builder;

use Artifakt\CLI\Http\Factory\RequestFactory;
use Artifakt\CLI\Url\Generator\UrlGenerator;
use Psr\Http\Message\RequestInterface;

/**
 * Class RequestBuilder
 * @package Artifakt\CLI\Http\Builder
 */
class RequestBuilder
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var string|null
     */
    private $param;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $body;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * RequestBuilder constructor.
     *
     * @param RequestFactory $requestFactory
     * @param UrlGenerator   $urlGenerator
     */
    public function __construct(RequestFactory $requestFactory, UrlGenerator $urlGenerator)
    {
        $this->requestFactory = $requestFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string $method
     *
     * @return RequestBuilder
     */
    public function setMethod(string $method) : RequestBuilder
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $action
     *
     * @return RequestBuilder
     */
    public function setAction(string $action) : RequestBuilder
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @param string $entity
     *
     * @return RequestBuilder
     */
    public function setEntity(string $entity) : RequestBuilder
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param string|null $param
     *
     * @return RequestBuilder
     */
    public function setParam(string $param = null) : RequestBuilder
    {
        $this->param = $param;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return RequestBuilder
     */
    public function addHeader(string $key, string $value) : RequestBuilder
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return RequestBuilder
     */
    public function removeHeader(string $key) : RequestBuilder
    {
        if (isset($this->headers[$key])) {
            unset($this->headers[$key]);
        }

        return $this;
    }

    /**
     * @param string $body
     *
     * @return RequestBuilder
     */
    public function setBody(string $body = '') : RequestBuilder
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest() : RequestInterface
    {
        return $this->requestFactory->create(
            $this->method,
            $this->urlGenerator->generate($this->action, $this->entity, $this->param),
            $this->body,
            $this->headers
        );
    }
}
