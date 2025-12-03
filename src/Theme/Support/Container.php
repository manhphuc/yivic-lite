<?php

namespace Yivic\YivicLite\Theme\Support;

/**
 * Very small service container for the Yivic Lite theme.
 * Inspired by Laravel's Container but extremely simplified.
 */
class Container {
    /**
     * @var array<string, callable>
     */
    protected array $bindings = [];

    /**
     * @var array<string, mixed>
     */
    protected array $instances = [];

    /**
     * Bind a factory into the container.
     *
     * @param string   $abstract
     * @param callable $factory  function ( Container $app ): mixed
     */
    public function bind( string $abstract, callable $factory ): void {
        $this->bindings[$abstract] = $factory;
    }

    /**
     * Bind a singleton into the container.
     *
     * @param string   $abstract
     * @param callable $factory
     */
    public function singleton( string $abstract, callable $factory ): void {
        $this->bindings[$abstract] = $factory;
        $this->instances[$abstract] = null; // marker
    }

    /**
     * Resolve an entry from the container.
     *
     * @param  string $abstract
     * @return mixed
     */
    public function make( string $abstract ) {
        if ( array_key_exists( $abstract, $this->instances )
            && $this->instances[$abstract] !== null
        ) {
            return $this->instances[$abstract];
        }

        if ( ! isset( $this->bindings[$abstract] ) ) {
            throw new \RuntimeException( "Nothing bound in container for [{$abstract}]." );
        }

        $object = ( $this->bindings[$abstract] )( $this );

        if ( array_key_exists( $abstract, $this->instances ) ) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }
}