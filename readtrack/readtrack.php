<?php
/*
 * Plugin Name:       ReadTrack
 * Plugin URI:        https://github.com/marcin-filipiak/wordpress_readtrack
 * Description:       Adds a reading progress bar and estimated reading time above post content.
 * Version:           1.1
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Author:            Marcin Filipiak
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       readtrack
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) exit;

define('READTRACK_CONFIG_FILE', plugin_dir_path(__FILE__) . 'config.txt');

// ===========================
// FRONTEND
// ===========================

add_action('wp_enqueue_scripts', 'readtrack_enqueue_assets');
function readtrack_enqueue_assets() {
    wp_enqueue_style('readtrack-style', plugin_dir_url(__FILE__) . 'assets/readtrack.css');
    wp_enqueue_script('readtrack-script', plugin_dir_url(__FILE__) . 'assets/readtrack.js', array(), false, true);
}

add_filter('the_content', 'readtrack_add_elements');
function readtrack_add_elements($content) {
    if (is_single()) {
        $custom_text = readtrack_get_custom_text();
        $word_count = str_word_count(strip_tags($content));
        $minutes = ceil($word_count / 200);
        $text = str_replace('%minutes%', $minutes, $custom_text);

        $reading_time = "<div class='readtrack-time'>{$text}</div>";
        $progress_bar = "<div class='readtrack-progress-container'><div class='readtrack-progress-bar'></div></div>";
        return $reading_time . $progress_bar . $content;
    }
    return $content;
}

function readtrack_get_custom_text() {
    if (!file_exists(READTRACK_CONFIG_FILE)) {
        return '⏱️ Estimated reading time: %minutes% min';
    }
    $text = trim(file_get_contents(READTRACK_CONFIG_FILE));
    return $text ?: '⏱️ Estimated reading time: %minutes% min';
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
    if (isset($_POST['readtrack_text'])) {
        $new_text = sanitize_text_field($_POST['readtrack_text']);
        file_put_contents(READTRACK_CONFIG_FILE, $new_text);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $current_text = htmlspecialchars(readtrack_get_custom_text());
    ?>
    <div class="wrap">
        <h1>ReadTrack Settings</h1>
        <form method="post">
            <label for="readtrack_text">Text displayed above post content (use <code>%minutes%</code> as placeholder):</label><br>
            <input type="text" name="readtrack_text" id="readtrack_text" value="<?php echo $current_text; ?>" style="width: 100%; max-width: 500px;"><br><br>
            <input type="submit" class="button button-primary" value="Save Changes">
        </form>
    </div>
    <?php
}

