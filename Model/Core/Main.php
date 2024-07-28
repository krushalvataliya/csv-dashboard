<?php 
require_once 'Model/Core/Request.php';
require_once 'Model/Core/Adapter.php';
require_once 'Model/Core/Session.php';


class Model_Core_Main
{
    public $request = null;
    public $adapter = null;
    public $session = null;
    protected $data = [];

    public function __construct()
    {
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
        return $this;
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'set') === 0) {
            $key = lcfirst(substr($name, 3));
            $this->data[$key] = $arguments[0];
            return $this;
        } elseif (strpos($name, 'get') === 0) {
            $key = lcfirst(substr($name, 3));
            return isset($this->data[$key]) ? $this->data[$key] : null;
        }

        throw new BadMethodCallException("Method $name does not exist");
    }

    public function getRequest()
    {
        if($this->request)
        {
            return $this->request;
        }
        $request = new Model_Core_Request();
        $this->setRequest($request);
        return $request;
    }

    protected function setRequest(Model_Core_Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getAdapter()
    {
        if($this->adapter)
        {
            return $this->adapter;
        }
        $adapter = new Model_Core_Adapter();
        $this->setAdapter($adapter);
        return $adapter;
    }
    
    protected function setAdapter(Model_Core_Adapter  $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    public function getSession()
    {
         if($this->session)
        {
            return $this->session;
        }
        $session = new Model_Core_Session();
        $this->setSession($session);
        return $this->session;
    }

    protected function setSession($session)
    {
        $this->session = $session;

        return $this;
    }
}

?>