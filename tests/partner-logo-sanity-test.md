# Partner Logo Module — Sanity Test Plan

## 📋 Test Checklist

### 1. Database Migration
- [ ] Run migration to add 'recognition' and 'award' types
- [ ] Verify enum values in database

### 2. Create Partner Logo
- [ ] Create with type: alumni ✅
- [ ] Create with type: accreditation ✅
- [ ] Create with type: recognition ✅ (NEW)
- [ ] Create with type: award ✅ (NEW)

### 3. Edit Partner Logo
- [ ] Edit name without changing image ✅
- [ ] Edit with new image upload ✅
- [ ] Change type from alumni to accreditation ✅
- [ ] Change type to recognition ✅ (NEW)
- [ ] Change type to award ✅ (NEW)

### 4. List Page Filters
- [ ] Filter by All ✅
- [ ] Filter by Alumni ✅
- [ ] Filter by Accreditation ✅
- [ ] Filter by Recognition ✅
- [ ] Filter by Awards ✅

### 5. Delete Partner Logo
- [ ] Delete single item ✅
- [ ] Bulk delete ✅

### 6. Display on Frontend
- [ ] Homepage: Alumni section shows alumni type ✅
- [ ] Homepage: Accreditations section shows accreditation + alumni + recognition ✅
- [ ] Accreditation Page: Partnerships shows accreditation + alumni ✅
- [ ] Accreditation Page: Awards shows award + recognition ✅

---

## ✅ Expected Results
All tests should pass without errors.
