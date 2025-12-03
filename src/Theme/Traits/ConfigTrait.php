<?php

namespace Yivic\YivicLite\Theme\Traits;

trait ConfigTrait {

    /**
     * @param array $config
     */
    public function bindConfig( array $config ): void {
        foreach ( ( array )$config as $attrName => $attrValue ) {
            if ( property_exists( $this, $attrName ) ) {
                $this->$attrName = $attrValue;
            }
        }
    }

}