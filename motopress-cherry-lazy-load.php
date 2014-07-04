<?php

// add Cherry shortcodes to library
add_action('mp_library', 'motopress_cherry_lazy_load_mp_library_action', 10, 1);

// filter shortcode render
add_filter('cherry_plugin_shortcode_output', 'motopress_cherry_lazy_load_output_filter', 10, 3);

// include php files
add_action('motopress_render_shortcode', 'motopress_cherry_lazy_load_render_action', 10, 1);

// filter shortcode output
function motopress_cherry_lazy_load_output_filter($content, $atts, $shortcodename)
{
    if ( !empty($shortcodename) && $shortcodename == "lazy_load_box" ) {

        require_once 'motopress-cherry-shortcodes-utils.php';

        extract(shortcode_atts(addStyleAtts(), $atts));

        $marginClasses = trim(getMarginClasses($margin));

        return '<div' . ( empty($marginClasses) ? '' : (' class="' . $marginClasses . '" ') ) . '>' . $content . '</div>';
    } else {
        return $content;
    }
}


// add cherry_lazy_load to MP Library
function motopress_cherry_lazy_load_mp_library_action($motopressCELibrary)
{
    global $motopress_cherry_default_title, $motopress_cherry_default_text, $motopressCELang;
    
    $openInEditorText = empty($motopressCELang) ? 'Open in WordPress Editor' : $motopressCELang->CEOpenInWPEditor;

    $motopressCELibrary->addObject(
        new MPCEObject(
            'lazy_load_box',
            'Lazy Load Boxes',
            null,
            array(
                'shortcode_content' => array(
                    'type' => 'longtext-tinymce',
                    'label' => 'Content',
                    'text' => $openInEditorText,
                    'default' => '[spacer]<h1>' . $motopress_cherry_default_title . '</h1><h3>' . $motopress_cherry_default_text . '</h3>[spacer]',
                    'saveInContent' => 'true'
                ),
                'effect' => array(
                    'type' => 'select',
                    'label' => "Effect",
                    'default' => 'fade',
                    'list' => array(
                        'fade' => 'Fade',
                        'slideup' => 'Slideup',
                        'slidefromleft' => 'Slide from left',
                        'slidefromright' => 'Slide from right',
                        'zoomin' => 'Zoom in',
                        'zoomout' => 'Zoom out',
                        'rotate' => 'Rotate',
                        'skew' => 'Skew',
                    ),
                ),
                'delay' => array(
                    'type' => 'slider',
                    'label' => 'Delay',
                    'default' => 0,
                    'min' => 0,
                    'max' => 1000
                ),
                'speed' => array(
                    'type' => 'slider',
                    'label' => 'Speed',
                    'default' => 600,
                    'min' => 100,
                    'max' => 1000
                ),
                'custom_class' => array(
                    'type' => 'text',
                    'label' => "Custom class",
                ),
            ),
            0,
            MPCEObject::ENCLOSED
        ),
        'other'
    );
}

// include cherry lazy_load class file
function motopress_cherry_lazy_load_render_action($shortcode)
{
    if ( file_exists(MOTOPRESS_CHERRYFRAMEWORK_PLUGIN_DIR . '../cherry-lazy-load/cherry-lazy-load.php') )
        include_once (MOTOPRESS_CHERRYFRAMEWORK_PLUGIN_DIR . '../cherry-lazy-load/cherry-lazy-load.php');
}
