# Facebook Album Browser


By [Shashwat Mittal](http://www.shashwatmittal.com)

## Description

Facebook Album Browser is a Web Application that allows the User to View their Public Facebook ALbums in Full Screen mode and Donwload the albums.


When the user visits the script page the application Prompts the user for Facebook Login and asks for the Necessary permissions required to display the albums.
After the user grants access it redirects the user to a Home page where it displays all the albums of a User as a thumbnail with Album names and download options.

When the user clicks on any Album thumbnail it start a slide-show of all the photos inside that album in full screen mode.

The user have to option to download a Single album, Multiple albums by selecting each album as desired by the user and they can also download all the albums at once.

When the user clicks on the download button the app fetches the photos in the album in the background and zips them into a Single zip file and prompts the user with a download link to that zip file.

For privacy issues the app deletes the images downloaded in the background as soon as the Zip file is created. Also the Zip file is deleted when the user dismisses the donwload modal presented after the zip has been made.

## Libraries Used:

* [Facebook Javascript SDK](https://developers.facebook.com/docs/javascript) for Login.
* [Facebook PHP SDK](https://developers.facebook.com/docs/reference/php/) for Fetching User Data.
* [Colorbox](http://www.jacklmoore.com/colorbox/) Library for displaying Slideshow.
* [jQuery](https://jquery.com/) for Javascript Library

## Frameworks Used:

* [Bootstrap](http://getbootstrap.com/) for the Styling of the Application.

## Package Manager:

* [Composer](https://getcomposer.org/)

## Others:

* [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) for Coding Standards
* [PHPUnit](https://phpunit.de/) for Unit Testing

