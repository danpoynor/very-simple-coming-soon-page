<?php
/*
Plugin Name: Very Simple Coming Soon Page
Description: This plugin provides a very simple "Coming Soon" page for your website. When enabled, visitors who are not logged in will see the coming soon page, while users who are logged in to the WordPress admin will see the actual website. This allows you to build and modify your website without showing incomplete changes to your visitors. The plugin also includes settings for customizing the message, adding an image, and customizing the background color and text color of the coming soon page.
Version: 1.0
Author: Dan Poynor
Author URI: https://danpoynor.com/
*/

// Call the delete_option function to remove the plugin settings from the database.
// Called when the user clicks on the "Clear Settings" button on the settings page.
function vscsp_reset_settings() {
    if (isset($_POST['vscsp_reset']) && check_admin_referer('vscsp_reset_action', 'vscsp_reset_nonce')) {
        delete_option('vscsp_options');
    }
}
add_action('admin_init', 'vscsp_reset_settings');

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'vscsp_activate');
register_deactivation_hook(__FILE__, 'vscsp_deactivate');

function vscsp_activate() {
    // Code to run when the plugin is activated
}

function vscsp_deactivate() {
    // Code to run when the plugin is deactivated
}

// Check if user is logged in and plugin is enabled
function vscsp_check_user() {
    // Get plugin settings
    $vscsp_options = get_option('vscsp_options');

    // Check if plugin is enabled and user is not logged in
    if (isset($vscsp_options['enabled']) && $vscsp_options['enabled'] && !is_user_logged_in()) {
        // Show coming soon page
        include plugin_dir_path(__FILE__) . 'coming-soon.php';
        exit();
    }
}
add_action('template_redirect', 'vscsp_check_user');

// Enqueue styles for the admin
function vscsp_enqueue_admin_styles() {
    wp_enqueue_style('vscsp-admin-style', plugin_dir_url(__FILE__) . 'admin-style.css');
}
add_action('admin_enqueue_scripts', 'vscsp_enqueue_admin_styles');

// Add a notice to the admin bar
function vscsp_add_admin_bar_item($wp_admin_bar) {
    // Get plugin settings
    $vscsp_options = get_option('vscsp_options');

    // Determine the value of the data-coming-soon attribute and the visible span
    $coming_soon = isset($vscsp_options['enabled']) && $vscsp_options['enabled'];
    $coming_soon_attr = $coming_soon ? 'true' : 'false';

    // Add a custom item to the admin bar
    $wp_admin_bar->add_node(array(
        'id'    => 'vscsp-site-status',
        'title' => "<div id='vscsp-site-status' data-coming-soon='{$coming_soon_attr}' title='View plugin settings'>Site Status: <span id='vscsp-site-status-coming-soon' class='vscsp-coming-soon-active' style='display: " . ($coming_soon ? 'inline-block' : 'none') . ";'>Not Live</span><span id='vscsp-site-status-live' class='vscsp-coming-soon-inactive' style='display: " . ($coming_soon ? 'none' : 'inline-block') . ";'>Live</span></div>",
        'href'  => admin_url('options-general.php?page=very-simple-coming-soon-page'),
        'parent' => 'top-secondary', // this makes it appear on the right side
    ));
}
add_action('admin_bar_menu', 'vscsp_add_admin_bar_item', 999);

//
// Settings page functions
//

// Create settings page
function vscsp_create_settings_page() {
    add_options_page(
        'Very Simple Coming Soon Page', // page_title
        'Very Simple Coming Soon Page', // menu_title
        'manage_options', // capability
        'very-simple-coming-soon-page', // menu_slug
        'vscsp_render_settings_page' // function
    );
}
add_action('admin_menu', 'vscsp_create_settings_page');

// Function to display the settings page form
function vscsp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "vscsp"
            settings_fields('vscsp');
            // output setting sections and their fields
            do_settings_sections('vscsp');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
        <form method="post">
            <input name="vscsp_reset" type="submit" value="Clear Settings" class="button"/>
            <?php wp_nonce_field( 'vscsp_reset_action', 'vscsp_reset_nonce' ); ?>
        </form>
    </div>
    <?php
}

// Register settings
function vscsp_register_settings() {
    register_setting('vscsp', 'vscsp_options');

    add_settings_section(
        'vscsp_section', // id
        'Settings', // title
        '', // callback function
        'vscsp' // page
    );

    // Add "Enable" field
    add_settings_field(
        'vscsp_enabled', // id
        'Enable', // title
        'vscsp_enabled_render', // callback function
        'vscsp', // page
        'vscsp_section' // section
    );

    // Add "Message" field
    add_settings_field(
        'vscsp_message', // id
        'Message', // title
        'vscsp_message_render', // callback function
        'vscsp', // page
        'vscsp_section' // section
    );

    // Add "Language" field
    add_settings_field(
        'vscsp_language', // id
        'Language', // title
        'vscsp_language_render', // callback function
        'vscsp', // page
        'vscsp_section' // section
    );

    // Add "Background Color" field
    add_settings_field(
        'vscsp_background_color', 
        'Background Color', 
        'vscsp_background_color_render', 
        'vscsp', 
        'vscsp_section'
    );
    
    // Add "Text Color" field
    add_settings_field(
        'vscsp_text_color', 
        'Text Color', 
        'vscsp_text_color_render', 
        'vscsp', 
        'vscsp_section'
    );

    // Add "Font Family" field
    add_settings_field(
        'vscsp_font_family', // id
        'Font Family', // title
        'vscsp_font_family_render', // callback function
        'vscsp', // page
        'vscsp_section' // section
    );

    // Add "Bold Text" field
    add_settings_field(
        'vscsp_bold_text', // id
        'Bold Text', // title
        'vscsp_bold_text_render', // callback function
        'vscsp', // page
        'vscsp_section' // section
    );

    // Add "Image" field
    add_settings_field(
        'vscsp_image', // id
        'Image', // title
        'vscsp_image_render', // callback function
        'vscsp', // page
        'vscsp_section' // section
    );

}
add_action('admin_init', 'vscsp_register_settings');

