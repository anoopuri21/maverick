#!/bin/bash
# =============================================================
# LOCAL UI TESTING SCRIPT
# Runs all validations before commit/push
# NEVER commit this file or its outputs
# =============================================================

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

PASSED=0
FAILED=0
TOTAL=0

pass() {
    echo -e "${GREEN}✅ PASS:${NC} $1"
    ((PASSED++))
    ((TOTAL++))
}

fail() {
    echo -e "${RED}❌ FAIL:${NC} $1"
    ((FAILED++))
    ((TOTAL++))
}

warn() {
    echo -e "${YELLOW}⚠️  WARN:${NC} $1"
}

info() {
    echo -e "${BLUE}ℹ️  INFO:${NC} $1"
}

# =============================================================
echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║         LOCAL UI TESTING ENVIRONMENT                      ║"
echo "║         Maverick Business Academy                         ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# =============================================================
# PHASE 1: Build Verification
# =============================================================
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 1: BUILD VERIFICATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Check PHP
if command -v php &> /dev/null; then
    pass "PHP installed: $(php -v | head -n 1 | cut -d' ' -f1-2)"
else
    fail "PHP not installed"
fi

# Check Composer
if command -v composer &> /dev/null; then
    pass "Composer installed"
else
    warn "Composer not found"
fi

# Check Node.js
if command -v node &> /dev/null; then
    pass "Node.js installed: $(node -v)"
else
    fail "Node.js not installed"
fi

# Check npm
if command -v npm &> /dev/null; then
    pass "npm installed: $(npm -v)"
else
    fail "npm not installed"
fi

# =============================================================
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 2: SYNTAX VALIDATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Check Blade templates
echo ""
info "Checking Blade templates..."
BLADE_ERRORS=0
for f in resources/views/pages/*.blade.php resources/views/sections/*.blade.php; do
    if [ -f "$f" ]; then
        # Check for unmatched directives
        OPENS=$(grep -c "@if\|@foreach\|@for\|@php" "$f" 2>/dev/null || echo 0)
        CLOSES=$(grep -c "@endif\|@endforeach\|@endfor\|@endphp" "$f" 2>/dev/null || echo 0)
        
        if [ "$OPENS" != "$CLOSES" ]; then
            fail "Blade directive mismatch in $f (opens: $OPENS, closes: $CLOSES)"
            ((BLADE_ERRORS++))
        fi
    fi
done
if [ "$BLADE_ERRORS" -eq 0 ]; then
    pass "All Blade templates have balanced directives"
fi

# Check JavaScript syntax
echo ""
info "Checking JavaScript syntax..."
JS_ERRORS=0
for f in public/assets/js/*.js public/assets/js/**/*.js; do
    if [ -f "$f" ]; then
        if ! node -c "$f" 2>/dev/null; then
            fail "JavaScript syntax error in $f"
            ((JS_ERRORS++))
        fi
    fi
done
if [ "$JS_ERRORS" -eq 0 ]; then
    pass "All JavaScript files have valid syntax"
fi

# Check CSS syntax (basic)
echo ""
info "Checking CSS files..."
CSS_ERRORS=0
for f in public/assets/css/*.css public/css/**/*.css; do
    if [ -f "$f" ]; then
        # Check for unclosed braces
        OPENS=$(grep -o "{" "$f" | wc -l)
        CLOSES=$(grep -o "}" "$f" | wc -l)
        if [ "$OPENS" -ne "$CLOSES" ]; then
            warn "CSS brace mismatch in $f (opens: $OPENS, closes: $CLOSES)"
            ((CSS_ERRORS++))
        fi
    fi
done
if [ "$CSS_ERRORS" -eq 0 ]; then
    pass "All CSS files have balanced braces"
fi

# =============================================================
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 3: ASSET VERIFICATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

# Check critical CSS files
CSS_FILES=(
    "public/assets/css/main.css"
    "public/assets/css/sections.css"
    "public/assets/css/our-story.css"
    "public/assets/css/responsive.css"
)

for file in "${CSS_FILES[@]}"; do
    if [ -f "$file" ]; then
        SIZE=$(wc -c < "$file")
        if [ "$SIZE" -gt 0 ]; then
            pass "CSS exists: $file ($SIZE bytes)"
        else
            fail "CSS empty: $file"
        fi
    else
        fail "CSS missing: $file"
    fi
done

