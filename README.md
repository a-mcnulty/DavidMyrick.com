# David Myrick Portfolio Website

A custom PHP-based photographer portfolio site featuring a unique DVD-stack style navigation menu with video backgrounds.

## Prerequisites

- PHP 7.2+
- MySQL 8.0+ (via Homebrew recommended)
- Node.js 14+ and npm
- Git

## Critical: Missing Files from Repository

**BEFORE YOU BEGIN**, you need the following directories from the previous developer:

1. **`src/` directory** - JavaScript source files that compile to `dist/site.js`
2. **`local_modules/` directory** - Custom npm packages:
   - `local_modules/ar-rows` - Custom rows module
   - `local_modules/social-icons` - Social media icons module

**What to request from previous developer:**
- Complete `src/` directory with all JavaScript source files
- Complete `local_modules/ar-rows/` directory (custom npm package)
- Complete `local_modules/social-icons/` directory (custom npm package)
- (Optional) Production `images/pics/` directory if you need sample images
- (Optional) Production `videos/` directory if you need sample videos

Without these directories, **`npm install` will fail** and the site won't build.

## Initial Setup

### 1. Clone Repository

```bash
git clone [repository-url]
cd DavidMyrick.com
```

### 2. Get Missing Directories

Contact the previous developer and request the directories listed above. Place them in the project root:
```
DavidMyrick.com/
├── src/                  # ← Get this
├── local_modules/        # ← Get this
│   ├── ar-rows/
│   └── social-icons/
```

### 3. Install Node Dependencies

Once you have the missing directories:

```bash
npm install
```

### 4. Database Setup

#### Start MySQL

```bash
# Start MySQL service
brew services start mysql

# Verify MySQL is running
brew services list | grep mysql
```

#### Create Database

```bash
# Login to MySQL as root
mysql -u root

# Create the database
CREATE DATABASE myrick;

# Exit MySQL
exit
```

#### Import Database

```bash
# Import the backup SQL file
mysql -u root myrick < myrick_backup.sql
```

#### Verify Database

```bash
# Login and check tables
mysql -u root myrick

# Show all tables
SHOW TABLES;

# Exit
exit
```

**Database Configuration** (already set in `includes/connect.php`):
- Host: `localhost`
- User: `root`
- Password: `` (empty)
- Database: `myrick`

### 5. Build Frontend Assets

**For development** (watches for changes and auto-rebuilds):
```bash
npm run dev
```

**For production**:
```bash
npm run build
```

This compiles JavaScript from `src/*.js` into `dist/site.js` which is loaded by the site.

### 6. Start PHP Development Server

```bash
php -S localhost:8000 router.php
```

The `router.php` file simulates Apache's mod_rewrite behavior for local development.

### 7. Access the Site

Open your browser to:
```
http://localhost:8000
```

## Project Structure

```
DavidMyrick.com/
├── src/                    # JS source files (MISSING - get from prev dev)
├── local_modules/          # Custom npm packages (MISSING - get from prev dev)
│   ├── ar-rows/
│   └── social-icons/
├── dist/                   # Compiled JS (auto-generated, gitignored)
├── includes/               # PHP includes
│   ├── connect.php        # Database connection
│   ├── header.php         # Navigation menu (custom DVD-stack menu)
│   ├── footer.php
│   ├── functions.php      # Helper functions
│   ├── scripts.php        # JS includes
│   ├── topScripts.php     # Head scripts
│   └── overlays.php       # Modal overlays
├── ajax/                   # AJAX endpoints
│   ├── grid-justified.php # Justified grid layout
│   ├── grid-masonry.php   # Masonry grid layout
│   └── textpage.php       # Text page loader
├── classes/                # PHP classes
│   ├── addToLightbox.php
│   ├── removeFromLightbox.php
│   └── removeAllLightbox.php
├── css/                    # Custom CSS
│   ├── custom.css         # Main styles
│   └── admin.css          # Admin styles
├── images/                 # Static images
│   ├── pics/              # User-uploaded images (gitignored)
│   ├── circle.svg
│   ├── square.svg
│   └── triangle.svg
├── videos/                 # Video assets (gitignored)
├── fonts/                  # Marvin font family
│   └── Marvin/
├── proshop/                # Admin/CMS system
├── *.php                   # Page templates
│   ├── index.php          # Homepage
│   ├── project.php        # Project page
│   ├── content.php        # Content page
│   ├── gridpage-masonry.php
│   ├── gridpage-justified.php
│   └── ...
├── router.php              # Local dev router (simulates .htaccess)
├── .htaccess               # Apache rewrite rules (production)
├── myrick_backup.sql       # Database backup
├── package.json            # npm dependencies
└── README.md               # This file
```

