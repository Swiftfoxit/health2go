<?php
/**
 * JobBoard Map Admin.
 *
 * Action/filter hooks used for JobBoard Map admin.
 *
 * @author 		FOX
 * @category 	Core
 * @package 	JobBoard/Map
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (! class_exists('JB_Map_Admin')) :
    class JB_Map_Admin{

        function __construct()
        {
            add_filter( 'jobboard_admin_sections', array($this, 'sections_admin'));
            add_filter( 'jobboard_admin_profile_sections', array($this, 'sections_admin_profile'));
            add_filter( 'jobboard_job_sections', array($this, 'sections_post'));
            add_filter( 'jobboard_event_sections', array($this, 'sections_event'));
            add_filter( 'jobboard_location_sections', array($this, 'sections_location'));
            add_filter( 'jobboard_candidate_profile_fields', array($this, 'sections_profile'), 20);
            add_filter( 'jobboard_employer_profile_fields', array($this, 'sections_profile'), 20);
            add_filter( 'jobboard_profile_custom_fields', array($this, 'sections_profile'));
            add_filter( 'rc_map_api', array($this, 'set_map_api'));
        }

        function sections_admin($sections){

            $sections['map-settings'] = array(
                'title'            => esc_html__( 'Live Map', JB_MAP_TEXT_DOMAIN ),
                'id'               => 'map-settings',
                'icon'             => 'dashicons dashicons-location-alt',
                'desc'             => esc_html__( 'Listing jobs on the location.', JB_MAP_TEXT_DOMAIN ),
                'fields'           => array(
                    array(
                        'id'       => 'map-api',
                        'type'     => 'text',
                        'title'    => esc_html__( 'API Key', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Google map api key.', JB_MAP_TEXT_DOMAIN ),
                        'desc'     => sprintf(esc_html__( 'You can get Api Key %sHere →%s', JB_MAP_TEXT_DOMAIN ), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">', '</a>'),
                    ),
                    array(
                        'id'       => 'map-default',
                        'type'     => 'rc_map',
                        'title'    => esc_html__( 'Set Default Map', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Default zoom level, map center. Search a location and right mouse click on map make a marker (right click on marker = remove marker).', JB_MAP_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'       => 'map-geolocation',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Auto Detect Location', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Auto detect client location, suggestions related jobs in the region.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'map-marker',
                        'type'     => 'media',
                        'title'    => esc_html__( 'Marker Icon', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Custom your map marker, you can select a image (.jpg/.png/.svg/.gif)', JB_MAP_TEXT_DOMAIN ),
                    ),
                    array(
                        'id'       => 'map-marker-group',
                        'type'     => 'image_select',
                        'title'    => esc_html__( 'Group Markers', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Select a style for group markers.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => 'default',
                        'options'  => $this->get_markers_options()
                    ),
                    array(
                        'id'       => 'map-marker-limit',
                        'type'     => 'slider',
                        'title'    => esc_html__( 'Markers Limit', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Limit jobs show in map.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => 20,
                        'min'      => 5,
                        'step'     => 1,
                        'max'      => 100,
                        'display_value' => 'label'
                    ),
                    array(
                        'id'       => 'map-style',
                        'type'     => 'image_select',
                        'title'    => esc_html__( 'Map Style', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Select a style or custom.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => 'standard',
                        'options'  => array(
                            'standard'=> array(
                                'alt' => esc_html__('Standard', JB_MAP_TEXT_DOMAIN),
                                'img' => jb_map()->plugin_directory_uri . 'assets/images/standard.png'
                            ),
                            'silver'=> array(
                                'alt' => esc_html__('Silver', JB_MAP_TEXT_DOMAIN),
                                'img' => jb_map()->plugin_directory_uri . 'assets/images/silver.png'
                            ),
                            'retro'=> array(
                                'alt' => esc_html__('Retro', JB_MAP_TEXT_DOMAIN),
                                'img' => jb_map()->plugin_directory_uri . 'assets/images/retro.png'
                            ),
                            'dark'=> array(
                                'alt' => esc_html__('Dark', JB_MAP_TEXT_DOMAIN),
                                'img' => jb_map()->plugin_directory_uri . 'assets/images/dark.png'
                            ),
                            'night'=> array(
                                'alt' => esc_html__('Night', JB_MAP_TEXT_DOMAIN),
                                'img' => jb_map()->plugin_directory_uri . 'assets/images/night.png'
                            ),
                            'aubergine'=> array(
                                'alt' => esc_html__('Aubergine', JB_MAP_TEXT_DOMAIN),
                                'img' => jb_map()->plugin_directory_uri . 'assets/images/aubergine.png'
                            ),
                            'custom'=> array(
                                'alt' => esc_html__('Custom', JB_MAP_TEXT_DOMAIN),
                                'img' => jb_map()->plugin_directory_uri . 'assets/images/custom.png'
                            )
                        )
                    ),
                    array(
                        'id'       => 'map-style-custom',
                        'type'     => 'textarea',
                        'title'    => esc_html__( 'Custom Style (JSON)', JB_MAP_TEXT_DOMAIN ),
                        'desc'     => sprintf('%1$s <a href="%2$s" target="_blank">%2$s</a>', esc_html__( 'Custom your map style here.', JB_MAP_TEXT_DOMAIN ), 'https://mapstyle.withgoogle.com'),
                        'subtitle' => esc_html__( 'Copy and paste the JSON into text field.', JB_MAP_TEXT_DOMAIN ),
                        'required' => array( 'map-style', '=', 'custom' ),
                    )
                )
            );

            $sections['map-controls'] = array(
                'title'            => esc_html__( 'Controls', JB_MAP_TEXT_DOMAIN ),
                'id'               => 'map-controls',
                'icon'             => 'dashicons dashicons-move',
                'subsection'       => true,
                'desc'             => esc_html__( 'Show or hide map controls.', JB_MAP_TEXT_DOMAIN ),
                'fields'           => array(
                    array(
                        'id'       => 'map-control-zoom',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Zoom', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'The Zoom control displays "+" and "-" buttons for changing the zoom level of the map. This control appears by default in the bottom right corner of the map.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'map-control-maptype',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Map Type', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'The Map Type control is available in a dropdown or horizontal button bar style, allowing the user to choose a map type (ROADMAP, SATELLITE, HYBRID, or TERRAIN). This control appears by default in the top left corner of the map.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'map-control-scale',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Scale', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'The Scale control displays a map scale element. This control is disabled by default.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'map-control-streetview',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Street View', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( "The Street View control contains a Pegman icon which can be dragged onto the map to enable Street View. This control appears by default near the bottom right of the map.", JB_MAP_TEXT_DOMAIN ),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'map-control-rotate',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Rotate', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( "The Rotate control provides a combination of tilt and rotate options for maps containing oblique imagery. This control appears by default near the bottom right of the map.", JB_MAP_TEXT_DOMAIN ),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'map-control-fullscreen',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Full Screen', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( "The Fullscreen control offers the option to open the map in fullscreen mode. This control is enabled by default on mobile devices, and is disabled by default on desktop. Note: iOS doesn't support the fullscreen feature. The fullscreen control is therefore not visible on iOS devices.", JB_MAP_TEXT_DOMAIN ),
                        'default'  => true,
                    )
                )
            );

            $sections['map-search'] = array(
                'title'            => esc_html__( 'Search', JB_MAP_TEXT_DOMAIN ),
                'id'               => 'map-search',
                'icon'             => 'dashicons dashicons-location',
                'subsection'       => true,
                'desc'             => esc_html__( 'Map search controls.', JB_MAP_TEXT_DOMAIN ),
                'fields'           => array(
                    array(
                        'id'       => 'map-search-control',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Search', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'The Search control displays a search form, search all jobs in current map location.', JB_MAP_TEXT_DOMAIN ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'map-search-active',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Is Active', JB_MAP_TEXT_DOMAIN ),
                        'subtitle' => esc_html__( 'Active tab search in map, default hide in tab.', JB_MAP_TEXT_DOMAIN ),
                        'required' => array( 'map-search-control', '=', 1 ),
                        'default'  => false,
                    )
                )
            );

            return $sections;
        }

        function sections_admin_profile($sections){
            $sections[] = array (
                'id'         => 'map',
                'title'      => esc_html__('Geo Location', JB_MAP_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Search a location or right mouse click on map.', JB_MAP_TEXT_DOMAIN ),
                'type'       => 'rc_map',
            );
            return $sections;
        }

        function sections_post($sections){

            $sections['location']['fields'][] = array(
                'id'       => '_map',
                'type'     => 'rc_map',
                'title'    => esc_html__( 'Geo Location', JB_MAP_TEXT_DOMAIN ),
                'subtitle' => esc_html__( 'Search a location or right mouse click on map (right click on marker = remove marker).', JB_MAP_TEXT_DOMAIN )
            );

            return $sections;
        }

	    function sections_event($sections){

		    $sections['setting']['fields'][] = array(
			    'id'       => '_map',
			    'type'     => 'rc_map',
			    'title'    => esc_html__( 'Geo Location', JB_MAP_TEXT_DOMAIN ),
			    'subtitle' => esc_html__( 'Search a location or right mouse click on map (right click on marker = remove marker).', JB_MAP_TEXT_DOMAIN )
		    );

		    return $sections;
	    }

        function sections_location($sections){

            $sections['basic']['fields'][] = array(
                'id'       => '_map',
                'type'     => 'rc_map',
                'title'    => esc_html__( 'Geo Location', JB_MAP_TEXT_DOMAIN ),
                'subtitle' => esc_html__( 'Search a location or right mouse click on map (right click on marker = remove marker).', JB_MAP_TEXT_DOMAIN )
            );

            return $sections;
        }

        function sections_profile($sections){
            $sections[] = array(
                'id'         => 'map-heading',
                'title'      => esc_html__('Location', JB_MAP_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Add your location.', JB_MAP_TEXT_DOMAIN ),
                'type'       => 'heading',
                'heading'    => 'h3'
            );

            $sections[] = array (
                'id'         => 'map',
                'title'      => esc_html__('Geo Location', JB_MAP_TEXT_DOMAIN ),
                'subtitle'   => esc_html__('Search a location or right mouse click on map.', JB_MAP_TEXT_DOMAIN ),
                'type'       => 'geolocation',
                'value'      => array(
                    's'     => '',
                    'lat'   => '',
                    'lng'   => '',
                    'zoom'  => '',
                )
            );
            return $sections;
        }

        function get_markers_options(){

            $markers = array('default' => array(
                'alt' => esc_html__('default', JB_MAP_TEXT_DOMAIN),
                'img' => jb_map()->plugin_directory_uri . 'assets/images/markers.png'
            ));

            $path           = JB()->template_path() . 'add-ons/map/markers/';
            $template_dir   = apply_filters('jobboard_map_options_markers_dir', get_template_directory() . '/' . $path);
            $template_uri   = apply_filters('jobboard_map_options_markers_uri', get_template_directory_uri() . '/' . $path);

            if(file_exists($template_dir)){
                $files = array_diff(scandir($template_dir), array('..', '.'));
                if(!empty($files)) {
                    foreach ($files as $file) {
                        $markers[$file] = array(
                            'alt' => $file,
                            'img' => $template_uri . $file . '/1.png'
                        );
                    }
                }
            }

            return apply_filters('jobboard_map_options_markers_args' ,$markers);
        }

        function set_map_api(){
            return jb_get_option('map-api');
        }
    }
endif;

new JB_Map_Admin();