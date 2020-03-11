<?php

/**
 * @package messagesend
 * @version 1.0.0
 */
/*
Plugin Name: messagesend
Plugin URI: http://wordpress.org/plugins/messagesend/
Description: This is not just a plugin, it is my first plugin !
Author: Laslaa Mohammed
Version: 1.0.0
Author URI: http://www.laslaamohammed.fr/
*/

function send_message()
{
    if (isset($_POST['object']) && isset($_POST['email']) && isset($_POST['message'])) {
        global $wpdb;
        $wpdb->insert("wp_message", ['object' => esc_html($_POST['object']), 'email' => esc_html($_POST['email']), 'message' => esc_html($_POST['message'])]);
    }

    $text = "<form method='POST' class='col-4 mx-auto formsendmessage'>
            <div class='form-group'>
                <label for='object'>Objet</label>
                <input type='text' class='form-control' name='object' id='object'>
            </div>
            <div class='form-group'>
                <label for='email'>Email</label>
                <input type='text' class='form-control emailsend' name='email' id='email' placeholder='Entrez un email'>
            </div>
            <div class='form-group'>
                <label for='message'>Message</label>
                <textarea type='text' class='form-control' name='message' id='message'>
                </textarea>
            </div>
            <button type='submit' class='btn btn-primary mt-5'>Contacter</button>
        </form>";

    echo $text;
}

add_shortcode('lm_formmessage', 'send_message');

function form_to_send()
{
?>
    <div class="wrap">
        <h1>Mes messages</h1>
        <p>Si vous souhaitez utiliser ce plugin dans vos pages, veuillez utiliser le shortcode suivant : [lm_formmessage]</p>
        <?php
        settings_fields('wporg_options');
        do_settings_sections('wporg');
        echo "<table class='lm_messagetable'>
                <tr>
                    <th>Objet</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>";
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM wp_message WHERE 1");
        $show = "";
        foreach ($results as $table) {
            $show .= "<tr>";
            foreach ($table as $key => $value) {
                if ($key !== "id") {
                    $show .= "<th>$value</th>";
                }
            }
            $show .= "</tr>";
        }

        $show .= "</table>";
        echo $show;
        ?>
    </div>
<?php
}

function message_options_page()
{
    add_menu_page(
        'Message',
        'Messages',
        'manage_options',
        'Message',
        'form_to_send',
        'dashicons-admin-comments',
        20
    );
}

function activate()
{
    global $wpdb;
    $table = $wpdb->prefix . 'message';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table (
    id int(9) NOT NULL AUTO_INCREMENT,
    object varchar(255) NOT NULL,
    email varchar(50) NOT NULL,
    message varchar(55) DEFAULT '' NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function desactivate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'message';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
    //delete_option("jal_db_version");
}

register_activation_hook(__FILE__, 'activate');
register_deactivation_hook(__FILE__, 'desactivate');
add_action('admin_menu', 'message_options_page');

function message_css()
{
    echo "
    <style type='text/css'>
    
	.lm_messagetable {
        color : black;
        background-color: white;
        width: 100%;
    }

    .lm_messagetable th{
        padding : 10px;
    }
	</style>
	";
}

add_action('admin_head', 'message_css');

// echo plugin_dir_url(__FILE__)  .  'messagesend.js';

function wptuts_scripts_basic()
{
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), 1.0, true);
    wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'messagesend.js', array(), null, true);
}

add_action('wp_enqueue_scripts', 'wptuts_scripts_basic');
