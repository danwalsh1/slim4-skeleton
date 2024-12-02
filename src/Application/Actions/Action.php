<?php

namespace App\Application\Actions;

use App\Application\Exceptions\ActionNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

abstract class Action
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var ServerRequestInterface
     */
    protected ServerRequestInterface $request;

    /**
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * @var array<string, mixed>
     */
    protected array $args;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array<string, mixed> $args
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (ActionNotFoundException $e) {
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }

    /**
     * @return ResponseInterface
     */
    abstract protected function action(): ResponseInterface;

    /**
     * @return array<mixed>|object|null
     */
    protected function getFormData(): array|null|object
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return array<mixed>
     */
    protected function getFormDataArray(): array
    {
        $data = $this->request->getParsedBody();

        return is_array($data) ? $data : [];
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function resolveArg(string $name): mixed
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument '{$name}'.");
        }

        return $this->args[$name];
    }

    /**
     * @param array<mixed>|object|null $data
     * @param int $statusCode
     * @return ResponseInterface
     */
    protected function respondWithData($data = null, int $statusCode = 200): ResponseInterface
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    /**
     * @param ActionPayload $payload
     * @return ResponseInterface
     */
    protected function respond(ActionPayload $payload): ResponseInterface
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write((string)$json);

        return $this->response->withHeader('Content-Type', 'application/json')->withStatus($payload->getStatusCode());
    }
}
