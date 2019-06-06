<?php

/**
 * Plugin Name:       WPE Template Info
 * Description:       This lets authors easily see which template a page is using and also see only pages using a particular template
 * Version:           1.0.0
 * Author:            Benjamin Bond for WP Engine
 * Author URI:        http://netswagger.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Add custom column for templates
add_filter( 'manage_page_posts_columns', 'page_template_collumn' );
function page_template_collumn($columns) {
    
    $columns['page_template'] = __( 'Template' );

    //I want to kep date as the last column
    $temp = $columns['date'];
    unset( $columns['date'] );
    $columns['date'] = $temp;

    return $columns;
}

// Add content to the colum to display temmplate name or slug
add_action( 'manage_page_posts_custom_column' , 'wpe_custom_page_column', 10, 2 );
function wpe_custom_page_column( $column, $post_id ) {

	$template = get_post_meta( $post_id, '_wp_page_template', true );

	$template_file = get_page_template_slug( $post_id);

	$template_contents = file_get_contents(get_template_directory().'/'.$template_file) ;
    
    //get theme object
    $templates = wp_get_theme()->get_page_templates( null, 'page' );

    echo $templates[$template_file];

}

// dropdown filter
add_action( 'restrict_manage_posts', 'wpe_filter_dropdown' );
function wpe_filter_dropdown(){
		if ( $GLOBALS['pagenow'] === 'upload.php' ) {
			return;
		}
	
		$template = isset( $_GET['wpe_template_filter'] ) ? $_GET['wpe_template_filter'] : "all"; 
		$default_title = apply_filters( 'default_page_template_title',  __( 'Default Template' ), 'meta-box' );
		?>
		<select name="wpe_template_filter" id="wpe_template_filter">
			<option value="all">All Page Templates</option>
			<option value="default" <?php echo ( $template == 'default' )? ' selected="selected" ' : "";?>><?php echo esc_html( $default_title ); ?></option>
			<?php page_template_dropdown($template); ?>
		</select>
		<?php	
	}

//Logic for filter 
add_filter( 'request', 'wpe_filter_logic' );
function wpe_filter_logic( $vars ){
	if ( ! isset( $_GET['wpe_template_filter'] ) ) return $vars;
	$template = trim($_GET['wpe_template_filter']);
	
	if ( $template == "" || $template == 'all' ) return $vars;
	$vars = array_merge(
		$vars,
		array(
			'meta_query' => array(
				array(
					'key'     => '_wp_page_template',
					'value'   => $template,
					'compare' => '=',
				),
			),
		)
	);
	return $vars;
}