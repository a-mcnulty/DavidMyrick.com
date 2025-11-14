# Navigation Menu Documentation

## Vocabulary & Components

### Menu Structure
- **DVD Stack Menu**: The main navigation interface - a stack of overlapping rectangles that rotates vertically
- **Menu Items**: 5 category items displayed as stacked boxes
- **Active Item**: The centered/highlighted menu item (larger, different gradient)
- **Inactive Items**: The 4 non-centered items above and below the active item
- **Rectangles**: The main box shape for each menu item
- **Trapezoids**: The 3D "edge" polygons that create the stacked DVD case effect
  - Each trapezoid has 4 points
  - `onBottom: true` - trapezoid is below the rectangle (connects to bottom edge)
  - `onBottom: false` - trapezoid is above the rectangle (connects to top edge)
- **Text**: Menu item labels, left-aligned on desktop, centered on mobile
- **Background**: Video/image that displays behind the menu, changes based on active item

### SVG Coordinate System
- ViewBox: `0 0 800 900`
- Center: x=400, y=450
- All positions defined in desktop and mobile config arrays

## Features Developed

### 1. URL-Based Active Item
When menu opens, it automatically centers the item matching the current page URL. If no match found, uses default ordering.

### 2. Mobile Click Debouncing
Prevents double-firing of both touch and mouse events on mobile devices using `isHandlingClick` flag with 100ms timeout.

### 3. Responsive Text Alignment
- Desktop: Left-aligned with 25px offset from rectangle edge
- Mobile: Centered within rectangle
- Dynamically updates on window resize

### 4. Logo Hide on Menu Open
Logo opacity set to 0 when `.menuOn` class is active.

### 5. Slide-In/Out Animation
Menu slides in from right side of screen:
- Duration: 0.5s
- Easing: ease-in-out
- Uses `transform: translateX()` for performance
- Closed state: `translateX(100%)` (off-screen right)
- Open state: `translateX(0)` on mobile, `translateX(46%)` on large screens

### 6. Homepage Auto-Open Behavior
**15-Second Timer:**
- Starts when user lands on homepage (`/`)
- Automatically opens menu after 15 seconds
- Stops permanently once menu opens by any method
- One-time only per page visit

**Scroll Gesture Trigger:**
- Detects mousewheel or touch swipe gestures
- Opens menu on any scroll attempt (page is 100vh, doesn't actually scroll)
- Continues to work after menu has been opened/closed
- Uses `wheel` and `touchmove` event listeners

**Hamburger Menu:**
- Always works to open/close menu
- Stops auto-open timer when clicked

### 7. Background Fallback
If no background video/image matches the active item's catId, the first background cell is activated as fallback to ensure something always displays.

## Resizing Method & Concerns

### Scaling Approach
We use **coordinate recalculation** rather than CSS transforms to scale the menu:

1. Calculate center point of each element
2. Scale width/height by desired factor
3. Recalculate x,y positions so center remains at same coordinates
4. Update trapezoid points to maintain connections to rectangle edges

### Scaling History
- **Original**: Base 1x scale
- **First scale**: 1.25x larger (all elements scaled from center)
- **Desktop reduction**: Reduced by 12% (now 1.1x from original)
- **Mobile**: Remains at 1.25x scale

### Why Not CSS Transform Scale?
- Tried `transform: scale()` but causes positioning conflicts
- Absolute positioning + transform can shift unexpectedly
- Coordinate recalculation gives precise control and maintains center

### Why Not ViewBox Scaling?
- Tried changing SVG viewBox from `0 0 800 900` to smaller dimensions
- Caused overflow issues - menu extended beyond container
- Container max-width conflicts
- Reverted and used coordinate approach instead

### Critical Consideration
**Always recalculate from center point** to avoid shifting menu down/right. When scaling:
- Don't just multiply all coordinates (shifts position)
- Calculate each element's center
- Scale size around that center
- Trapezoids must be recalculated to align with new rectangle edges

## Starting the Application

### Database
```bash
brew services start mysql
```

### Local Server
```bash
php -S localhost:8000 router.php
```

The `router.php` file simulates Apache's routing behavior for local development. In production, the site runs on Apache.

### Database Configuration
- Host: localhost
- User: root
- Password: (empty)
- Database: myrick

## Files Modified

### Primary Files
1. **`/Users/alexandermcnulty/davidmyrick.com/includes/header.php`**
   - Main navigation menu implementation
   - SVG menu structure (lines ~130-158)
   - CSS styling (lines ~164-274)
   - JavaScript logic (lines ~277-821)
   - Desktop position configs (lines ~308-315)
   - Mobile position configs (lines ~317-324)

2. **`/Users/alexandermcnulty/davidmyrick.com/ajax/grid-masonry.php`**
   - Fixed null array access warning (line 57)
   - Added null check for `$itemVideo` before accessing properties

### Supporting Files
3. **`/Users/alexandermcnulty/davidmyrick.com/.claude/server.md`**
   - Documents server startup commands
   - Explains router.php purpose

## Key Code Sections in header.php

### Position Configurations
- Desktop configs: Lines 308-315
- Mobile configs: Lines 317-324
- Each config contains: frontRect (x, y, width, height), trapezoid (points, onBottom), textX, textY, gradient

### Animation & Interaction
- Slide animation CSS: Lines 189-227
- Homepage auto-open variables: Lines 343-346
- Scroll gesture handler: Lines 702-713
- Menu button click handler: Lines 753-795
- Touch/mouse event handlers: Lines 506-606

### Responsive Behavior
- Text anchor updates: Line 491
- Window resize handler: Lines 711-725
- Breakpoint-specific transforms: Lines 215-227, 265-268

## Current Menu State

### Desktop (1.1x scale from original)
- Position 0: 440×35.948px rectangle
- Position 1: 550×35.948px rectangle
- Position 2 (active): 605×192.5px rectangle
- Position 3: 550×35.948px rectangle
- Position 4: 440×35.948px rectangle

### Mobile (1.25x scale from original)
- Position 0: 500×64.5px rectangle
- Position 1: 625×64.5px rectangle
- Position 2 (active): 687.5×245px rectangle
- Position 3: 625×64.5px rectangle
- Position 4: 500×64.5px rectangle

### Container
- Max-width: 1000px
- SVG viewBox: 0 0 800 900 (unchanged)

## Notes & Gotchas

- Text alignment changes require updating both `textX` values AND `text-anchor` attribute
- Trapezoid points must align with rectangle edges (bottom edge for onBottom:true, top edge for onBottom:false)
- Homepage uses `visibility: hidden` instead of `display: none` to allow CSS transitions
- Background update function needs fallback for unmatched catIds
- Mobile events require debouncing to prevent double-triggering
- Console logs added for debugging click behavior (can be removed later)
