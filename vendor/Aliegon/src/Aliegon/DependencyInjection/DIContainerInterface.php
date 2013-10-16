<?php
namespace Aliegon\DependencyInjection;

interface DIContainerInterface{

	function set($id, $value);

    function get($id);

    function has($id);
    
    function share(\Closure $callable, $run = false);

    function protect(\Closure $callable);
	
    function raw($id);
}