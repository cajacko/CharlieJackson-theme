<?php
/**
 * Charlie Jackson functions and definitions.
 *
 * @package Charlie Jackson
 */
	
/* -----------------------------
TERRY PRATCHETT HEADER
----------------------------- */
	/**
	 * Adds a memorial header for Terry Pratchett, 
	 * based off the code in the clacks referrenced 
	 * in the Discworld novel "Going Postal" by 
	 * Terry Pratchett.
	 */
	function add_header_clacks( $headers ) {
	    $headers['X-Clacks-Overhead'] = 'GNU Terry Pratchett'; //Add an array value to the headers variable
	    return $headers; //Return the headers
	}
	
	add_filter( 'wp_headers', 'add_header_clacks' );

/* -----------------------------
ADD/REMOVE THEME SUPPORT
----------------------------- */	
	function charliejackson_setup() {
		add_filter( 'show_admin_bar', '__return_false' ); // Always hide admin bar
		
		add_theme_support( 'post-thumbnails' );
		
		/*
		 * Add various image sizes so that images 
		 * can be progressively loaded at higher 
		 * resolutions.
		 */
		add_image_size( 'inline-image', 600, 3000, false );
		add_image_size( 'width-500', 500 );
		add_image_size( 'width-1000', 1000 );
		add_image_size( 'width-1500', 1500 );
		add_image_size( 'width-2000', 2000 );
		add_image_size( 'width-2500', 2500 );
		add_image_size( 'width-3000', 3000 );
		add_image_size( 'width-3500', 3500 );
		add_image_size( 'width-4000', 4000 );
		
		add_post_type_support( "page", "excerpt" );
		
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		
		add_theme_support( 'post-formats', array( 'image', 'video', 'gallery', 'link', 'aside', 'quote', 'status', 'audio', 'chat' ) );
	}
	
	add_action( 'after_setup_theme', 'charliejackson_setup' );

/* -----------------------------
ADD STYLES AND SCRIPTS
----------------------------- */
	function charliejackson_scripts() {
		/*
		 * Add the bootstrap stylesheet and JavaScript
		 */
		wp_enqueue_style( 'charliejackson-bootstrap-style',  get_template_directory_uri()  . '/inc/bootstrap/css/bootstrap.min.css' );
		wp_enqueue_script( 'charliejackson-bootstrap-script', get_template_directory_uri()  . '/inc/bootstrap/js/bootstrap.min.js', array( 'jquery' ) );
		
		/*
		 * Add the template.js file which provides global functions used by other JavaScript files.
		 */
		wp_enqueue_script( 'charliejackson-template-script', get_template_directory_uri()  . '/js/template.js', array( 'jquery' ) );
		
		/*
		 * Add the core setup.js file which is used on every page.
		 */
		wp_enqueue_script( 'charliejackson-setup-script', get_template_directory_uri()  . '/js/setup.js', array( 'jquery' ) );
		
		/*
		 * Add specific JavaScript for the banner element.
		 */
		if( is_front_page_showing() ) {
			wp_enqueue_script( 'charliejackson-banner-script', get_template_directory_uri()  . '/js/banner.js', array( 'jquery' ) );
		}
		
		/*
		 * Add JavaScript that acts on a loop of posts e.g. Infinite scroll
		 */
		if( !is_single() ) {
			wp_enqueue_script( 'charliejackson-not-single-script', get_template_directory_uri()  . '/js/not-single.js', array( 'jquery' ) );
		}
		
		/*
		 * Add specific JavaScript for the Portfolio page.
		 */
		if( is_page( 'portfolio' ) ) {
			wp_enqueue_script( 'charliejackson-portfolio-script', get_template_directory_uri()  . '/js/portfolio.js', array( 'jquery' ) );
		}
		
		/*
		 * Add specific JavaScript for the projects page otherwise include JavaScript for the sidebar on all other pages.
		 */
		if( is_page( 'projects' ) ) {
			wp_enqueue_script( 'charliejackson-projects-script', get_template_directory_uri()  . '/js/projects.js', array( 'jquery' ) );
			wp_enqueue_script( 'charliejackson-masonry-script', get_template_directory_uri()  . '/js/masonry.js', array( 'jquery' ) );
		} else {
			wp_enqueue_script( 'charliejackson-sidebar-script', get_template_directory_uri()  . '/js/sidebar.js', array( 'jquery' ) );
		}
	}
	
	add_action( 'wp_enqueue_scripts', 'charliejackson_scripts' );