## Tech Stack

- **Backend**: PHP 7.2+
- **Database**: MySQL 8.0+
- **Frontend**: Vanilla JavaScript (no framework)
- **Build Tool**: Parcel 2.4.0
- **CSS**: Custom CSS with PostCSS/Autoprefixer
- **Libraries**:
  - Flickity - Carousel/slider
  - Masonry - Grid layouts
  - Vanilla LazyLoad - Image lazy loading
  - Zenscroll - Smooth scrolling

## Development Workflow

### Running the Site Locally

**Terminal 1** - Start MySQL (if not running):
```bash
brew services start mysql
```

**Terminal 2** - Build/watch JavaScript:
```bash
npm run dev
```

**Terminal 3** - Start PHP server:
```bash
php -S localhost:8000 router.php
```

**Browser**: Navigate to `http://localhost:8000`

### Making Changes

1. Edit source files in `src/` directory
2. Parcel will automatically rebuild (if running `npm run dev`)
3. Refresh browser to see changes
4. Edit PHP files - changes are immediate (just refresh)
5. Edit CSS in `css/` - changes are immediate (just refresh)

## Production Deployment

1. Build optimized assets:
```bash
npm run build
```

2. Upload files to production server (Apache-based)

3. Production uses `.htaccess` for URL rewriting (not `router.php`)

4. Ensure database connection in `includes/connect.php` points to production database

## Common Issues & Troubleshooting

### npm install fails
**Problem**: Missing `local_modules/` directory
**Solution**: Get the directory from previous developer

### Site loads but no JavaScript works
**Problem**: Haven't run build process
**Solution**: Run `npm run build` or `npm run dev`

### Error: dist/site.js not found
**Problem**: Build hasn't been run
**Solution**: Run `npm run dev` or `npm run build`

### Database connection errors
**Problem**: MySQL not running or database not created
**Solution**:
```bash
brew services start mysql
mysql -u root myrick < myrick_backup.sql
```

### Images don't load
**Problem**: `images/pics/` directory is gitignored
**Solution**: Get production assets from server or previous developer

### Videos don't play
**Problem**: `videos/` directory is gitignored
**Solution**: Get production assets from server or previous developer

## Git Workflow

Current branch: `main`

### Recent Development Focus
Recent commits show work on the custom navigation menu:
- Mobile menu auto-open timing
- Menu rotation and animation speeds
- Subcategory highlighting effects
- Bug fixes for menu behavior
- Visual refinements (opacity, transitions)

### Before Making Changes

1. Create a feature branch:
```bash
git checkout -b feature/your-feature-name
```

2. Make your changes and test thoroughly

3. Commit with descriptive messages:
```bash
git add .
git commit -m "Brief description of changes"
```

4. Push and create pull request:
```bash
git push origin feature/your-feature-name
```

## Build Commands Reference

```bash
# Install dependencies
npm install

# Watch and rebuild on changes (development)
npm run dev

# Build optimized bundle (production)
npm run build

# Clean build artifacts
npm run clean

# Clean and rebuild
npm run build
```

## Database Information

**Local Database**:
- Name: `myrick`
- User: `root`
- Password: (empty)
- Host: `localhost`

**Production Database** (commented out in `includes/connect.php`):
- Host: `mysql.davidmyrick.com`
- User: `myrick_eow`
- Database: `myrick_eow`

## Additional Documentation

- `.claude/navigation-menu.md` - Comprehensive documentation of the custom DVD-stack menu
- `.claude/server.md` - Server setup and routing documentation

## Support

For questions about missing files or setup issues, contact the previous developer.

For ongoing development questions, refer to the documentation in the `.claude/` directory.
