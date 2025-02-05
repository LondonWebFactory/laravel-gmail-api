<?php
namespace Skn036\Gmail\Helper;

trait GmailHelpers
{
    /**
     * resolve Guzzle response if not a request
     *
     * @param mixed $responseOrRequest
     * @param \Google_Client $client
     * @param string|null $expectedClass
     *
     * @return mixed;
     */
    protected function executeRequest(
        mixed $responseOrRequest,
        \Google_Client $client,
        ?string $expectedClass = null
    ) {
        if (get_class($responseOrRequest) === 'GuzzleHttp\Psr7\Request') {
            $responseOrRequest = $client->execute($responseOrRequest, $expectedClass);
        }

        return $responseOrRequest;
    }
}
