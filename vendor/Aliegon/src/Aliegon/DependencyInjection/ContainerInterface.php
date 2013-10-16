<?php

namespace Aliegon\DependencyInjection;

interface ContainerInterface {
	/**
     * Sets the Container associated with this Controller.
     *
     * @param DIContainerInterface $container A DIContainerInterface instance
     */
	function setContainer(DIContainerInterface $container = null);
}