/* -----------------------------
IS THE FRONT PAGE SHOWING
----------------------------- */
	function is_front_page_showing() {
		if( is_front_page() && !is_paged() ) {
			return true;
		} else {
			return false;
		}
	}

/* -----------------------------
GET THE SYNCED URL
----------------------------- */
	function charliejackson_cross_site_sync_url() {
		$url = get_post_meta( get_the_ID(), 'cross_site_sync_original_url', true );
		
		if( $url == '' ) {
			return false;
		} else {
			return $url;
		}
	}

/* -----------------------------
GET THE SYNCED FEATURED IMAGE
----------------------------- */
	function charliejackson_cross_site_sync_featured_image() {
		$url = get_post_meta( get_the_ID(), 'cross_site_sync_featured_image', true );
		
		if( $url == '' ) {
			return false;
		} else {
			return $url;
		}
	}

/* -----------------------------
THE POST TITLE AND LINK
----------------------------- */
	function charliejackson_the_title() {
		$cross_site_sync_url = charliejackson_cross_site_sync_url();
		
		if( $cross_site_sync_url ) {
			echo '<a href="' . $cross_site_sync_url . '" target="_blank">';
		} else {
			echo '<a href="' . get_permalink() . '">';
		}

		the_title();
		
		echo '</a>';
	}

/* -----------------------------
CROSS SITE SYNC MESSAGE
----------------------------- */
	function charliejackson_cross_site_sync_message() {	
		$cross_site_sync_url = charliejackson_cross_site_sync_url();
		
		if( $cross_site_sync_url ) {

			echo '<small><a target="_blank" href="' . $cross_site_sync_url . '">Originally published on ';
				
			$website = get_post_meta( get_the_ID(), 'cross_site_sync_home_url', true );
				
			echo str_replace( 'http://', '', $website );
				 
			echo '</a></small>';
			
		}
	}
	
/* -----------------------------
FILTER OEMBED OUTPUT
----------------------------- */	
	function charliejackson_filter_oembed( $html, $url, $attr, $post_id ) {
		/**
		 * If the embed is a youTube video then 
		 * return the embed within a fixed aspect 
		 * ratio div
		 */
		if ( strpos( $html, 'youtube.com' ) !== false ) {
			$html = preg_replace( '/(width=").+?(")/', '', $html ); // Remove the width attribute
			$html = preg_replace( '/(height=").+?(")/', '', $html ); // Remove the height attribute
			$html = str_replace( 'iframe', 'iframe class="embed-responsive-item"', $html ); // Add a responsive class to the iframe
			$html = '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
		}
		
		return $html;
	}
	
	add_filter( 'embed_oembed_html', 'charliejackson_filter_oembed', 99, 4 );
	
/* -----------------------------
REGISTER PAGE CATEGORIES	
----------------------------- */	
	function charliejackson_page_cat() {
		register_taxonomy(
			'project-categories',
			'page',
			array(
				'label' => __( 'Project Categories' ),
				'rewrite' => array( 'slug' => 'topic' ),
				'hierarchical' => true,
			)
		);
	}
	
	add_action( 'init', 'charliejackson_page_cat' );
	
