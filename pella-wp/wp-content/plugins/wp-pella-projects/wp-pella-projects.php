<?php
/**
 * Plugin Name: Pella Nova Projects
 * Description: A custom plugin to manage and list Projects without using Elementor. Matches the Elina Szmrtyka demo layout.
 * Version: 2.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class PellaProjectsPlugin {
    
    public function __construct() {
        // Register Projects post type - only when plugin is active
        add_action('init', array($this, 'register_cpt'), 0);
        // Ensure Featured Image support is enabled for project post type (after registration)
        add_action('init', array($this, 'ensure_featured_image_support'), 20);
        
        add_action('add_meta_boxes', array($this, 'add_project_meta_boxes'));
        add_action('save_post', array($this, 'save_project_meta'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        
        // Fix REST API permissions for media uploads
        add_filter('rest_pre_insert_attachment', array($this, 'fix_rest_media_permissions'), 10, 2);
        
        // Flush rewrite rules on activation/deactivation
        register_activation_hook(__FILE__, array($this, 'flush_rewrite_rules'));
        register_deactivation_hook(__FILE__, array($this, 'flush_rewrite_rules'));
        
        // Also flush on plugin init to ensure rules are current (only once)
        add_action('init', array($this, 'maybe_flush_rewrite_rules'), 999);
        
        // Demo data seeding disabled - no demo packages will be created on activation
        // register_activation_hook(__FILE__, array($this, 'seed_demo_data'));

        // Shortcodes for different sections
        add_shortcode('pella_hero', array($this, 'render_hero_shortcode'));
        add_shortcode('pella_about', array($this, 'render_about_shortcode'));
        add_shortcode('pella_skills_grid', array($this, 'render_skills_grid_shortcode'));
        add_shortcode('pella_projects', array($this, 'render_projects_shortcode'));
        add_shortcode('pella_gallery', array($this, 'render_gallery_shortcode'));
    }
    
    public function ensure_featured_image_support() {
        // Ensure post thumbnails are supported globally
        if (!current_theme_supports('post-thumbnails')) {
            add_theme_support('post-thumbnails');
        }
        // Explicitly add thumbnail and editor support for project post type
        if (post_type_exists('project')) {
            add_post_type_support('project', 'thumbnail');
            add_post_type_support('project', 'editor');
        }
    }
    
    public function flush_rewrite_rules() {
        // Flush rewrite rules to ensure post type URLs work correctly
        flush_rewrite_rules();
    }
    
    public function fix_rest_media_permissions($prepared_post, $request) {
        // Allow media uploads for project post type
        if (isset($request['post']) && $request['post'] > 0) {
            $post = get_post($request['post']);
            if ($post && $post->post_type === 'project') {
                // Ensure user can edit the project post
                if (current_user_can('edit_post', $post->ID)) {
                    return $prepared_post;
                }
            }
        }
        return $prepared_post;
    }
    
    public function maybe_flush_rewrite_rules() {
        // Check if rewrite rules need to be flushed (only once per session)
        static $flushed = false;
        if ($flushed) {
            return;
        }
        
        $rules = get_option('rewrite_rules');
        $needs_flush = false;
        
        // Check if project post type rewrite rules exist
        if (!isset($rules['project/?$']) && !isset($rules['project/page/([0-9]{1,})/?$'])) {
            $needs_flush = true;
        }
        
        if ($needs_flush) {
            flush_rewrite_rules(false); // false = soft flush (faster)
            $flushed = true;
        }
    }

    public function register_cpt() {
        $labels = array(
            'name'                  => _x('Projects', 'Post Type General Name', 'pella'),
            'singular_name'         => _x('Project', 'Post Type Singular Name', 'pella'),
            'menu_name'             => __('Projects', 'pella'),
            'name_admin_bar'        => __('Project', 'pella'),
            'archives'              => __('Project Archives', 'pella'),
            'attributes'            => __('Project Attributes', 'pella'),
            'parent_item_colon'     => __('Parent Project:', 'pella'),
            'all_items'             => __('All Projects', 'pella'),
            'add_new_item'          => __('Add New Project', 'pella'),
            'add_new'               => __('Add New', 'pella'),
            'new_item'              => __('New Project', 'pella'),
            'edit_item'             => __('Edit Project', 'pella'),
            'update_item'           => __('Update Project', 'pella'),
            'view_item'             => __('View Project', 'pella'),
            'view_items'            => __('View Projects', 'pella'),
            'search_items'          => __('Search Project', 'pella'),
            'not_found'             => __('Not found', 'pella'),
            'not_found_in_trash'    => __('Not found in Trash', 'pella'),
            'featured_image'        => __('Featured Image', 'pella'),
            'set_featured_image'    => __('Set featured image', 'pella'),
            'remove_featured_image' => __('Remove featured image', 'pella'),
            'use_featured_image'    => __('Use as featured image', 'pella'),
            'insert_into_item'      => __('Insert into project', 'pella'),
            'uploaded_to_this_item' => __('Uploaded to this project', 'pella'),
            'items_list'            => __('Projects list', 'pella'),
            'items_list_navigation' => __('Projects list navigation', 'pella'),
            'filter_items_list'     => __('Filter projects list', 'pella'),
        );
        $args = array(
            'label'                 => __('Project', 'pella'),
            'description'           => __('Portfolio Projects', 'pella'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-portfolio',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => array(
                'slug'                  => 'project',
                'with_front'            => false,
                'pages'                 => true,
                'feeds'                 => true,
            ),
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
            'capabilities'          => array(
                'edit_post'          => 'edit_post',
                'read_post'          => 'read_post',
                'delete_post'        => 'delete_post',
                'edit_posts'         => 'edit_posts',
                'edit_others_posts'  => 'edit_others_posts',
                'publish_posts'      => 'publish_posts',
                'read_private_posts' => 'read_private_posts',
                'create_posts'       => 'edit_posts',
            ),
            'show_in_rest'          => true,
            'rest_base'             => 'projects',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
        register_post_type('project', $args);
    }

    public function add_project_meta_boxes() {
        // Use theme's 'project' post type instead of plugin's 'pella_project'
        add_meta_box('pella_project_details', 'Package Details & Basic Info', array($this, 'render_meta_box'), 'project', 'normal', 'default');
    }

    public function render_meta_box($post) {
        // Check if post object is valid
        if (!$post || !isset($post->ID)) {
            echo '<p>' . __('Error: Invalid post object.', 'pella') . '</p>';
            return;
        }
        
        wp_nonce_field('elina_save_project_meta_box_data', 'elina_project_meta_box_nonce');
        
        $short_description = get_post_meta($post->ID, '_project_short_description', true);
        $package_info = get_post_meta($post->ID, '_project_package_info', true);
        
        echo '<p><label for="project_short_description">';
        _e('Short Description', 'pella');
        echo '</label><br>';
        echo '<textarea id="project_short_description" name="project_short_description" rows="3" style="width:100%;">' . esc_textarea($short_description) . '</textarea></p>';
        
        echo '<p><label for="project_package_info">';
        _e('Package / Basic Info fields', 'pella');
        echo '</label><br>';
        echo '<input type="text" id="project_package_info" name="project_package_info" value="' . esc_attr($package_info) . '" style="width:100%;" /></p>';
        
        echo '<p style="margin-top: 20px; padding: 10px; background-color: #f0f0f1; border-left: 4px solid #2271b1;">';
        echo '<strong>' . __('Note:', 'pella') . '</strong> ';
        _e('To add a Featured Image, look for the "Featured Image" meta box on the right side of the screen. If you don\'t see it, click "Screen Options" at the top right and make sure "Featured Image" is checked.', 'pella');
        echo '</p>';
    }

    public function save_project_meta($post_id) {
        if (!isset($_POST['elina_project_meta_box_nonce']) || !wp_verify_nonce($_POST['elina_project_meta_box_nonce'], 'elina_save_project_meta_box_data')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        if (isset($_POST['project_short_description'])) {
            update_post_meta($post_id, '_project_short_description', sanitize_textarea_field($_POST['project_short_description']));
        }
        if (isset($_POST['project_package_info'])) {
            update_post_meta($post_id, '_project_package_info', sanitize_text_field($_POST['project_package_info']));
        }
    }

    public function enqueue_styles() {
        wp_enqueue_style('pella-nova-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap', array(), null);
        wp_enqueue_style('pella-projects-style', plugin_dir_url(__FILE__) . 'assets/style.css', array(), '2.0.0');
        wp_enqueue_script('pella-projects-script', plugin_dir_url(__FILE__) . 'assets/script.js', array(), '2.0.0', true);
    }

    public function seed_demo_data() {
        // Use theme's 'project' post type instead of plugin's 'pella_project'
        // 1. Create a sample Project/Package
        $project_check = new WP_Query(array(
            'post_type' => 'project',
            'posts_per_page' => 1
        ));
        
        if (!$project_check->have_posts()) {
            $post_id = wp_insert_post(array(
                'post_title'    => 'Strategic Consulting Masterclass',
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => 'project'
            ));
            
            if ($post_id) {
                update_post_meta($post_id, '_project_package_info', 'Platinum Strategy Package');
                update_post_meta($post_id, '_project_short_description', 'Comprehensive strategic alignment and decision framework design for executive teams.');
            }
        }

        // 2. Create the Demo Page with all shortcodes
        $page_check = get_page_by_title('Elina Demo Page');
        if (!isset($page_check->ID)) {
            $demo_content = '
                <!-- wp:shortcode -->
                [pella_hero name="ELINA SZMRTYKA" title="Managing Partner &amp; Visionary Idea Owner" nationality="Hungarian" image="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=800"]
                <!-- /wp:shortcode -->
                
                <!-- wp:shortcode -->
                [pella_about]
                <!-- /wp:shortcode -->
                
                <!-- wp:shortcode -->
                [pella_skills_grid]
                <!-- /wp:shortcode -->
                
                <!-- wp:shortcode -->
                [pella_projects]
                <!-- /wp:shortcode -->
                
                <!-- wp:shortcode -->
                [pella_gallery images="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&q=80&w=600, https://images.unsplash.com/photo-1557426272-fc759fdf7a8d?auto=format&fit=crop&q=80&w=600, https://images.unsplash.com/photo-1556761175-5973dc0f32d7?auto=format&fit=crop&q=80&w=600"]
                <!-- /wp:shortcode -->
            ';

            wp_insert_post(array(
                'post_title'    => 'Elina Demo Page',
                'post_content'  => $demo_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
            ));
        }
    }

    // 1. Hero Section Shortcode
    public function render_hero_shortcode($atts) {
        $atts = shortcode_atts(array(
            'image' => '',
            'name' => 'ELINA SZMRTYKA',
            'title' => 'Managing Partner & Visionary Idea Owner',
            'nationality' => 'Hungarian'
        ), $atts);

        ob_start(); ?>
        <div class="pella-hero-section">
            <div class="pella-hero-content">
                <p class="pella-hero-quote">Ideas gain value when they turn into experience. I focus on clarity, impact, and long term vision.</p>
                <h1 class="pella-hero-name"><?php echo esc_html($atts['name']); ?></h1>
            </div>
            <div class="pella-hero-image-wrapper">
                <?php if ($atts['image']): ?>
                    <img src="<?php echo esc_url($atts['image']); ?>" alt="<?php echo esc_attr($atts['name']); ?>" class="pella-hero-img" />
                <?php else: ?>
                    <div class="pella-hero-img-placeholder"></div>
                <?php endif; ?>
            </div>
            <div class="pella-hero-meta">
                <p class="pella-hero-title"><?php echo esc_html($atts['title']); ?></p>
                <p class="pella-hero-nationality">Nationality: <?php echo esc_html($atts['nationality']); ?></p>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    // 2. About Section Shortcode
    public function render_about_shortcode($atts) {
        ob_start(); ?>
        <div class="pella-about-section">
            <h2 class="pella-section-title">A B O U T</h2>
            <div class="pella-about-text">
                <p>Elina Szmrtyka is an international business strategist and Managing Partner known for her disciplined mindset, structured leadership, and long-term strategic vision. Born in Hungary and professionally shaped in Germany, she developed a strong foundation in analytical thinking, precision, and consistent execution - qualities that continue to define her professional approach.</p>
                <p>She began her professional journey at the age of sixteen, combining formal education with practical business experience. Through continuous learning and hands-on engagement, she built a leadership philosophy centered on clarity, accountability, and sustainable growth rather than short-term outcomes.</p>
                <p>Operating across international markets, Elina has developed a global perspective aligned with Dubai's dynamic business environment. Fluent in German, Hungarian, and English, she navigates multicultural ecosystems with cultural intelligence and strong communication capabilities. Her multilingual background strengthens her ability to evaluate complex structures with analytical precision and strategic awareness.</p>
            </div>
            
            <div class="pella-social-bar">
                <div class="pella-social-icons">
                    <a href="#" class="social-icon">in</a>
                    <a href="#" class="social-icon">ig</a>
                </div>
                <div class="pella-social-text">Connect with me</div>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    // 3. Skills & Info Grid Shortcode
    public function render_skills_grid_shortcode() {
        ob_start(); ?>
        <div class="pella-grid-container">
            <!-- Left Column -->
            <div class="pella-grid-col">
                <div class="pella-info-box">
                    <h3>Languages</h3>
                    <ul>
                        <li><strong>German</strong> - Fluent</li>
                        <li><strong>Hungarian</strong> - Native</li>
                        <li><strong>English</strong> - Professional Working Proficiency</li>
                    </ul>
                </div>
                <div class="pella-info-box">
                    <h3>Skills & Expertise</h3>
                    <ul>
                        <li>Strategic Direction & Leadership</li>
                        <li>Decision Framework Design</li>
                        <li>Digital Reporting Systems</li>
                        <li>International Business Strategy</li>
                        <li>Operational Alignment</li>
                        <li>Analytical Thinking & Structured Execution</li>
                        <li>Cross-Cultural Communication</li>
                    </ul>
                </div>
                <div class="pella-info-box">
                    <h3>Professional Philosophy</h3>
                    <ul>
                        <li>Strategic clarity over short-term visibility</li>
                        <li>Structured execution and disciplined growth</li>
                        <li>Cross-border collaboration and transparency</li>
                        <li>Long-term value creation through systems thinking</li>
                    </ul>
                </div>
            </div>

            <!-- Right Column -->
            <div class="pella-grid-col">
                <div class="pella-info-box">
                    <h3>Information</h3>
                    <ul>
                        <li><strong>Full Name:</strong> Elina Szmrtyka</li>
                        <li><strong>Nationality:</strong> Hungarian</li>
                        <li><strong>Profession:</strong> Managing Partner, Visionary / Idea Owner</li>
                        <li><strong>Known For:</strong> Strategic Leadership, Digital Strategy Systems</li>
                        <li><strong>Based In:</strong> Dubai, United Arab Emirates</li>
                    </ul>
                </div>
                <div class="pella-info-box">
                    <h3>Professional Timeline</h3>
                    <ul class="timeline-list">
                        <li><strong>Present</strong> - Managing Partner & Visionary / Idea Owner, Pella Force Ecosystem</li>
                        <li><strong>Ongoing</strong> - International Strategy & Business Development, Global Markets</li>
                        <li><strong>Early Career</strong> - Early Professional Experience & Structured Development, Germany & Europe</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="pella-what-i-do-section">
            <h2 class="pella-section-title">W H A T &nbsp;&nbsp;I&nbsp;&nbsp; D O</h2>
            <div class="pella-about-text">
                <p>As Managing Partner, Elina Szmrtyka oversees strategic direction, operational clarity, and the alignment between long-term vision and daily execution. She focuses on building structured systems, transparent decision frameworks, and scalable strategies that support sustainable growth.</p>
                <p>Her work centers on transforming complex business challenges into organized, measurable, and execution-driven strategies. By combining analytical structure with strategic foresight, she ensures that every initiative operates within a clear framework of responsibility and performance.</p>
            </div>
            
            <div class="pella-pill-container">
                <span class="pella-pill">Strategic Direction & Leadership</span>
                <span class="pella-pill">Decision Framework Design</span>
                <span class="pella-pill">Digital Reporting Systems</span>
                <span class="pella-pill">International Business Strategy</span>
                <span class="pella-pill">Operational Alignment</span>
                <span class="pella-pill">Analytical Thinking & Structured Execution</span>
                <span class="pella-pill">Cross-Cultural Communication</span>
            </div>
        </div>
        <?php return ob_get_clean();
    }

    // 4. Projects/Packages Shortcode
    public function render_projects_shortcode($atts) {
        ob_start();
        $query = new WP_Query(array('post_type' => 'project', 'posts_per_page' => -1));

        echo '<h2 class="pella-section-title" style="text-align:center; margin-top:60px;">C E R T I F I C A T I O N S &nbsp;&nbsp;&amp;&nbsp;&nbsp; P A C K A G E S</h2>';
        echo '<div class="pella-projects-wrapper">';
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $short_desc = get_post_meta($id, '_project_short_description', true);
                $package_info = get_post_meta($id, '_project_package_info', true);
                $img = get_the_post_thumbnail_url($id, 'large');
                
                // Fallback placeholder image for demo purposes if no thumbnail is set
                if (!$img) {
                    $img = 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=600';
                }

                echo '<div class="pella-project-item">';
                echo '<div class="pella-project-img" style="background-image:url(' . esc_url($img) . ')"></div>';
                
                echo '<div class="pella-project-info">';
                echo '<h4>' . get_the_title() . '</h4>';
                if ($package_info) echo '<p class="pkg-name"><strong>Package / Basic Info:</strong> ' . esc_html($package_info) . '</p>';
                if ($short_desc) echo '<p class="pkg-desc">' . esc_html($short_desc) . '</p>';
                echo '</div></div>';
            }
            wp_reset_postdata();
        } else {
            echo '<p style="text-align:center;">No packages found. Add them in the WordPress admin.</p>';
        }
        echo '</div>';
        return ob_get_clean();
    }

    // 5. Animated Gallery Shortcode
    public function render_gallery_shortcode($atts) {
        $atts = shortcode_atts(array(
            'images' => '' // Comma separated image URLs
        ), $atts);
        
        $images = array_filter(array_map('trim', explode(',', $atts['images'])));
        
        ob_start(); ?>
        <div class="pella-media-section">
            <h2 class="pella-section-title" style="text-align:center; margin-top:80px; margin-bottom: 40px;">V I S U A L &nbsp;&nbsp;&amp;&nbsp;&nbsp; M E D I A &nbsp;&nbsp; P R E S E N C E</h2>
            
            <div class="pella-marquee-container">
                <div class="pella-marquee-track">
                    <?php 
                    // Duplicate images to create infinite scroll effect
                    $display_images = empty($images) ? array_fill(0, 5, '') : array_merge($images, $images);
                    foreach($display_images as $img): 
                    ?>
                        <div class="pella-marquee-item">
                            <?php if ($img): ?>
                                <img src="<?php echo esc_url($img); ?>" alt="Media Presence" />
                            <?php else: ?>
                                <div class="pella-marquee-placeholder"></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="pella-video-grid">
                <div class="pella-video-card">
                    <div class="pella-video-placeholder">
                        <span class="play-btn">▶</span>
                    </div>
                </div>
                <div class="pella-video-card">
                    <div class="pella-video-placeholder">
                        <span class="play-btn">▶</span>
                    </div>
                </div>
            </div>
        </div>
        
        <footer class="pella-footer">
            <div class="footer-left">© 2026 Elina Szmrtyka</div>
            <div class="footer-center">Designed by PellaNova</div>
            <div class="footer-right">All rights reserved</div>
        </footer>
        <?php return ob_get_clean();
    }
}

new PellaProjectsPlugin();
