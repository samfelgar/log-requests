<?php

namespace Samfelgar\LogRequests\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function handle(Request $request, Closure $next)
    {
        $context = $this->context($request);

        Log::channel($this->channel())
            ->debug($this->message(), $context);

        return $next($request);
    }

    private function context(Request $request): array
    {
        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->getMethod(),
            'agent' => $request->header('User-Agent'),
            'headers' => $request->headers->all(),
            'ip' => $request->getClientIp(),
            'body' => $request->getContent(),
        ];

        $authenticatedUser = $request->user();

        if ($authenticatedUser !== null) {
            $context['user'] = $authenticatedUser->getKey();
        }

        return array_filter($context, fn($value) => $value !== null);
    }

    private function channel(): string
    {
        if (!$this->config->has('log-requests.log-channel')) {
            return $this->config->get('logging.default', 'stack');
        }

        return $this->config->get('log-requests.log-channel');
    }

    private function message(): string
    {
        return $this->config->get('log-requests.message', '');
    }
}