/* -----------------------------
REGISTER IMAGE SIZE SELECTIONS	
----------------------------- */
	function charliejackson_new_image_sizes( $sizes ) {
		return array_merge( $sizes, array(
	        'inline-image' => __( 'Inline Image' ),
	        'width-500' => __( 'Width 500' ),
	        'width-1000' => __( 'Width 1000' ),
	        'width-1500' => __( 'Width 1500' ),
	        'width-2000' => __( 'Width 2000' ),
	        'width-2500' => __( 'Width 2500' ),
	        'width-3000' => __( 'Width 3000' ),
	        'width-3500' => __( 'Width 3500' ),
	        'width-4000' => __( 'Width 4000' )
	    ) );
	}
	
	add_filter( 'image_size_names_choose', 'charliejackson_new_image_sizes' );

/* -----------------------------
INCREASE MAX UPLOAD SIZE	
----------------------------- */
	@ini_set( ‘upload_max_size’ , ‘10G’);
	@ini_set( ‘post_max_size’, ‘10G’);	
	@ini_set( ‘max_execution_time’, ‘300’);

/* -----------------------------
DISPLAY PORTFOLIO META BOXES	
----------------------------- */
	/**
	 * Add a 'Portfolio Content' meta box to the edit/add page acreen 
	 */
	function charliejackson_add_custom_box() {
		if( $_GET['post_type'] == 'page' || get_post_type( $_GET['post'] ) == 'page' ) {
			add_meta_box( 'portfolio-meta-box', 'Portfolio Content', 'wp_editor_meta_box' );
		}
	}
	
	/**
	 * Show the existing 'Portfolio Content' in the meta box
	 */
	function wp_editor_meta_box( $post ) {				
		wp_nonce_field( plugin_basename( __FILE__ ), 'charliejackson_noncename' ); // Use nonce for verification
		
		$field_value = get_post_meta( $post->ID, 'portfolio_content', false ); 
		wp_editor( $field_value[0], 'portfolio_content' );
	}
	
	add_action( 'add_meta_boxes', 'charliejackson_add_custom_box' );
	
	
	/**
	 * When the post is saved, also save the 'Portfolio Content'
	 */
	function charliejackson_save_postdata( $post_id ) {
		/**
		 * Don't save the 'Portfolio Content' on an autosave
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		/**
		 * Verify that the save request came from the correct screen 
		 * and with the proper authorisation. As save_post can be 
		 * triggered at other times.
		 */
		if ( ( isset ( $_POST['charliejackson_noncename'] ) ) && ( !wp_verify_nonce( $_POST['charliejackson_noncename'], plugin_basename( __FILE__ ) ) ) ) {
			return;
		}
		
		/**
		 * Check that the user can edit pages
		 */
		if ( ( isset ( $_POST['post_type'] ) ) && ( 'page' == $_POST['post_type'] )  ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return;
			}    
		} else {
			if ( !current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		
		/**
		 * Everything is authenticated, so save the 'Portfolio Content'
		 */
		if ( isset ( $_POST['portfolio_content'] ) ) {
			update_post_meta( $post_id, 'portfolio_content', $_POST['portfolio_content'] );
		}		
	}
	
	add_action( 'save_post', 'charliejackson_save_postdata' );

/* -----------------------------
DISPLAY PORTFOLIO IMAGE 
----------------------------- */
	if ( class_exists( 'MultiPostThumbnails' ) ) {
	    new MultiPostThumbnails(
	        array(
	            'label' => 'Portfolio Image',
	            'id' => 'portfolio-image',
	            'post_type' => 'page'
	        )
	    );
	}

/* -----------------------------
EDIT THE MORE TEXT TAG
----------------------------- */		
	function charliejackson_read_more_link() {
		return '<a class="more-link" href="' . get_permalink() . '">Read more...</a>';
	}
	
	add_filter( 'the_content_more_link', 'charliejackson_read_more_link' );
	
/* -----------------------------
FILTER MEDIA BY QUERY
----------------------------- */	
	/**
	 * Show sketchwork which is not in Pinterest
	 */
	function charliejackson_filter_media_by_query( $query ) {		
		if( $_GET['pinterest-media'] == 'pinterest-sketchbook' ) {
	        $query->set( 'tax_query', 
	        	array(
	        		'relation' => 'AND',
					array(
						'taxonomy' => 'attachment_category',
						'field'    => 'slug',
						'terms'    => 'sketchbook',
					),
					array(
						'taxonomy' => 'attachment_category',
						'field'    => 'slug',
						'terms'    => 'in-pinterest',
						'operator' => 'NOT IN',
					),
				) 
			);
		}    
	}
	
	add_action( 'pre_get_posts', 'charliejackson_filter_media_by_query' );
		
/* -----------------------------
ADD PINTEREST MENU ITEM
----------------------------- */
	function charliejackson_add_sketch_pinterest_menu( $wp_admin_bar ) {
		$args = array(
			'id'    => 'sketch_pinterest',
			'title' => 'Sketchbook not in Pinterest',
			'href'  => esc_url( home_url() ) . '/wp-admin/upload.php?page=mla-menu&pinterest-media=pinterest-sketchbook',
			'meta'  => array( 'class' => 'my-toolbar-page' )
		);
		
		$wp_admin_bar->add_node( $args );
	}
	
	add_action( 'admin_bar_menu', 'charliejackson_add_sketch_pinterest_menu', 999 );

/* -----------------------------
EDIT ATTACHMENT CAPTION HTML
----------------------------- */
	function charliejackson_attachment_caption_html( $current_html, $attr, $content ) {
	    /**
		 * Get the attributes of the attachment as variables
		 */
	    extract( shortcode_atts( array(
	        'id'    => '',
	        'align' => 'alignnone',
	        'width' => '',
	        'caption' => ''
	    ), $attr ) );
	    
	    //Do nothing if the image doesn't have a caption or the image is broken
	    if ( 1 > ( int ) $width || empty( $caption ) ) {
	        return $content;
	    }
	
	    return '<div class="caption">' . do_shortcode( $content ) . '<p class="caption-text">' . $caption . '</p></div>';
	}
	
	add_filter( 'img_caption_shortcode', 'charliejackson_attachment_caption_html', 10, 3 );

/* -----------------------------
GET THE CONTENT WITH FORMATTING	
----------------------------- */	
	function charliejackson_get_the_content_with_formatting( $more_link_text = '(more...)', $stripteaser = 0, $more_file = '' ) {
		$content = get_the_content( $more_link_text, $stripteaser, $more_file );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content ); // Can't remember what this was for, but I must have added it for a reason?...
		$content = str_replace( '<p>&nbsp;</p>', '', $content ); // Remove empty paragraphs
		return $content;
	}

