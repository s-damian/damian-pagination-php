<?php

declare(strict_types=1);

namespace DamianPaginationPhp\Http\Request;

use DamianPaginationPhp\Support\String\Str;
use DamianPaginationPhp\Http\Request\Bags\ParameterBag;
use DamianPaginationPhp\Contracts\Http\Request\RequestInterface;

/**
 * @author  Stephen Damian <contact@damian-freelance.fr>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    https://github.com/s-damian/damian-pagination-php
 */
class Request implements RequestInterface
{
    /**
     * @var ParameterBag - $_GET
     */
    private ParameterBag $paramGet;

    /**
     * @var ParameterBag - $_SERVER
     */
    private ParameterBag $paramServer;

    public function __construct()
    {
        // Handle symfony live component
        if (str_starts_with($_SERVER['REQUEST_URI'], '/_component')) {
            $url = parse_url($_SERVER['HTTP_REFERER']);
            if (isset($url['query'])) {
                foreach (explode("&", $url['query']) as $item) {
                    $a = explode('=', $item);
                    $_GET[$a[0]] = $a[1];
                }
            }
            $_SERVER['REQUEST_URI'] = $url['path'];
        }

        $this->paramGet = new ParameterBag($_GET);
        $this->paramServer = new ParameterBag($_SERVER);
    }

    public function getGet(): ParameterBag
    {
        return $this->paramGet;
    }

    public function getServer(): ParameterBag
    {
        return $this->paramServer;
    }

    public function isAjax(): bool
    {
        return $this->paramServer->has('HTTP_X_REQUESTED_WITH') && $this->paramServer->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * @return string - L'URL courante (sans les éventuels query params).
     */
    public function getUrlCurrent(): string
    {
        $server = new Server();

        $requestUri = $server->getRequestUri();

        if (Str::contains($requestUri, '?')) {
            $ex = explode('?', $requestUri);
            $uri = $ex[0];
        } elseif (Str::contains($requestUri, '&')) {
            $ex = explode('&', $requestUri);
            $uri = $ex[0];
        } else {
            $uri = $requestUri;
        }

        return $server->getRequestScheme().'://'.$server->getServerName().$uri;
    }

    /**
     * @param array<string, mixed> $query
     */
    public function getFullUrlWithQuery(array $query): string
    {
        return self::getUrlCurrent() . '?' . $this->buildQuery(array_merge(self::getGet()->all(), $query));
    }

    /**
     * @param array<string, mixed> $array
     */
    public function buildQuery(array $array): string
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }
}