# Check critical JS files
JS_FILES=(
    "public/assets/js/main.js"
    "public/assets/js/animations.js"
    "public/assets/js/animations-utils.js"
    "public/assets/js/navigation.js"
)

for file in "${JS_FILES[@]}"; do
    if [ -f "$file" ]; then
        SIZE=$(wc -c < "$file")
        if [ "$SIZE" -gt 0 ]; then
            pass "JS exists: $file ($SIZE bytes)"
        else
            fail "JS empty: $file"
        fi
    else
        fail "JS missing: $file"
    fi
done

# Check layout file
if [ -f "resources/views/layouts/app.blade.php" ]; then
    pass "Layout file exists"
else
    fail "Layout file missing"
fi

# =============================================================
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 4: RESPONSIVE VALIDATION CHECKLIST"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

echo ""
info "Responsive breakpoints to verify manually:"
echo "  • Mobile: 375px"
echo "  • Tablet: 768px"
echo "  • Desktop: 1024px"
echo "  • Large Desktop: 1440px"

# Check for responsive CSS
if grep -q "@media" public/assets/css/our-story.css 2>/dev/null; then
    pass "Our Story CSS has responsive styles"
else
    fail "Our Story CSS missing responsive styles"
fi

if grep -q "@media" public/css/pages/*.css 2>/dev/null; then
    pass "Page CSS files have responsive styles"
else
    warn "Some page CSS may be missing responsive styles"
fi

# =============================================================
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 5: ANIMATION CHECKLIST"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

echo ""
info "Animation files to verify:"
echo "  • GSAP loaded in layout"
echo "  • ScrollTrigger loaded in layout"
echo "  • Lenis smooth scroll loaded"
echo "  • prefers-reduced-motion respected"

# Check GSAP loading
if grep -q "gsap" resources/views/layouts/app.blade.php 2>/dev/null; then
    pass "GSAP reference found in layout"
else
    fail "GSAP not found in layout"
fi

# Check reduced motion support
if grep -q "prefers-reduced-motion" public/assets/css/our-story.css 2>/dev/null; then
    pass "Reduced motion support in Our Story CSS"
else
    fail "Reduced motion support missing"
fi

# =============================================================
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 6: SEO & ACCESSIBILITY CHECKLIST"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

echo ""
info "SEO elements to verify:"
echo "  • Title tags on all pages"
echo "  • Meta descriptions on all pages"
echo "  • OG tags (when implemented)"
echo "  • Schema markup (when implemented)"

# Check for title tags
if grep -q "@section('title'" resources/views/pages/*.blade.php 2>/dev/null; then
    pass "Title tags found in page templates"
else
    warn "Some pages may be missing title tags"
fi

# Check for meta descriptions
if grep -q "@section('meta_description'" resources/views/pages/*.blade.php 2>/dev/null; then
    pass "Meta descriptions found in page templates"
else
    warn "Some pages may be missing meta descriptions"
fi

# =============================================================
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 7: ROUTE VERIFICATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

echo ""
info "Checking routes..."
if [ -f "routes/web.php" ]; then
    ROUTE_COUNT=$(grep -c "Route::" routes/web.php 2>/dev/null || echo 0)
    pass "Routes file exists ($ROUTE_COUNT routes)"
else
    fail "Routes file missing"
fi

# =============================================================
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "PHASE 8: GIT STATUS CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

echo ""
info "Files to be committed:"
git status --short

echo ""
info "Checking for testing artifacts in staging..."
TESTING_FILES=$(git diff --cached --name-only | grep -E "playwright|test-results|screenshots|\.png$|\.jpg$|\.mp4$" 2>/dev/null || true)
if [ -n "$TESTING_FILES" ]; then
    fail "Testing artifacts found in staging: $TESTING_FILES"
else
    pass "No testing artifacts in staging"
fi

# =============================================================
echo ""
echo "╔════════════════════════════════════════════════════════════╗"
echo "║                    TEST RESULTS                           ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""
echo -e "Total Tests:  $TOTAL"
echo -e "Passed:       ${GREEN}$PASSED${NC}"
echo -e "Failed:       ${RED}$FAILED${NC}"
echo ""

if [ $FAILED -gt 0 ]; then
    echo -e "${RED}❌ UI TESTS FAILED - DO NOT COMMIT/PUSH${NC}"
    echo ""
    echo "Fix the issues above and run again."
    exit 1
else
    echo -e "${GREEN}✅ ALL UI TESTS PASSED - SAFE TO COMMIT/PUSH${NC}"
    exit 0
fi
