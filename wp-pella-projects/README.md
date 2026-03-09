# Pella Nova Projects - WordPress Plugin

A custom WordPress plugin that recreates the **Elina Szmrtyka / Pella Nova** demo site design without using Elementor or Hello Elementor themes. Features a modular shortcode system, animated image galleries, and a dedicated admin panel for managing portfolio projects/packages.

## 🚀 Features

- ✅ **Custom Post Type** - "Portfolio Items" for managing projects/packages
- ✅ **Custom Meta Boxes** - Package name, price, and basic information fields
- ✅ **Modular Shortcodes** - Build the entire page layout using shortcodes
- ✅ **Animated Image Gallery** - CSS-only infinite scrolling marquee
- ✅ **Auto-Seeding** - Automatically creates demo content on activation
- ✅ **No Elementor Required** - Pure WordPress, no page builder dependencies
- ✅ **Responsive Design** - Mobile-friendly layouts

## 📋 Prerequisites

- WordPress >= 5.0
- PHP >= 7.4
- Apache/Nginx web server

## 🔧 Installation

### Step 1: Upload Plugin to WordPress

Copy the entire `wp-pella-projects` folder to your WordPress plugins directory:

```
wp-content/plugins/wp-pella-projects/
```

### Step 2: Activate the Plugin

1. Log into your WordPress Admin Dashboard
2. Navigate to **Plugins** → **Installed Plugins**
3. Find **"Pella Nova Projects"** in the list
4. Click **Activate**

### Step 3: View the Auto-Generated Demo Page

Upon activation, the plugin automatically:
- Creates a sample Portfolio Item (project/package)
- Creates a demo page titled **"Elina Demo Page"** with all shortcodes pre-configured

Navigate to **Pages** → **All Pages** and click **View** on "Elina Demo Page" to see the complete design!

## 📝 Available Shortcodes

### 1. Hero Section

Displays the main hero section with name, title, and portrait image.

```
[pella_hero name="ELINA SZMRTYKA" title="Managing Partner & Visionary Idea Owner" nationality="Hungarian" image="https://your-image-url.jpg"]
```

**Parameters:**
- `name` - Full name (default: "ELINA SZMRTYKA")
- `title` - Professional title
- `nationality` - Nationality text
- `image` - URL to portrait image

### 2. About Section

Displays the "ABOUT" section with biography text and social links.

```
[pella_about]
```

No parameters required.

### 3. Skills & Info Grid

Displays the two-column grid with Languages, Skills, Information, Timeline, and Philosophy boxes, plus the "WHAT I DO" section.

```
[pella_skills_grid]
```

No parameters required.

### 4. Projects/Packages Display

Lists all Portfolio Items (projects) from the WordPress admin in a beautiful grid layout.

```
[pella_projects]
```

No parameters required. Automatically pulls from the Custom Post Type.

### 5. Animated Gallery

Displays an infinitely scrolling horizontal image gallery and video placeholders.

```
[pella_gallery images="url1.jpg,url2.jpg,url3.jpg,url4.jpg"]
```

**Parameters:**
- `images` - Comma-separated list of image URLs

## 🎨 Building a Complete Page

To recreate the full Elina Szmrtyka demo page, create a new WordPress Page and add these shortcodes in order:

```
[pella_hero name="ELINA SZMRTYKA" title="Managing Partner & Visionary Idea Owner" nationality="Hungarian" image="https://your-image.jpg"]

[pella_about]

[pella_skills_grid]

[pella_projects]

[pella_gallery images="https://image1.jpg,https://image2.jpg,https://image3.jpg"]
```

## 📦 Managing Portfolio Items

### Adding a New Project/Package

1. Go to **Portfolio** → **Add New** in WordPress admin
2. Enter a **Title** (e.g., "Strategic Consulting Masterclass")
3. Upload a **Featured Image** (recommended size: 600x400px)
4. Scroll down to the **"Package Details & Basic Info"** meta box:
   - **Package Name**: e.g., "Platinum Strategy Package"
   - **Package Price**: e.g., "$2,500.00"
   - **Basic Information**: Description of the package
5. Click **Publish**

The new item will automatically appear in the `[pella_projects]` shortcode output!

### Editing Existing Items

1. Go to **Portfolio** → **All Items**
2. Click **Edit** on any item
3. Update the fields and click **Update**

## 🎯 Customization

### Styling

All styles are in `assets/style.css`. The plugin uses:
- **Montserrat** font from Google Fonts
- Custom CSS grid layouts
- CSS animations for the marquee gallery

### Modifying Content

Edit the shortcode functions in `wp-pella-projects.php` to customize:
- Hero section text
- About section paragraphs
- Skills grid items
- Default gallery images

## 🔄 Database Structure

The plugin creates:
- **Custom Post Type**: `pella_project`
- **Post Meta Fields**:
  - `_pella_package_name`
  - `_pella_package_price`
  - `_pella_basic_info`

## 🛠️ Development

### File Structure

```
wp-pella-projects/
├── wp-pella-projects.php    # Main plugin file
├── assets/
│   ├── style.css            # All CSS styles
│   └── script.js            # JavaScript (minimal)
└── README.md                # This file
```

### Hooks Used

- `init` - Register Custom Post Type
- `add_meta_boxes` - Add custom meta boxes
- `save_post` - Save meta box data
- `wp_enqueue_scripts` - Enqueue styles and scripts
- `register_activation_hook` - Seed demo data on activation

## 🐛 Troubleshooting

### Shortcodes Not Displaying

1. Ensure the plugin is **activated**
2. Check that you're using the exact shortcode syntax (case-sensitive)
3. Clear any caching plugins

### Images Not Showing

1. Verify image URLs are accessible
2. Check that Featured Images are set for Portfolio Items
3. Ensure images are uploaded to WordPress Media Library

### Gallery Not Animating

1. Check browser console for JavaScript errors
2. Verify CSS is loading (check Network tab)
3. Ensure you have at least 2 images in the gallery shortcode

## 📝 Requirements Met

✅ **No Elementor or Hello Elementor** - Pure WordPress implementation  
✅ **Custom Admin Panel** - Dedicated "Portfolio" menu in WordPress admin  
✅ **Project List Page** - `[pella_projects]` shortcode displays all items  
✅ **Basic Package Information** - Custom meta fields for name, price, and description  
✅ **Frontend Display** - All content renders beautifully on the frontend  

## 📝 License

This project is part of the Pella assignment submission.
