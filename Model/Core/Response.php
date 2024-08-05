<?php 
class Model_Core_Response
{
    protected $_headers = array();
    protected $_body = '';

    function __construct()
    {
    }

    public function jsonResponse($data)
    {
        $this->clearHeaders();
        $this->setHeader("Content-type", "application/json");
        $this->setBody(json_encode($data));
        $this->sendResponse();
    }

    public function clearHeaders()
    {
        $this->_headers = array();
        return $this;
    }

    public function setHeader($name, $value)
    {
        $this->_headers[$name] = $value;
        return $this;
    }

    public function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    public function sendResponse()
    {
        foreach ($this->_headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->_body;
    }
}