/* -----------------------------
DISPLAY THE SITE NAV CLASSES	
----------------------------- */
	function charliejackson_the_site_nav_classes() {
		if( is_front_page_showing() ) {
			echo 'absolute-nav'; 
		} else { 
			echo 'fixed-nav';
		}
	}

/* -----------------------------
DISPLAY THE MAIN ID	
----------------------------- */
	function charliejackson_the_main_id() {
		if( is_page( 'projects' ) ) {
			echo ' id="page-projects"';
		}
	}
	
/* -----------------------------
DISPLAY THE PORTFOLIO AFFIX NAV CLASSES	
----------------------------- */
	function charliejackson_portfolio_nav_class( $nav_item ) {
		if( $_GET['portfolio'] == $nav_item || ( $_GET['portfolio'] != 'web' && $_GET['portfolio'] != 'design' && $nav_item == 'all' ) ) { 
			echo ' active-portfolio'; 
		}
	}

/* -----------------------------
DISPLAY THE PORTFOLIO NAV CLASSES
----------------------------- */
	function charliejackson_portfolio_affix_nav_classes( $count ) {
		if( $count == 0 ) { 
			echo ' class="active-portfolio-item"'; 
		}
	}

/* -----------------------------
DISPLAY THE QUERY TITLE	
----------------------------- */	
	function charliejackson_the_query_title() {
		if( is_category() || is_tag() ) {
			$string = '';
			
			if( is_category() ) {
				$string .= 'Category: ';
			} elseif( is_tag() ) {
				$string .= 'Tag: ';
			}
			
			$string .= single_cat_title( '', false );
			
			if( is_paged() ) {
				$string .= ' <small>- Page: '.get_query_var( 'paged' ).'</small>';
			}

			echo '<div id="post-query-title" class="wrap"><h1>'.$string.'</h1></div>';
		}
	}
		
