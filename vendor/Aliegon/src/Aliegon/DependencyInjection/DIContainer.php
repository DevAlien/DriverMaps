<?php
namespace Aliegon\DependencyInjection;

class DIContainer implements DIContainerInterface, \ArrayAccess
{
    
    protected $values;

    /**
     * Instantiate the container.
     *
     * Objects and parameters can be passed as argument to the constructor.
     *
     * @param array $values The parameters or objects.
     */
    public function __construct (array $values = array()) {
        $this->values = $values;
        $this->set('service_container', $this);
    }

    /**
     * Sets a parameter or an object.
     *
     * Objects must be defined as Closures.
     *
     * Allowing any PHP callable leads to difficult to debug problems
     * as function names (strings) are callable (creating a function with
     * the same a name as an existing parameter would break your container).
     *
     * @param string $id    The unique identifier for the parameter or object
     * @param mixed  $value The value of the parameter or a closure to defined an object
     */
    public function offsetSet($id, $value)
    {
        $this->values[$id] = $value;
    }

    /**
     * Gets a parameter or an object.
     *
     * @param string $id The unique identifier for the parameter or object
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws InvalidArgumentException if the identifier is not defined
     */
    public function offsetGet($id)
    {
        if (!array_key_exists($id, $this->values)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        $isFactory = is_object($this->values[$id]) && method_exists($this->values[$id], '__invoke');

        return $isFactory ? $this->values[$id]($this) : $this->values[$id];
    }

    /**
     * Checks if a parameter or an object is set.
     *
     * @param string $id The unique identifier for the parameter or object
     *
     * @return Boolean
     */
    public function offsetExists($id)
    {
        return array_key_exists($id, $this->values);
    }

    /**
     * Unsets a parameter or an object.
     *
     * @param string $id The unique identifier for the parameter or object
     */
    public function offsetUnset($id)
    {
        unset($this->values[$id]);
    }
    
	public function set($id, $value) {
        $this->values[$id] = $value;
    }

	public function __set($id, $value) {
        $this->values[$id] = $value;
    }

    public function has($id)
    {
        if (array_key_exists($id, $this->values))
            return true;

        return false;
    }

    public function get($id) {
        if (!array_key_exists($id, $this->values)) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }
        return $this->values[$id] instanceof \Closure ? $this->values[$id]($this) : $this->values[$id];
    }


    function share(\Closure $callable, $run = false) {
        $shared =  function ($c) use ($callable) {
            static $object;

            if (is_null($object))
                $object = $callable($c);
            return $object;
        };
		
		if($run === true)
			$shared($this);
		return $shared;
    }

    public function protect(\Closure $callable) {
        return function ($c) use ($callable) {
            return $callable;
        };
    }
	
    public function raw($id) {
        if (!array_key_exists($id, $this->values)) {
            throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        return $this->values[$id];
    }
}