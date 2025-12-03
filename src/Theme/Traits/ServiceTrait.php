<?php

namespace Yivic\YivicLite\Theme\Traits;

use Yivic\YivicLite\Theme\Support\Container;

trait ServiceTrait {
    /**
     * @var Container|null
     */
    protected ?Container $container = null;

    /**
     * Every service using this trait must implement init().
     * Called to register hooks, filters, or bootstrap service.
     */
    abstract public function init(): void;

    /**
     * Inject container instance into this service.
     *
     * @param  Container $container
     * @return void
     */
    public function setContainer( Container $container ): void {
        if ( $this->container === null ) {
            $this->container = $container;
        }
    }

    /**
     * Retrieve the DI container.
     *
     * @return Container
     */
    public function getContainer(): Container {
        return $this->container;
    }
}