// Render the "Enable" field checkbox
function vscsp_enabled_render() {
    $options = get_option('vscsp_options');
    if (!is_array($options)) {
        $options = array();
    }
    ?>
    <input type='checkbox' name='vscsp_options[enabled]' <?php checked($options['enabled'] ?? 0, 1); ?> value='1'>
    <p class="description">Enabling the Coming Soon page lets you hide your site from visitors while you build or update your site.</p>
    <p class="description">If a user is logged in to the site they will be able to see the regular live site.</p>
    
    <?php
}

// Render the "Message" field text input
function vscsp_message_render() {
    $options = get_option('vscsp_options');
    if (!is_array($options)) {
        $options = array();
    }
    ?>
    <input type='text' name='vscsp_options[message]' value='<?php echo $options['message'] ?? 'Coming Soon'; ?>'>
    <?php
}

// Render the "Language" field select dropdown
function vscsp_language_render() {
    $options = get_option('vscsp_options');
    $selected = $options['language'] ?? 'en';
    $languages = array(
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'it' => 'Italian',
        'pt' => 'Portuguese',
        'ru' => 'Russian',
        'zh' => 'Chinese',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'ar' => 'Arabic',
        'hi' => 'Hindi',
        'bn' => 'Bengali',
        'pa' => 'Punjabi',
        'jv' => 'Javanese',
        'vi' => 'Vietnamese',
        'ta' => 'Tamil',
        'tr' => 'Turkish',
    );
    echo '<select name="vscsp_options[language]">';
    foreach ($languages as $code => $language) {
        echo '<option value="' . esc_attr($code) . '"' . selected($selected, $code, false) . '>' . esc_html($language) . '</option>';
    }
    echo '</select>';
}

// Render the "Background Color" field
function vscsp_background_color_render() {
    $options = get_option('vscsp_options', array('background_color' => '#ffffff')); // default to white
    if (!is_array($options)) {
        $options = array();
    }
    ?>
    <input type='color' name='vscsp_options[background_color]' value='<?php echo $options['background_color'] ?? '#ffffff'; ?>'>
    <?php
}

// Render the "Text Color" field
function vscsp_text_color_render() {
    $options = get_option('vscsp_options', array('text_color' => '#000000')); // default to black
    if (!is_array($options)) {
        $options = array();
    }
    ?>
    <input type='color' name='vscsp_options[text_color]' value='<?php echo $options['text_color'] ?? '#000000'; ?>'>
    <?php
}

// Render the "Font Family" field text input
function vscsp_font_family_render() {
    $options = get_option('vscsp_options');
    echo '<input type="text" name="vscsp_options[font_family]" value="' . esc_attr($options['font_family'] ?? '') . '">';
    echo '<p class="description" style="margin-bottom:.25rem">Enter the font family for the coming soon page. You can enter any valid CSS font family value. For example, you could enter "Arial, sans-serif" to use the Arial font, or "Courier New, monospace" to use the Courier New font.</p>';
    echo '<details>';
    echo '<summary>Click to see a few font stack examples</summary>';
    echo '<ul>';
    echo '<li>"Helvetica Neue", Helvetica, Arial, sans-serif</li>';
    echo '<li>"Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif</li>';
    echo '<li>"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Geneva, Verdana, sans-serif</li>';
    echo '<li>Georgia, Times, "Times New Roman", serif</li>';
    echo '<li>"Palatino Linotype", "Book Antiqua", Palatino, serif</li>';
    echo '<li>"Hoefler Text", "Baskerville old face", Garamond, "Times New Roman", serif</li>';
    echo '<li>"Courier New", Courier, "Lucida Sans Typewriter", "Lucida Typewriter", monospace</li>';
    echo '<li>"Consolas", monaco, monospace</li>';
    echo '<li>"Andale Mono", "Lucida Console", monospace</li>';
    echo '</ul>';
    echo '</details>';
}

// Render the "Bold Text" field checkbox
function vscsp_bold_text_render() {
    $options = get_option('vscsp_options');
    $checked = isset($options['bold_text']) && $options['bold_text'] ? 'checked' : '';
    echo '<input type="checkbox" name="vscsp_options[bold_text]" value="1" ' . $checked . '>';
    echo '<p class="description">If unchecked, the text will have normal font weight by default.</p>';
}

// Render the "Image" field file input
function vscsp_image_render() {
    $options = get_option('vscsp_options');
    if (!is_array($options)) {
        $options = array();
    }
    ?>
    <input type='hidden' id='vscsp_image' name='vscsp_options[image]' value='<?php echo $options['image'] ?? ''; ?>'>
    <input type='button' id='vscsp_image_button' class='button' value='Set Featured Image'>
    <div style='margin-top:1rem;'>
        <img id='vscsp_image_preview' src='<?php echo $options['image'] ?? ''; ?>' style='max-width:200px; max-height:200px;'>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('vscsp_image_button').addEventListener('click', function(e) {
            e.preventDefault();
            var custom_uploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false  // Set this to true to allow multiple files to be selected
            })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                document.getElementById('vscsp_image').value = attachment.url;
                document.getElementById('vscsp_image_preview').src = attachment.url;
            })
            .open();
        });
    });
    </script>
    <?php
}

function vscsp_enqueue_scripts($hook) {
    if ('settings_page_very-simple-coming-soon-page' != $hook) {
        // Only applies to settings page
        return;
    }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'vscsp_enqueue_scripts');
