<?php
/*
 * WAP Plugin For WordPress
 *	- wp-wap-comments.php
 *
 * Copyright © 2004-2005 Lester "GaMerZ" Chan
*/


// Set Header To WML
header('Content-Type: text/vnd.wap.wml');

// We Are Doing RSS
$feed = 'rss';
$doing_rss = 1;

// Wordpress Stuffs
require('wp-blog-header.php');

// Get Post ID
$p = intval($_GET['p']);

// If $p Is Not Empty
if ($p > 0) {
	$comments = $wpdb->get_results("SELECT comment_ID, comment_author, comment_author_email, comment_author_url, comment_date,	comment_content, comment_post_ID, $wpdb->posts.ID, $wpdb->posts.post_password FROM $wpdb->comments LEFT JOIN $wpdb->posts ON comment_post_ID = ID WHERE comment_post_ID = '$p' AND $wpdb->comments.comment_approved = '1' AND $wpdb->posts.post_status = 'publish' AND post_date_gmt < '".gmdate("Y-m-d H:i:s")."' ORDER BY comment_date");
	$post = $wpdb->get_row("SELECT post_title, comment_status FROM $wpdb->posts WHERE ID = '$p' AND post_date < post_date_gmt < '".gmdate("Y-m-d H:i:s")."' AND post_status = 'publish'");
// Else Display Last 10 Comments
} else {
	$comments = $wpdb->get_results("SELECT comment_ID, comment_author, comment_author_email, comment_author_url, comment_date, comment_content, comment_post_ID, $wpdb->posts.ID, $wpdb->posts.post_password FROM $wpdb->comments LEFT JOIN $wpdb->posts ON comment_post_id = id WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->comments.comment_approved = '1' AND post_date_gmt < '".gmdate("Y-m-d H:i:s")."' ORDER BY comment_date DESC LIMIT 10");
	$post = $wpdb->get_row("SELECT post_title, comment_status FROM $wpdb->posts WHERE post_date_gmt < '".gmdate("Y-m-d H:i:s")."' AND post_status = 'publish' ORDER BY post_date DESC");
}

// Echo XML
echo "<?xml version=\"1.0\"?".">\n";
?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.2//EN" "http://www.wapforum.org/DTD/wml12.dtd">
<wml>
<card id="WordPress" title="<?php bloginfo_rss('name'); ?>">
<?php
	$post_title = $post_title = htmlspecialchars(stripslashes($post->post_title));
	echo "<p>Comments On Post<br />&gt; $post_title</p><p>&nbsp;</p>";
?>
<?php if ($comments) : ?>
	<?php foreach ($comments as $comment) : ?>
			<p>&gt; <?php comment_author_rss() ?></p>
			<p>&gt; <?php comment_time('d.m.Y @ H:i'); ?></p>
			<p><?php comment_text_rss() ?></p>
			<p>&nbsp;</p>
	<?php endforeach; ?>
<?php else : ?>
	<?php if ('open' == $post->comment_status) : ?> 
		<p>No Comments Are Posted Yet.</p>
	<?php else : ?>
		<p>Comments Are Closed.</p>
	<?php endif; ?>
<?php endif; ?>
<p><a href="wp-wap.php">&lt;&lt; <?php bloginfo_rss('name'); ?></a></p>
</card>
</wml>