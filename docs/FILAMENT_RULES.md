# Filament Architecture Rules — Maverick Business Academy

## 📋 Overview

This document defines the rules and conventions for working with Filament in the Maverick project.

---

## 🏗️ Architecture Structure

```
app/Filament/
├── Concerns/                    # Shared traits
│   ├── HandlesCloudinaryImageFields.php
│   └── HasStrictValidation.php
├── Forms/
│   └── Components/              # Custom form components
│       ├── MediaPicker.php
│       └── SeoFormFields.php
├── Pages/                       # Settings pages (singleton)
│   ├── ManageHero.php
│   ├── ManageSiteSettings.php
│   └── ...
├── Resources/                   # CRUD resources
│   ├── PartnerLogoResource.php
│   ├── ProgramResource.php
│   └── ...
└── Widgets/                     # Dashboard widgets
    └── SiteOverviewWidget.php
```

---

## 📐 Rules & Conventions

### 1. Resource Naming
- **Pattern:** `{Model}Resource.php`
- **Example:** `PartnerLogoResource.php` for `PartnerLogo` model
- **Navigation Label:** Human-readable plural (e.g., "Partner Logos")

### 2. Form Structure
- Use **Tabs** for complex forms with multiple sections
- Use **Sections** for grouping related fields
- Always include `MediaPicker` for image uploads (not default FileUpload)
- Use `HandlesCloudinaryImageFields` trait for all resources with images

### 3. Table Structure
- Always include `TextColumn` for primary identifier
- Use `ImageColumn` for logos/images (size: 50px)
- Use `Badge` styling for status/type columns
- Include `IconColumn` for boolean fields
- Add filters for dropdown selects (type, category, etc.)
- Default sort by `sort_order` if available

### 4. Type/Category Fields
- Use `Select` with predefined options
- Include emoji icons in formatStateUsing for visual clarity
- Color-code badges by type

### 5. Media Handling
```php
// Always use MediaPicker for image uploads
MediaPicker::forField('field_name', 'folder-name')

// In Edit pages, use preserveExistingImageFields
use HandlesCloudinaryImageFields;

protected function mutateFormDataBeforeSave(array $data): array
{
    return $this->preserveExistingImageFields($data, $this->record);
}
```

### 6. Settings Pages
- Use `Spatie\LaravelSettings` for singleton content
- Register in `config/settings.php`
- Create settings migration
- Use `ManageXxx` naming pattern

### 7. Soft Deletes
- If model uses SoftDeletes:
  - Add `TrashedFilter` in table filters
  - Add `ForceDeleteBulkAction` and `RestoreBulkAction`
  - Override `getEloquentQuery` to remove `SoftDeletingScope`

---

## 🎨 Design Patterns

### Partner Logo Resource (Reference Implementation)
```
Tabs:
├── Basic Info Tab
│   ├── TextInput (name)
│   ├── MediaPicker (logo_url)
│   ├── Select (type with emoji badges)
│   ├── TextInput (sort_order)
│   └── Toggle (is_active)
└── Display Info Tab
    └── Placeholder (usage guide)
```

### Type Badge Colors
```php
'alumni' => 'info',        // Blue
'accreditation' => 'success', // Green
'recognition' => 'warning',   // Yellow
'award' => 'danger',          // Red
```

### Type Badge Labels
```php
'alumni' => '🎓 Alumni',
'accreditation' => '✅ Accreditation',
'recognition' => '🏆 Recognition',
'award' => '🥇 Award',
```

---

## 📋 Checklist for New Resources

- [ ] Model has `$fillable` array
- [ ] Model has `$casts` for boolean/integer fields
- [ ] Resource uses `HandlesCloudinaryImageFields` (if has images)
- [ ] Form uses Tabs for complex structures
- [ ] Table has filters for type/category fields
- [ ] Table has default sort
- [ ] Badge styling for status columns
- [ ] MediaPicker used for image uploads
- [ ] Edit page uses `preserveExistingImageFields`
- [ ] SoftDeletes handled properly (if applicable)

---

## 🔗 Related Files

| File | Purpose |
|------|---------|
| `app/Filament/Concerns/HandlesCloudinaryImageFields.php` | Cloudinary image handling |
| `app/Filament/Forms/Components/MediaPicker.php` | Custom media picker |
| `app/Filament/Forms/Components/SeoFormFields.php` | SEO form fields |
| `config/settings.php` | Settings registration |
| `app/Providers/Filament/AdminPanelProvider.php` | Panel configuration |

---

*Document created: 2026-08-03*
