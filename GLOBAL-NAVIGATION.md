# Global Navigation System

## Overview

The new global navigation system provides a modern, responsive, and consistent navigation experience across all pages of the Minecraft Hosting application. It replaces the old admin-layout system with a unified approach that works for all user types.

## Features

- **Responsive Design**: Adapts seamlessly from mobile (320px) to ultra-wide screens (1920px+)
- **Modern Glass Morphism UI**: Beautiful blur effects and gradient backgrounds
- **Role-based Navigation**: Different menu items based on user permissions
- **Mobile-first Approach**: Optimized for mobile devices with collapsible menu
- **Accessibility**: Full keyboard navigation and screen reader support
- **Performance Optimized**: Minimal CSS/JS overhead with efficient rendering

## Usage

### Basic Layout

Replace any `<x-app-layout>` or `<x-admin-layout>` with:

```blade
<x-global-layout title="Page Title">
    <!-- Your content here -->
</x-global-layout>
```

### Props

- `title` (optional): Sets the page title (defaults to app name)

### Content Structure

The layout provides several content areas:

1. **Navigation Bar**: Fixed top navigation with logo, menu items, and user menu
2. **Main Content**: Scrollable content area with `{{ $slot }}`
3. **Footer**: Consistent footer with links and company information

## Navigation Features

### Desktop Navigation

- Horizontal navigation bar with hover effects
- Dropdown menus for admin and user functions
- Notification indicators
- User avatar with dropdown menu

### Mobile Navigation

- Hamburger menu button
- Slide-out mobile menu
- Touch-friendly menu items
- Responsive user menu

### Role-based Menus

#### Regular Users
- Dashboard
- Servers
- Billing
- Support

#### Admin Users
- All regular user items
- Admin dropdown with:
  - Admin Dashboard
  - User Management
  - Plans Management
  - Orders Management
  - Servers Management
  - Billing Management
  - Notifications

## Styling System

### CSS Classes

The global layout introduces several utility classes:

- `.hero-gradient`: Purple gradient background
- `.glass-card`: Glass morphism card effect
- `.nav-link`: Styled navigation links with hover effects
- `.dropdown-menu`: Styled dropdown menus
- `.mobile-menu`: Mobile menu styling

### Responsive Breakpoints

- **Mobile**: 0px - 767px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px - 1919px
- **Ultra-wide**: 1920px+

## Components Integration

### Flash Messages

The layout automatically styles flash messages:

```blade
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 flex items-center">
        <i class="fas fa-check-circle mr-3 text-green-600"></i>
        {{ session('success') }}
    </div>
@endif
```

### Page Headers

For consistent page headers, use this pattern:

```blade
<div class="bg-gradient-to-r from-gray-700 to-gray-900 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold">Page Title</h1>
        <p class="text-gray-300">Page description</p>
    </div>
</div>
```

### Content Cards

Use glass cards for content containers:

```blade
<div class="glass-card hover:bg-opacity-20 transition-all duration-300 p-6 rounded-xl shadow-lg">
    <!-- Card content -->
</div>
```

## Updated Pages

The following pages have been updated to use the global layout:

- ✅ Dashboard (`dashboard.blade.php`)
- ✅ Admin Dashboard (`admin/dashboard.blade.php`)
- ✅ Admin Users (`admin/users.blade.php`)
- ✅ Profile Edit (`profile/edit.blade.php`)
- ✅ Welcome Page (`welcome-new.blade.php`)

## Migration Guide

### From admin-layout

Replace:
```blade
<x-admin-layout>
    <x-slot name="header">
        <h2>Page Title</h2>
    </x-slot>
    
    <div class="py-12">
        <!-- content -->
    </div>
</x-admin-layout>
```

With:
```blade
<x-global-layout title="Page Title">
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">Page Title</h1>
        </div>
    </div>
    
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- content -->
        </div>
    </div>
</x-global-layout>
```

### From app-layout

Replace:
```blade
<x-app-layout>
    <x-slot name="header">
        <h2>Page Title</h2>
    </x-slot>
    
    <!-- content -->
</x-app-layout>
```

With:
```blade
<x-global-layout title="Page Title">
    <!-- Use hero section or page header as needed -->
    <!-- content -->
</x-global-layout>
```

## Browser Support

- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+
- Mobile Safari iOS 13+
- Chrome Android 80+

## Performance

- CSS: ~8KB gzipped
- JS: Alpine.js CDN (~15KB)
- Images: SVG icons for crisp rendering
- Fonts: Google Fonts with preconnect optimization

## Accessibility

- WCAG 2.1 AA compliant
- Keyboard navigation support
- Screen reader optimized
- High contrast mode support
- Reduced motion preferences respected

## Testing Checklist

When implementing on new pages:

- [ ] Test on mobile devices (320px - 767px)
- [ ] Test on tablets (768px - 1023px)
- [ ] Test on desktop (1024px+)
- [ ] Verify navigation menus work
- [ ] Check user role-based menu items
- [ ] Test keyboard navigation
- [ ] Verify accessibility compliance
- [ ] Check print styles
- [ ] Test with reduced motion settings

## Support

For issues or questions about the global navigation system:

1. Check this documentation
2. Review the component files in `resources/views/components/`
3. Check CSS files in `resources/css/`
4. Test in different browsers and devices

## Future Enhancements

- Dark mode toggle
- User preference settings
- Notification center
- Search functionality
- Breadcrumb navigation
- Multi-language support
