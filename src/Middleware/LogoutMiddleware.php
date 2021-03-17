<?php

namespace Maicol07\SSO\Middleware;

use Illuminate\Support\Facades\Cookie;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LogoutMiddleware implements MiddlewareInterface
{
    final public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $actor = $request->getAttribute('actor');
        $logout_url = resolve('flarum.forum.routes')->getPath('logout');
        $token = $request->getAttribute('session')->token();
        $path = $request->getUri()->getPath();

        if (empty(Cookie::get('flarum_remember')) and !$actor->isGuest() and $path !== $logout_url) {
            return new RedirectResponse("$logout_url?token=$token&redirect=false&path=$path");
        }

        return $handler->handle($request);
    }
}
