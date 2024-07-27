<?php 
/**
 * 
 */
class Model_Core_Session
{
	public function getId()
	{
		return session_id();
	}
	
	public function start()
	{
		if (session_status() != PHP_SESSION_ACTIVE)
		{
			session_start();
		}
		return $this;
	}

	public function destroy()
	{
		session_destroy();
		return $this;
	}

	public function set($key, $value)
	{
		$_SESSION[$key] = $value;
		return $this;
	}

	public function get($key=null, $value=null)
	{
		if($key == null)
		{
			return $_SESSION;
		}
		if(array_key_exists($key, $_SESSION))
		{
			return $_SESSION[$key];
		}

		return $value;
	}

	public function unset($key)
	{
		if(array_key_exists($key, $_SESSION))
		{
			unset($_SESSION[$key]);
		}
		return $this;
	}

	public function has($key)
	{
		$this->start();
		if(!array_key_exists($key, $_SESSION))
		{
			return false;
		}
		return true;
	}


}
?>