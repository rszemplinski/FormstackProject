<?php

namespace JustTheBasicz;

class Controller
{

    protected $request;

    protected $response;

    protected $config;

    protected $data = null;

    public function __construct(Request $request, Response $response)
    {
        $this->setRequest($request);
        $this->setResponse($response);
        $this->config = Config::instance();
    }
    /**
     * You can overwrite this function to run code before
     * any action is called.
     *
     * @return void
     */
    public function initController()
    { }

    protected function render($template, $data = [], $status = 200)
    {
        $rendered = View::render($template, $data);
        $this->response->getBody()->write($rendered);
        return $this->getResponse()
            ->withStatus($status)
            ->withHeader('Content-Type', 'text/html; charset=utf-8');
    }

    protected function renderJSON($data = [], $status = 200, $flags = null)
    {
        $rendered = View::renderJSON($data, $flags);
        $this->response->getBody()->rewind();
        $this->response->getBody()->write($rendered);
        return $this->getResponse()
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
    }

    public function routerError($error = null, $subject = null)
    {
        switch ($error) {
            case Router::ERR_NOT_FOUND:
                $status = 404;
                $template = 'router/error404';
                break;
            case Router::ERR_BAD_METHOD:
                $status = 405;
                $template = 'router/error405';
                break;
            case Router::ERR_MISSING_CONTROLLER:
            case Router::ERR_MISSING_ACTION:
            default:
                $status = 500;
                $template = 'router/error';
                break;
        }
        return $this->render($template, compact('error', 'subject'), $status);
    }

    public function __get($name)
    {
        return null;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        $parsedBody = $this->request->getParsedBody();
        if (!empty($parsedBody)) {
            if (is_array($parsedBody)) {
                $this->data = array_map('trim', (array)$parsedBody);
            } else {
                $this->data = $parsedBody;
            }
        }
        return $this;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }
}
