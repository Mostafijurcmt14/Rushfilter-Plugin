<?php
defined( 'ABSPATH' ) || exit;
?>
<?php 
include( plugin_dir_path(__FILE__) . 'rushfilter-header.php' );

 
	global $wpdb;
	$table_name = $wpdb->prefix.'rush_filter';
	$getResult =  $wpdb->get_results("SELECT * FROM $table_name");

?>
<div class="rushfilter-lists">
<div id="blogListFrame" onload="blog_list()">
	<table>
		<tbody>
			<tr>
				<th><?php echo esc_html__('ID', 'rush-filter') ?></th>
				<th><?php echo esc_html__('Filter Shortcode', 'rush-filter') ?></th>
				<th><?php echo esc_html__('Post Type', 'rush-filter') ?></th>
				<th><?php echo esc_html__('Published', 'rush-filter') ?></th>
				<th><?php echo esc_html__('Edit', 'rush-filter') ?></th>
				<th><?php echo esc_html__('Delete', 'rush-filter') ?></th>
			</tr>
			
			<?php	
			foreach( $getResult as $getResults ) :
			
			?>
			<tr>
				<td><?php echo $getResults->id; ?></td>
				<td class="shortcode-td"><span>[rushfilter_filter post_id="<?php echo $getResults->id; ?>" post_type="<?php echo $getResults->post_type; ?>"]</span></td>
				<td class="center col-small"><?php echo $getResults->post_name; ?></td>
				<td class="center col-small">
					<label class="switch">
					  <input type="checkbox" checked>
					  <span class="slider round"></span>
					</label>
				</td>
				<td class="center col-small"><a href="<?php echo esc_url( admin_url( "/admin.php?page=rushfilter-edit&eid=$getResults->id" )); ?>" class="openeditmodal"><i class="fa-solid fa-pen"></i></a></td>
				<td class="center col-small"><a href="<?php echo esc_url( admin_url( "/admin.php?page=all-rushfilter&did=$getResults->id" )); ?>"><i class="fa-regular fa-trash-can"></i></a></td>
			</tr>
			<?php	
			endforeach;
			?>
				
	
		</tbody>
	</table>
	
	
	
	
	
	

	
	
</div>
</div>