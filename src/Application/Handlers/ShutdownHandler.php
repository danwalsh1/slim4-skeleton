<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\ResponseEmitter\ResponseEmitter;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpInternalServerErrorException;

use function error_get_last;

use const E_USER_ERROR;

class ShutdownHandler
{
    /**
     * @var ServerRequestInterface
     */
    private ServerRequestInterface $request;

    /**
     * @var HttpErrorHandler
     */
    private HttpErrorHandler $errorHandler;

    /**
     * @var bool
     */
    private bool $displayErrorDetails;

    public function __construct(
        ServerRequestInterface $request,
        HttpErrorHandler $errorHandler,
        bool $displayErrorDetails
    ) {
        $this->request = $request;
        $this->errorHandler = $errorHandler;
        $this->displayErrorDetails = $displayErrorDetails;
    }

    /**
     * @return void
     */
    public function __invoke(): void
    {
        $error = error_get_last();
        if (!$error) {
            return;
        }

        $message = $this->getErrorMessage($error);
        $exception = new HttpInternalServerErrorException($this->request, $message);
        $response = $this->errorHandler->__invoke($this->request, $exception, $this->displayErrorDetails, false, false);

        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }

    /**
     * @param array<string, string|int> $error
     * @return string
     */
    private function getErrorMessage(array $error): string
    {
        if (!$this->displayErrorDetails) {
            return 'An error occurred whilst processing your request. Please try again later.';
        }

        $errorFile = $error['file'];
        $errorLine = $error['line'];
        $errorMessage = $error['message'];
        $errorType = $error['type'];

        if ($errorType === E_USER_WARNING) {
            return "WARNING: {$errorMessage}";
        }

        if ($errorType === E_USER_NOTICE) {
            return "NOTICE: {$errorMessage}";
        }

        return "FATAL ERROR: {$errorMessage}. on line {$errorLine} in file {$errorFile}.";
    }
}
