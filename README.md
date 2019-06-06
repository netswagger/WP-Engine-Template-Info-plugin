<img src="assets/badge.png" align="right" />

# WPE Template Info
This plugin was developed for WP Engine and add a custom meta field (checkbox) to each post to specify featured posts. If check the post will be set as featured and the WordPress rest API is then updated with the corresponding meta key information. You can then use the sites REST API endpoint to show the latest 5 featured post by hitting an endpoint like:

> domain.com/wp-json/wp/v2/posts?per_page=5&filter[meta_key]=wpe-fp-check&filter[meta_value]=yes
