<?php

/**
 * die and dump
 *
 * @param $var
 */
if ( ! function_exists( 'dd' ) )
{
    function dd( $var = '' )
    {
        if ( $var )
        {
            echo "<pre>";
            var_dump( $var );
            echo "</pre>";
        }
        die();
    }
}




