/* -----------------------------
POST FORMAT FUNCTION
----------------------------- */
	/**
	 * Get the video from the content and display it
	 */
	function charliejackson_the_video() {
		preg_match ( "/<div class=\"embed-responsive embed-responsive-16by9\"><iframe.*<\/div>/" , charliejackson_get_the_content_with_formatting(), $match );
		
		echo $match[0];
	}
	
	/**
	 * Remove the video from the content and then display the content
	 */
	function charliejackson_the_video_content() {
		$content = preg_replace ( "/<p><div class=\"embed-responsive embed-responsive-16by9\"><iframe.*<\/div><\/p>/" , "" , charliejackson_get_the_content_with_formatting() );
		$content = preg_replace ( "/<div class=\"embed-responsive embed-responsive-16by9\"><iframe.*<\/div>/" , "" , $content );
		echo $content;	
	}
	
	/**
	 * Get the gallery from the content and display it
	 */
	function charliejackson_the_gallery() {
		preg_match ( '/<div class=\"gallery.*?<\/figure><\/div>/' , charliejackson_get_the_content_with_formatting(), $match );
		
		echo $match[0];
	}
	
	/**
	 * Remove the gallery from the content and then display the content
	 */
	function charliejackson_the_gallery_content() {
		echo preg_replace ( '/<div class=\"gallery.*?<\/figure><\/div>/' , "" , charliejackson_get_the_content_with_formatting() );
	}

/* -----------------------------
FILTER THE GALLERY
----------------------------- */
	/**
	 * Output the gallery contents
	 */	
	function charliejackson_gallery( $output, $attr ) {
	    global $post;
	
	    if( isset( $attr[ 'orderby' ] ) ) {
	        $attr[ 'orderby' ] = sanitize_sql_orderby( $attr[ 'orderby' ] );
	        
	        if( !$attr[ 'orderby' ] ) {
	            unset( $attr[ 'orderby' ] );
	        }
	    }
	
	    extract( shortcode_atts( array(
	        'order'      => 'ASC',
	        'orderby'    => 'menu_order ID',
	        'id'         => $post->ID,
	        'itemtag'    => 'dl',
	        'icontag'    => 'dt',
	        'captiontag' => 'dd',
	        'columns'    => 3,
	        'size'       => 'thumbnail',
	        'include'    => '',
	        'exclude'    => '',
	    ), $attr ) );
	
	    $id = intval( $id );
	    
	    if( 'RAND' == $order ) { 
		    $orderby = 'none';
		}
	
	    if( !empty( $include ) ) {
	        $include = preg_replace( '/[^0-9,]+/', '', $include );
	        
	        $_attachments = get_posts( array( 
	        	'include'        => $include, 
	        	'post_status'    => 'inherit', 
	        	'post_type'      => 'attachment', 
	        	'post_mime_type' => 'image', 
	        	'order'          => $order, 
	        	'orderby'        => $orderby,
	        ) );
	
	        $attachments = array();
	        
	        foreach ( $_attachments as $key => $val ) {
	            $attachments[ $val->ID ] = $_attachments[ $key ];
	        }
	    }
	
	    if( empty( $attachments ) ) { 
		    return '';
		}

	    $output = "<div class=\"gallery clearfix\">";

	    foreach ( $attachments as $id => $attachment ) {
	        $img = wp_get_attachment_image_src( $id, 'thumbnail' );
	
	        $output .= "<figure>";
	        $output .= "<img src=\"{$img[0]}\" width=\"{$img[1]}\" height=\"{$img[2]}\" alt=\"\" />";
	        $output .= "</figure>";
	    }
	
	    $output .= "</div>";
	
	    return $output;
	}
	
	add_filter( 'post_gallery', 'charliejackson_gallery', 10, 2 );
		
