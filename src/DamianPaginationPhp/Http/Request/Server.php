<?php

declare(strict_types=1);

namespace DamianPaginationPhp\Http\Request;

/**
 * @author  Stephen Damian <contact@damian-freelance.fr>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link    https://github.com/s-damian/damian-pagination-php
 */
class Server
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function getRequestScheme(): string
    {
        return $this->request->getServer()->get('REQUEST_SCHEME');
    }

    public function getRequestUri(): string
    {
        return $this->request->getServer()->get('REQUEST_URI');
    }

    public function getServerName(): string
    {
        // Support symfony
        if ($this->request->getServer()->has('HTTP_HOST')) {
            return $this->request->getServer()->get('HTTP_HOST');
        }

        return $this->request->getServer()->get('SERVER_NAME');
    }
}
