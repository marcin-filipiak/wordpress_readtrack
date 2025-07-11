<?php
/*
 * Plugin Name:       ReadTrack
 * Plugin URI:        https://github.com/marcin-filipiak/wordpress_readtrack
 * Description:       Adds a reading progress bar and estimated reading time above post content.
 * Version:           1.2
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Author:            Marcin Filipiak
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       readtrack
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) exit;

// ===========================
// FRONTEND
// ===========================

add_action('wp_enqueue_scripts', 'readtrack_enqueue_assets');
function readtrack_enqueue_assets() {
    wp_enqueue_style(
        'readtrack-style',
        plugin_dir_url(__FILE__) . 'assets/readtrack.css',
        array(),
        '1.2'
    );
    wp_enqueue_script(
        'readtrack-script',
        plugin_dir_url(__FILE__) . 'assets/readtrack.js',
        array(),
        '1.2',
        true
    );
}

add_filter('the_content', 'readtrack_add_elements');
function readtrack_add_elements($content) {
    if (is_single()) {
        $custom_text = readtrack_get_custom_text();
        $word_count = str_word_count(wp_strip_all_tags($content));
        $minutes = ceil($word_count / 200);
        $text = str_replace('%minutes%', $minutes, $custom_text);

        $reading_time = "<div class='readtrack-time'>" . esc_html($text) . "</div>";
        $progress_bar = "<div class='readtrack-progress-container'><div class='readtrack-progress-bar'></div></div>";
        return $reading_time . $progress_bar . $content;
    }
    return $content;
}

function readtrack_get_custom_text() {
    $default = '⏱️ Estimated reading time: %minutes% min';
    $saved = get_option('readtrack_custom_text', '');
    return $saved !== '' ? $saved : $default;
}

// ===========================
// ADMIN PANEL
// ===========================

add_action('admin_menu', 'readtrack_admin_menu');
function readtrack_admin_menu() {
    add_options_page(
        'ReadTrack Settings',
        'ReadTrack',
        'manage_options',
        'readtrack-settings',
        'readtrack_settings_page'
    );
}

function readtrack_settings_page() {
    if (isset($_POST['readtrack_text']) && check_admin_referer('readtrack_save_settings')) {
        $new_text = sanitize_text_field(wp_unslash($_POST['readtrack_text']));
        update_option('readtrack_custom_text', $new_text);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $current_text = readtrack_get_custom_text();
    ?>
    <div class="wrap">
        <h1>ReadTrack Settings</h1>
        <form method="post">
            <?php wp_nonce_field('readtrack_save_settings'); ?>
            <label for="readtrack_text">
                Text displayed above post content (use <code>%minutes%</code> as placeholder):
            </label><br>
            <input type="text" name="readtrack_text" id="readtrack_text"
                   value="<?php echo esc_attr($current_text); ?>" style="width: 100%; max-width: 500px;"><br><br>
            <input type="submit" class="button button-primary" value="Save Changes">
        </form>
    </div>
    <?php
}

