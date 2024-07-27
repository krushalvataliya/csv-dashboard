<?php
/**
 * 
 */
class Model_Core_Table_Row
{

	protected $data = [];

	public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    public function __unset($key)
    {
    	if (array_key_exists($key, $this->data)) {
        unset($this->data[$key]);
    	}
    	return $this;
    }
  
    public function getData()
    {
        return $this->data;
    }

    public function setData($key, $value)
    {
        $this->data = $data;

        return $this;
    }

    public function addData($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    	
    }
    public function removeData($key)
    {
    	if(array_key_exists($key, $this->data))
    	{
    		unset($this->data[$key]);
    	}
    	return $this;
    }
}
?>