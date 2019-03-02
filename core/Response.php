<?php

namespace JustTheBasicz;

use Zend\Diactoros\Response as ResponseInterface;

class Response extends ResponseInterface
{
    public static function send(ResponseInterface $response)
    {
        header(
            sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            )
        );
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        $body = $response->getBody();
        if ($body->isSeekable()) {
            $body->rewind();
        }
        $contentLength = $response->getHeaderLine('Content-Length');
        if (!$contentLength) {
            $contentLength = $body->getSize();
        }
        if (isset($contentLength)) {
            $amountToRead = $contentLength;
            while ($amountToRead > 0 && !$body->eof()) {
                $chunk = $body->read(min(4096, $amountToRead));
                echo $chunk;
                $amountToRead -= strlen($chunk);
                if (connection_status() != CONNECTION_NORMAL) {
                    break;
                }
            }
        } else {
            while (!$body->eof()) {
                echo $body->read(4096);
                if (connection_status() != CONNECTION_NORMAL) {
                    break;
                }
            }
        }
    }
}
