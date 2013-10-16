<?php

namespace Aliegon\DependencyInjection;

class Container implements ContainerInterface{

    /**
     * @var DIContainerInterface
     *
     * @api
     */
    protected $container;

    /**
     * Sets the Container
     *
     * @param DIContainerInterface $container A DIContainerInterface instance
     */
    public function setContainer(DIContainerInterface $container = null){
        $this->container = $container;
    }
}
