<?php
/**
 * Handles the Logout from the app.
 * 
 * @category Functionality
 * @package FBAlbumBrowser
 * @author Shashwat Mittal <shashwat.mittal5@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.0.0
 * @link https://fb.shashwatmittal.com/logout.php
 */
session_start();
session_destroy(); // Unset the Access token recieved from Facebook.
header('Location: index.php'); // Redirects the user to the login page for Re-authorization.
exit;