<?php # -*- coding: utf-8 -*-
/* Plugin Name: AML Run Python */
add_shortcode( 'python', 'embed_python' );
function embed_python( $attributes )
{
    $data = shortcode_atts(
        array(
            'file' => 'hello.py'
        ),
        $attributes
    );
exec("/usr/bin/python $pyScript", $output);
var_dump($output);
}

