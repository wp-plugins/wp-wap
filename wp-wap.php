<?php
/*
+----------------------------------------------------------------+
|																							|
|	WordPress 2.0 Plugin: WP-Wap 2.00										|
|	Copyright (c) 2005 Lester "GaMerZ" Chan									|
|																							|
|	File Written By:																	|
|	- Lester "GaMerZ" Chan															|
|	- http://www.lesterchan.net													|
|																							|
|	File Information:																	|
|	- WAP Friendly Page	For Blog Post											|
|	- wp-wap.php																		|
|																							|
+----------------------------------------------------------------+
*/


### We Need RSS
if (empty($wp)) {
	require_once('wp-config.php');
	wp('feed=rss');
}

### Set Header To WML
header('Content-Type: text/vnd.wap.wml;charset=utf-8');

### Function: Get Recent Posts
function gmz_get_recentposts($limit = 5) {
    global $wpdb;
    $recentposts = $wpdb->get_results("SELECT $wpdb->posts.ID as post_id, post_title, post_date FROM $wpdb->posts WHERE post_date < '".current_time('mysql')."' AND post_status = 'publish' ORDER  BY post_date DESC LIMIT $limit");
    foreach ($recentposts as $recentpost) {
			$post_id = intval($recentpost->post_id);
			$post_title = htmlspecialchars(stripslashes($recentpost->post_title));
			$post_date = mysql2date('d.m.Y', $recentpost->post_date);
			echo "<p>$post_date<br />-&nbsp;<a href=\"wp-wap.php?p=$recentpost->post_id\">$post_title</a></p>\n";
    }
}

### Function: Get Category Name
function gmz_get_category() {
	echo strip_tags(get_the_category_list());
}

### Echo XML
echo '<?xml version="1.0" encoding="utf-8"?'.'>';
?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD WML 2.0//EN" "http://www.wapforum.org/dtd/wml20.dtd">
<wml>
<card id="WordPress" title="<?php bloginfo_rss('name'); ?>">
<?php if(empty($_GET['p'])) { gmz_get_recentposts(); } else { ?>
		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>
				<p>&gt; <?php the_title_rss(); ?></p>
				<p>&gt; <?php the_time("d.m.Y"); ?></p>
				<p>&gt; In <?php gmz_get_category(); ?></p>
				<p>&gt; <a href="wp-wap-comments.php?p=<?php the_ID() ?>"><?php comments_number("No Comments", "1 Comment", "% Comments"); ?></a></p>
				<p><?php the_content_rss(); ?></p>
			<?php endwhile; ?>
		<?php else : ?>
			<p>No Posts Matched Your Criteria</p>
		<?php endif; ?>
<p>&nbsp;</p>
<p><a href="wp-wap.php">&lt;&lt; <?php bloginfo_rss('name'); ?></a></p>
<?php } ?>
</card>
</wml>