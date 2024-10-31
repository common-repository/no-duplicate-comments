<?php
/*
Plugin Name: No Duplicate Comments
Version: 0.9.2
Plugin URI: http://www.coffee2code.com/wp-plugins/
Author: Scott Reilly
Author URI: http://www.coffee2code.com
Description: Prevent visitors from leaving duplicate comments/pingbacks/trackbacks to a post.

=>> Visit the plugin's homepage for more information and latest updates  <<=

Installation:

1. Download the file http://www.coffee2code.com/wp-plugins/no-duplicate-comments.zip and unzip it into your 
/wp-content/plugins/ directory.
-OR-
Copy and paste the the code ( http://www.coffee2code.com/wp-plugins/no-duplicate-comments.phps ) into a file called 
no-duplicate-comments.php, and put that file into your /wp-content/plugins/ directory.
2. Activate the plugin from your WordPress admin 'Plugins' page.

Notes:

* Requires at least WordPress 1.5.

* A duplicate is a comment/pingback/trackback that meets these criteria:
Its contents match that of another comment in the same post
-AND-
Either the commenter's name or e-mail address (if provided) also match.

I won't claim this approach won't lead to false-positives, but as far as I'm concerned it's reasonable.  

* If a comment is deemed to be a duplicate, an error page is displayed to the user indicating as much.


*/

/*
Copyright (c) 2005 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

function c2c_no_duplicate_comments ( $comment_text ) {
	global $wpdb, $commentdata;
	
	$sql  = "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_post_ID = '" . $commentdata['comment_post_ID'] . "' ";
	$sql .= "AND ( comment_author = '" . addslashes($commentdata['comment_author']) . "' ";
	if ( $commentdata['comment_author_email'] ) $sql .= " OR comment_author_email = '" . addslashes($commentdata['comment_author_email']) . "' ";
	$sql .= ") ";
	$sql .= "AND comment_content = '" . addslashes($comment_text) . "' ";
	$sql .= "LIMIT 1";
	
	if ( $wpdb->get_var($sql) ) die( __('Duplicate comment detected; it looks as though you\'ve already said that!') );
	
	return $comment_text;
} //end c2c_no_duplicate_comments()

add_filter('pre_comment_content', 'c2c_no_duplicate_comments');

?>