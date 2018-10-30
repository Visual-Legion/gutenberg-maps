<?php
/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

// TODO MAKE MORE LILE MY THEMES SETTINGS
// VALIDATOR FUNCTIONS FOR EACH TYPE OF INPUTS


function vl_gm_validate_options($input) {
 // Create our array for storing the validated options
    $output = array();
     
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
         
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) &&  $input[$key] != '') {
         
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
             
        } 
        //shouldn't it not save the empty fields?
         
    } // end foreach
     
    return $output;
}

 
/**
 * custom option and settings
 */
function vl_gm_settings_init() {
 // register a new setting for "vl_gm" page
 register_setting( 'vl_gm', 'vl_gm_options', 'vl_gm_validate_options' );
 
 // register a new section in the "vl_gm" page
 add_settings_section(
 'vl_gm_section_developers',
 __( 'Gutenberg google maps Settings', 'vl_gm' ),
 'vl_gm_section_developers_cb',
 'vl_gm'
 );
 
 // register a new field in the "vl_gm_section_developers" section, inside the "vl_gm" page
 add_settings_field(
 'vl_gm_google_maps_api_key', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 __( 'Google maps API key', 'vl_gm' ),
 'vl_gm_google_maps_api_key_cb',
 'vl_gm',
 'vl_gm_section_developers',
 [
 'label_for' => 'vl_gm_google_maps_api_key',
 'class' => 'vl_gm_row',
 'vl_gm_google_maps_api_key_custom_data' => 'custom',
 ]
 );
}
 
/**
 * register our vl_gm_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'vl_gm_settings_init' );
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function vl_gm_section_developers_cb( $args ) { 
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php _e( 'Text asking user to get maps api key and instructions + link on how to do so', 'vl_gm' ); ?></p> 
 <?php
}


 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function vl_gm_google_maps_api_key_cb( $args ) {
 // get the value of the setting we've registered with register_setting()
 $options = get_option( 'vl_gm_options' );
 // output the field
 ?>

<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" data-custom="<?php echo esc_attr( $args['vl_gm_google_maps_api_key_custom_data'] ); ?>" name="vl_gm_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo isset( $options[ $args['label_for'] ] ) ? ( ( $options[ $args['label_for'] ]) ) : ( '' ); ?>">

 <p class="description">
 <?php esc_html_e( 'Please enter your api key', 'vl_gm' ); ?>
 </p>
 <?php
}

// print_r(get_option('vl_gm_options')['vl_gm_google_maps_api_key']);
 
/**
 * top level menu
 */
function vl_gm_options_page() {
 // add top level menu page
 add_menu_page(
 'Gutenberg Maps',
 'Gutenberg Maps',
 'manage_options',
 'vl_gm',
 'vl_gm_options_page_html'
 );
}
 
/**
 * register our vl_gm_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'vl_gm_options_page' );
 
/**
 * top level menu:
 * callback functions
 */
function vl_gm_options_page_html() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }
 
 // add error/update messages
 
 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'vl_gm_messages', 'vl_gm_message', __( 'Settings Saved', 'vl_gm' ), 'updated' );
 }
 
 // show error/update messages
 settings_errors( 'vl_gm_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "vl_gm"
 settings_fields( 'vl_gm' );
 // output setting sections and their fields
 // (sections are registered for "vl_gm", each field is registered to a specific section)
 do_settings_sections( 'vl_gm' );
 // output save settings button
 submit_button( 'Save Settings' );
 ?>
 </form>
 </div>
 <?php
}