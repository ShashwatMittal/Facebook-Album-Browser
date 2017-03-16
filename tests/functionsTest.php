<?php
/**
 * PHPUnit Testing implementation. Tests the functions in the project.
 * 
 * @category Testing
 * @package  FBAlbumBrowser
 * @author   Shashwat Mittal <shashwat.mittal5@gmail.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  1.0.0
 * @link     https://fb.shashwatmittal.com
 */
use PHPUnit\Framework\TestCase;

//require_once __DIR__ . 'config.php';
require_once 'functions.php';

class FunctionsTest extends TestCase {

    /**
     * Tests the downloading of a Test Image on the Server and saves
     * it inside the images folder in the project root directory.
     */
    public function testdonwloadImage() {

        $testurl = 'http://vignette1.wikia.nocookie.net/looneytunes/images/0/0b/
            Bugs-Bunny-Whats-Up-Doc-Cover.png/revision/latest?cb=20130209211559';
        $this->assertTrue( downloadImage( $testurl, 'images/' ) );
    }

    /**
     * Tests the zip functino by creating a zip of the images folder
     * and saving the zip file in the project root director.
     */
    public function testzip() {
        $this->assertTrue( zip( 'images', 'albums.zip' ) );
    }

    /**
     * Tests the deletePath functino by deleting the Images folder and
     * Zip file in the project root directory created by the above test
     * cases.
     */
    public Function testdeletePath() {
        $this->assertTrue( deletePath( 'images' ) );     // Deleting a Directory
        $this->assertTrue( deletePath( 'albums.zip' ) ); //Deleting a File.

    }

}
