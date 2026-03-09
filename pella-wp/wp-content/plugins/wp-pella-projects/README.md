# Pella Nova Projects WordPress Plugin

This is a custom plugin to manage and list Projects, mirroring the Pella Nova demo site without using Elementor.

## Features
- Registers a Custom Post Type "Projects" (`pella_project`).
- Adds Custom Meta Boxes for "Package Name", "Package Price", and "Basic Information".
- Provides a shortcode `[pella_projects]` to display the grid of projects on any page.
- Includes a template override (`archive-pella_project.php`) for viewing the projects archive natively.

## Installation
1. Move the `wp-pella-projects` folder into your WordPress installation's `wp-content/plugins/` directory.
2. Go to the WordPress Admin Panel -> Plugins.
3. Activate the **Pella Nova Projects** plugin.
4. You will see a new "Projects" menu in the sidebar. You can start adding projects and filling out their basic package info.

## Usage
- **Shortcode:** Create a new Page (e.g., "Our Projects") and simply add the shortcode `[pella_projects]` inside the default block editor.
- **Archive:** Alternatively, go to Settings -> Permalinks, click Save Changes (to flush rewrite rules), and visit `yourwebsite.com/pella_project/` to view the archive template provided by the plugin.
