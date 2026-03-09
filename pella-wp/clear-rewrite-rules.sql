-- SQL queries to clear rewrite rules and flush permalinks
-- Run these in phpMyAdmin or MySQL command line
-- Database: pella

-- 1. Clear rewrite rules (MAIN FIX - this will force WordPress to regenerate them)
DELETE FROM wp_options WHERE option_name = 'rewrite_rules';

-- 2. Clear any cached post type data related to pella_project (optional)
DELETE FROM wp_options WHERE option_name LIKE '%pella_project%';

-- 3. After running these queries, go to WordPress Admin -> Settings -> Permalinks
--    and click "Save Changes" (this will regenerate the rewrite rules)

-- Alternative: If you want to clear ALL rewrite-related options:
-- DELETE FROM wp_options WHERE option_name IN ('rewrite_rules', 'permalink_structure');

-- Note: Replace 'wp_' with your actual table prefix if different
-- Default WordPress prefix is 'wp_'
