<?php

namespace JustTheBasicz;

use Zend\Diactoros\ServerRequestFactory as Factory;
use Zend\Diactoros\ServerRequest;


class Request extends ServerRequest
{
    private $_action;

    public static function createFromGlobals()
    {
        $server = Factory::normalizeServer($_SERVER);
        $files = Factory::normalizeFiles($_FILES);
        $headers = Factory::marshalHeaders($server);
        return new static(
            $server,
            $files,
            Factory::marshalUriFromServer($server, $headers),
            Factory::get('REQUEST_METHOD', $server, 'GET'),
            'php://input',
            $headers,
            $_COOKIE,
            $_GET,
            $_POST,
            static::_marshalProtocolVersion($server)
        );
    }

    private static function _marshalProtocolVersion($server)
    {
        if (!isset($server['SERVER_PROTOCOL'])) {
            return '1.1';
        }
        if (!preg_match(
            '#^(HTTP/)?(?P<version>[1-9]\d*(?:\.\d)?)$#',
            $server['SERVER_PROTOCOL'],
            $matches
        )) {
            throw new UnexpectedValueException(
                sprintf(
                    'Unrecognized protocol version (%s)',
                    $server['SERVER_PROTOCOL']
                )
            );
        }
        return $matches['version'];
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }

    public function getContentType()
    {
        $contentType = null;
        $_contentType = $this->getHeader('Content-Type');
        if (!empty($_contentType)) {
            $_contentType = explode(';', $_contentType[0]);
            $contentType = $_contentType[0];
        }
        return $contentType;
    }

    public function getParsedBody()
    {
        $parsedBody = parent::getParsedBody();
        if (empty($parsedBody)) {
            if ($this->getContentType() == 'application/json') {
                $input = file_get_contents("php://input");
                $parsedBody = json_decode($input, true);
            }
        }
        return $parsedBody;
    }
}