/* -----------------------------
DISPLAY THE PAGINATION
----------------------------- */
	/**
	 * Use the Bootstrap pagination, modified from the standard WordPress pagination
	 */
	function charliejackson_pagination( $args = array() ) {	    
	    $defaults = array(
	        'range'           => 4,
	        'custom_query'    => FALSE,
	        'previous_string' => __( '<i class="fa fa-chevron-left"></i>', 'text-domain' ),
	        'next_string'     => __( '<i class="fa fa-chevron-right"></i>', 'text-domain' ),
	        'before_output'   => '<div id="page-nav" class="text-center"><ul class="pagination">',
	        'after_output'    => '</ul></div>'
	    );
	    
	    $args = wp_parse_args( 
	        $args, 
	        apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
	    );
	    
	    $args['range'] = (int) $args['range'] - 1;
	    
	    if ( !$args['custom_query'] ) {
	        $args['custom_query'] = @$GLOBALS['wp_query'];
	    }
	    
	    $count = ( int ) $args['custom_query']->max_num_pages;
	    $page  = intval( get_query_var( 'paged' ) );
	    $ceil  = ceil( $args['range'] / 2 );
	    
	    if ( $count <= 1 ) {
	        return FALSE;
	    }
	    
	    if ( !$page ) {
	        $page = 1;
	    }
	    
	    if ( $count > $args['range'] ) {
	        if ( $page <= $args['range'] ) {
	            $min = 1;
	            $max = $args['range'] + 1;
	        } elseif ( $page >= ( $count - $ceil ) ) {
	            $min = $count - $args['range'];
	            $max = $count;
	        } elseif ( $page >= $args['range'] && $page < ( $count - $ceil ) ) {
	            $min = $page - $ceil;
	            $max = $page + $ceil;
	        }
	    } else {
	        $min = 1;
	        $max = $count;
	    }
	    
	    $echo = '';
	    $previous = intval( $page ) - 1;
	    $previous = esc_attr( get_pagenum_link( $previous ) );	    
	    $firstpage = esc_attr( get_pagenum_link( 1 ) );
	    
	    if ( $firstpage && (1 != $page) ) {
	        $echo .= '<li class="previous"><a href="' . $firstpage . '">' . __( 'First', 'text-domain' ) . '</a></li>';
	    }
	
	    if ( $previous && ( 1 != $page ) ) {
	        $echo .= '<li><a href="' . $previous . '" title="' . __( 'previous', 'text-domain') . '">' . $args['previous_string'] . '</a></li>';
	    }
	    
	    if ( !empty( $min ) && !empty( $max ) ) {
	        for( $i = $min; $i <= $max; $i++ ) {
	            if( $page == $i ) {
	                $echo .= '<li class="active"><span class="active">' . str_pad( ( int ) $i, 2, '0', STR_PAD_LEFT ) . '</span></li>';
	            } else {
	                $echo .= sprintf( '<li><a href="%s">%002d</a></li>', esc_attr( get_pagenum_link( $i ) ), $i );
	            }
	        }
	    }
	    
	    $next = intval( $page ) + 1;
	    $next = esc_attr( get_pagenum_link( $next ) );
	    if ( $next && ( $count != $page ) ) {
	        $echo .= '<li><a href="' . $next . '" title="' . __( 'next', 'text-domain') . '">' . $args['next_string'] . '</a></li>';
	    }
	    
	    $lastpage = esc_attr( get_pagenum_link( $count ) );
	    if ( $lastpage ) {
	        $echo .= '<li class="next"><a href="' . $lastpage . '">' . __( 'Last', 'text-domain' ) . '</a></li>';
	    }
	
	    if ( isset($echo) ) {
	        echo $args['before_output'] . $echo . $args['after_output'];
	    }
	}