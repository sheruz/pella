# Elina Szmrtyka WordPress Theme

A custom WordPress theme and plugin package for Elina Szmrtyka's professional portfolio website. This project provides a complete solution without using Elementor or Hello Elementor theme, matching the original demo design exactly.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Theme Structure](#theme-structure)
- [Plugin Features](#plugin-features)
- [Custom Post Type: Projects](#custom-post-type-projects)
- [Page Templates](#page-templates)
- [Styling & Design](#styling--design)
- [Configuration](#configuration)
- [Usage](#usage)
- [Troubleshooting](#troubleshooting)
- [Development](#development)

## 🎯 Overview

This WordPress theme package recreates the exact design and functionality of the Elina Szmrtyka demo site. It includes:

- **Custom WordPress Theme**: `elina-theme` - A fully custom theme matching the demo design
- **Custom Plugin**: `wp-pella-projects` - Manages Projects/Packages custom post type and functionality

**Key Requirements Met:**
- ✅ No Elementor or Hello Elementor theme used
- ✅ Custom project list page that displays projects from WordPress admin
- ✅ Projects managed through dedicated WordPress admin panel
- ✅ Exact design match with typography, spacing, and visual hierarchy
- ✅ Dynamic content management from WordPress dashboard

## ✨ Features

### Theme Features

- **Hero Section**: Dynamic background text effect with layered image and text
- **About Section**: Single-column, left-aligned content with social bar
- **Info Grid**: Two-column grid with styled boxes and bullet points
- **Certifications Section**: Image grid with hover effects and scroll animations
- **Visual & Media Presence**: Image gallery and video modal functionality
- **Responsive Design**: Fully responsive across all devices
- **Custom Typography**: Glancyr font family integration
- **Scroll Animations**: Fade-in animations on scroll for various sections

### Plugin Features

- **Custom Post Type**: "Projects" (packages) with full admin interface
- **Custom Fields**: Short Description (rich text editor) and Package/Basic Info
- **Featured Image Support**: Full thumbnail support for projects
- **REST API Support**: Enabled for media uploads and Gutenberg editor
- **Archive Pages**: Automatic archive page generation
- **Single Project Pages**: Individual project detail pages

## 📦 Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher
- **Web Server**: Apache or Nginx
- **Browser Support**: Modern browsers (Chrome, Firefox, Safari, Edge)

## 🚀 Installation

### Step 1: Download/Clone Repository

```bash
git clone [repository-url]
cd pella-wp
```

### Step 2: Install WordPress

1. Download WordPress from [wordpress.org](https://wordpress.org/download/)
2. Extract WordPress files to your web server directory
3. Copy theme and plugin files to WordPress installation:

```bash
# Copy theme
cp -r wp-content/themes/elina-theme /path/to/wordpress/wp-content/themes/

# Copy plugin
cp -r wp-content/plugins/wp-pella-projects /path/to/wordpress/wp-content/plugins/
```

### Step 3: Configure WordPress

1. Create a MySQL database for WordPress
2. Edit `wp-config.php` with your database credentials:
   ```php
   define( 'DB_NAME', 'your_database_name' );
   define( 'DB_USER', 'your_database_user' );
   define( 'DB_PASSWORD', 'your_database_password' );
   define( 'DB_HOST', 'localhost' );
   ```

### Step 4: Activate Theme and Plugin

1. Log in to WordPress Admin Dashboard
2. Go to **Appearance → Themes**
3. Activate **"Elina Theme"**
4. Go to **Plugins → Installed Plugins**
5. Activate **"Pella Nova Projects"**

### Step 5: Flush Permalinks

1. Go to **Settings → Permalinks**
2. Click **"Save Changes"** (without making any changes)
3. This ensures custom post type URLs work correctly

## 📁 Theme Structure

```
wp-content/themes/elina-theme/
├── style.css                 # Main stylesheet with all custom styles
├── functions.php             # Theme functions and setup
├── header.php                # Site header with navigation
├── footer.php                # Site footer with scripts
├── front-page.php            # Homepage template
├── index.php                 # Default template
├── page-projects.php         # Projects list page template
├── archive-project.php       # Project archive page template
└── single-project.php        # Single project detail page template
```

## 🔌 Plugin Structure

```
wp-content/plugins/wp-pella-projects/
└── wp-pella-projects.php     # Main plugin file with all functionality
```

## 📝 Custom Post Type: Projects

The plugin registers a custom post type called **"Projects"** with the following features:

### Admin Interface

- **Location**: WordPress Admin → Projects
- **Fields Available**:
  - **Title**: Project/Package name
  - **Featured Image**: Thumbnail image for the project
  - **Short Description**: Rich text editor (WYSIWYG) with media upload support
  - **Package / Basic Info**: Text field for package information

### Adding a New Project

1. Go to **Projects → Add New**
2. Enter the project title
3. Set a **Featured Image** (use the Featured Image meta box on the right)
4. Add **Short Description** using the rich text editor
5. Enter **Package / Basic Info** in the custom field
6. Click **Publish**

### Project Display

- **Archive Page**: `http://yoursite.com/project/` - Shows all projects in a grid
- **Single Project**: `http://yoursite.com/project/project-name/` - Individual project details
- **Custom Page**: Create a page and select "Projects List" template

## 🎨 Page Templates

### Front Page (`front-page.php`)

The homepage includes:
- Hero section with dynamic background text
- About section
- Social bar
- Info grid (Languages, Skills, Professional Timeline)
- What I Do section
- Certifications section
- Visual & Media Presence section with videos

### Projects List Page (`page-projects.php`)

**Template Name**: "Projects List"

1. Create a new page in WordPress
2. Select **Template: Projects List** from Page Attributes
3. Publish the page
4. Projects will automatically display in a grid format

### Archive Page (`archive-project.php`)

Automatically displays all published projects at `/project/` URL.

### Single Project Page (`single-project.php`)

Displays individual project details when clicking on a project from the grid.

## 🎨 Styling & Design

### Color Scheme

- **Primary**: Black (#000000)
- **Secondary**: Dark Gray (#54595F)
- **Text**: Light Gray (#D9D9D9)
- **Background**: White (#FFFFFF)

### Typography

- **Font Family**: Glancyr (custom font)
- **Fallback**: Montserrat, Sans-serif
- **Font Weights**: 400 (Regular), 500 (Medium), 600 (SemiBold)

### CSS Custom Properties

The theme uses CSS variables for easy customization:

```css
:root {
    --e-global-color-primary: #000000;
    --e-global-color-secondary: #54595F;
    --e-global-color-text: #D9D9D9;
    --e-global-typography-primary-font-family: "Glancyr", Sans-serif;
    --e-global-typography-primary-font-weight: 600;
}
```

### Key Sections Styling

- **Hero Section**: Full-width with layered text and image effects
- **Certifications Grid**: Flexbox layout with hover overlays
- **Info Grid**: CSS Grid with 2 columns, rounded borders
- **Media Section**: Grid layout with scroll animations

## ⚙️ Configuration

### Header Navigation

The header automatically shows the "Projects" link when the plugin is active. Edit `header.php` to customize navigation.

### Footer

The footer includes scroll animation scripts and video modal functionality. Edit `footer.php` to customize.

### Permalinks

Ensure permalinks are set to "Post name" or "Custom Structure" for clean URLs:
- Go to **Settings → Permalinks**
- Select **Post name** or set custom structure: `/%postname%/`

## 📖 Usage

### Adding Projects/Packages

1. Navigate to **Projects → Add New**
2. Fill in all required fields
3. Set a featured image (recommended: 400px minimum width)
4. Publish the project
5. The project will automatically appear on:
   - Archive page (`/project/`)
   - Projects List page (if using the template)
   - Any shortcode output

### Managing Projects

- **Edit**: Go to **Projects → All Projects** and click "Edit" on any project
- **Delete**: Move to Trash from the projects list
- **View**: Click "View" to see the project on the frontend

### Customizing Content

- **Homepage**: Edit `front-page.php` to modify homepage content
- **Styling**: Edit `style.css` to customize appearance
- **Navigation**: Edit `header.php` to modify menu items

## 🔧 Troubleshooting

### Projects Not Showing

1. **Check Plugin Status**: Ensure "Pella Nova Projects" is activated
2. **Check Post Status**: Projects must be "Published" (not Draft)
3. **Flush Permalinks**: Go to Settings → Permalinks → Save Changes
4. **Check Featured Images**: Projects should have featured images set

### 404 Errors on Project Pages

1. Go to **Settings → Permalinks**
2. Click **"Save Changes"** to regenerate rewrite rules
3. If still not working, deactivate and reactivate the plugin

### Media Upload Errors

1. Ensure the plugin is activated
2. Check file permissions on `wp-content/uploads/`
3. Verify PHP upload limits in `php.ini`

### Images Not Displaying

1. Check that featured images are set for projects
2. Verify image file permissions
3. Check browser console for 404 errors on image URLs

## 🛠️ Development

### Local Development Setup

1. Install XAMPP, WAMP, or MAMP
2. Create a local WordPress installation
3. Copy theme and plugin files
4. Activate theme and plugin
5. Start developing!

### File Modifications

**Theme Files**:
- `style.css`: All styling and CSS
- `front-page.php`: Homepage structure
- `functions.php`: Theme setup and support

**Plugin Files**:
- `wp-pella-projects.php`: All plugin functionality

### Customization Tips

- **Colors**: Modify CSS variables in `style.css`
- **Layout**: Edit template files (`.php` files)
- **Functionality**: Modify plugin file for custom post type changes

## 📄 License

This project is proprietary and created for Elina Szmrtyka / PellaNova. All rights reserved.

## 👥 Credits

- **Client**: Elina Szmrtyka
- **Design**: PellaNova
- **Development**: Custom WordPress Theme & Plugin

## 📞 Support

For issues or questions:
1. Check the Troubleshooting section above
2. Review WordPress error logs
3. Contact the development team

---

**Version**: 1.0.0  
**Last Updated**: 2026  
**WordPress Compatibility**: 5.0+  
**PHP Compatibility**: 7.4+
