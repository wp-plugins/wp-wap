<?php
/*
 * WAP Plugin For WordPress
 *	- wp-wap.php
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

// Function Get Recent Posts
function gmz_get_recentposts($limit = 5) {
    global $wpdb;
    $recentposts = $wpdb->get_results("SELECT $wpdb->posts.ID as post_id, post_title, post_date FROM $wpdb->posts WHERE post_date_gmt < '".gmdate("Y-m-d H:i:s")."' AND post_status = 'publish' ORDER  BY post_date DESC LIMIT $limit");
    foreach ($recentposts as $recentpost) {
			$post_id = intval($recentpost->post_id);
			$post_title = htmlspecialchars(stripslashes($recentpost->post_title));
			$post_date = mysql2date('d.m.Y', $recentpost->post_date);
			echo "<p>$post_date<br />-&nbsp;<a href=\"wp-wap.php?p=$recentpost->post_id\">$post_title</a></p>\n";
    }
}

// Echo XML
echo "<?xml version=\"1.0\"?".">\n";
?>
<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.2//EN" "http://www.wapforum.org/DTD/wml12.dtd">
<wml>
<card id="WordPress" title="<?php bloginfo_rss('name'); ?>">
<?php if(empty($_GET['p'])) { gmz_get_recentposts(); } else { ?>
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<p>&gt; <?php the_title_rss(); ?></p>
				<p>&gt; <?php the_time("d.m.Y"); ?></p>
				<p>&gt; In <?php the_category(); ?></p>
				<p>&gt; <a href="wp-wap-comments.php?p=<?php the_ID() ?>"><?php comments_number("No Comments", "1 Comment", "% Comments"); ?></a></p>
				<p><?php the_content_rss(); ?></p>
			<?php endwhile; ?>
		<?php else : ?>
			<p>No Posts Matched Your Criteria</p>
		<?php endif; ?>
<p><a href="wp-wap.php">&lt;&lt; <?php bloginfo_rss('name'); ?></a></p>
<?php } ?>
</card>
</wml>