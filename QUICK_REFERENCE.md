# Quick Reference: Upload Fix untuk Production

## ✓ What Was Fixed

**Problem:** "Error Upload - Koneksi gagal" ketika upload multiple/large files
**Solution:** Timeout + Memory configuration + Sequential upload architecture

---

## 📋 For Production Server Admin

### Critical PHP Settings (php.ini)

```bash
# Edit php.ini
sudo nano /etc/php/8.2/apache2/php.ini  # Apache
sudo nano /etc/php/8.2/fpm/php.ini      # Nginx

# Change these values:
max_execution_time = 300      # (was: 30)
memory_limit = 256M           # (was: 128M)
post_max_size = 100M          # (should be)
upload_max_filesize = 100M    # (should be)

# Save & restart PHP
sudo systemctl restart apache2           # Apache
sudo systemctl restart php8.2-fpm        # Nginx
```

### Verify Configuration

```bash
php -i | grep -E "max_execution_time|memory_limit|post_max_size|upload_max_filesize"

# Expected output:
max_execution_time => 300 => 300
memory_limit => 256M => 256M
post_max_size => 100M => 100M
upload_max_filesize => 100M => 100M
```

### Web Server Configuration

**Apache (.htaccess or apache2.conf):**
```apache
TimeOut 300
```

**Nginx (server block):**
```nginx
client_max_body_size 100M;
proxy_read_timeout 300s;
fastcgi_read_timeout 300;
```

---

## 🧪 Testing

### Test Single File Upload
1. Navigate to Sertifikat Upload page
2. Select 1 file (5-8 MB)
3. Fill form fields
4. Click Upload
5. Wait for completion
6. Verify file uploaded

### Test Multiple Files Upload
1. Select 5 files (8MB each)
2. Fill form fields  
3. Click Upload
4. Watch progress: File 1 of 5, File 2 of 5, etc.
5. All files should upload successfully

### Test Edge Cases
- Upload 10+ files (total 100MB+)
- Upload different formats (JPG, PNG, PDF)
- Very large files (9.9MB - close to 10MB limit)
- PDF documents (largest files usually)

---

## 📊 Monitoring

### Check Upload Logs

```bash
# Watch in real-time
tail -f storage/logs/laravel.log | grep "UPLOAD_MASSAL"

# Or check after upload
grep "UPLOAD_MASSAL" storage/logs/laravel.log | tail -5
```

Look for lines like:
```
[2024-12-16 14:30:00] production.INFO: === UPLOAD MASSAL START === 
[2024-12-16 14:30:15] production.INFO: === UPLOAD MASSAL COMPLETE === 
```

### Monitor Resources During Upload

```bash
# In terminal, watch CPU/Memory
top -u www-data

# Memory should stay below 256M
# CPU may spike temporarily (normal)
```

### Check Disk Space

```bash
# Ensure enough space for uploads
df -h

# Check storage folder
du -sh storage/app/public/sertifikat_photos
```

---

## ❌ If Still Getting "Koneksi gagal" Error

### Step 1: Verify Settings

```bash
# Check max_execution_time is 300 (NOT 30!)
php -i | grep "max_execution_time" | head -1

# Should show: max_execution_time => 300 => 300
# If shows 30, php.ini wasn't updated correctly
```

### Step 2: Check Server Logs

```bash
# Apache
tail -20 /var/log/apache2/error.log

# Nginx
tail -20 /var/log/nginx/error.log

# PHP FPM
tail -20 /var/log/php8.2-fpm.log

# Laravel
tail -20 storage/logs/laravel.log
```

### Step 3: Test Network Connectivity

1. Open browser DevTools (F12)
2. Go to Network tab
3. Upload file
4. Check response status code:
   - 200 = Success ✓
   - 422 = Validation error (check file size/format)
   - 413 = File too large (> 10MB per file)
   - 500 = Server error (check logs)
   - No response = Timeout (check max_execution_time)

### Step 4: Restart Services

```bash
# Restart PHP (Nginx)
sudo systemctl restart php8.2-fpm

# Restart Apache
sudo systemctl restart apache2

# Restart Nginx  
sudo systemctl restart nginx
```

---

## 📁 File Limits

| Setting | Value | Purpose |
|---------|-------|---------|
| Per file max | 10 MB | Frontend validation |
| Total per request | 100 MB | post_max_size |
| Execution time | 300 sec (5 min) | Per upload request |
| Memory available | 256 MB | File processing |

---

## 🎯 Expected Behavior After Fix

### Single File Upload (8MB)
- ✓ Upload completes in < 30 seconds
- ✓ No timeout error
- ✓ File appears on dashboard

### Multiple Files Upload (5 × 8MB = 40MB total)
- ✓ Files upload sequentially (one after another)
- ✓ Progress shown as "File X of Y"
- ✓ All files complete successfully
- ✓ Redirect to dashboard on completion
- ✓ No "Koneksi gagal" error

### Large Files (Close to 10MB limit)
- ✓ Files up to 9.9MB upload successfully
- ✓ Files over 10MB rejected with message
- ✓ Clear error message explaining limit

---

## 📞 Troubleshooting Checklist

| Issue | Check | Fix |
|-------|-------|-----|
| Timeout error | max_execution_time | Set to 300 |
| Memory error | memory_limit | Set to 256M |
| File too large error | Check file size | Ensure < 10MB per file |
| Upload never completes | Network connection | Retry, check internet |
| Wrong error message | Browser console | May need to refresh |
| File uploads but doesn't save | Storage permissions | Set chmod 775 storage/ |

---

## 📚 Full Documentation

For detailed setup, see:
- `DEPLOYMENT_GUIDE.md` - Step-by-step production deployment
- `PRODUCTION_CONFIG.md` - Complete configuration options
- `UPLOAD_FIX_SUMMARY.md` - Technical summary of changes

---

## ✅ Deployment Checklist

Before considering issue resolved:

- [ ] PHP `max_execution_time` = 300 ✓
- [ ] PHP `memory_limit` ≥ 256M ✓
- [ ] PHP `post_max_size` ≥ 100M ✓
- [ ] PHP `upload_max_filesize` ≥ 100M ✓
- [ ] Web server timeout configured ✓
- [ ] Tests passing (5/5) ✓
- [ ] Single file upload working ✓
- [ ] Multiple files upload working ✓
- [ ] No timeout errors in logs ✓
- [ ] Disk space sufficient (> 1GB) ✓

---

## Quick Copy-Paste Commands

```bash
# Verify PHP config
php -i | grep -E "max_execution_time|memory_limit|post_max_size|upload_max_filesize"

# Check storage
du -sh /var/www/e-certifikat/storage/

# Watch logs
tail -f /var/www/e-certifikat/storage/logs/laravel.log

# Run tests
cd /var/www/e-certifikat && php artisan test tests/Feature/LargeFileUploadTest.php

# Clear cache
cd /var/www/e-certifikat && php artisan cache:clear && php artisan config:clear
```

---

## Support

If issue persists:

1. Provide output of PHP config verification command
2. Provide last 50 lines of laravel.log
3. Describe: How many files? What size each? What error message?
4. Check Network tab in Browser DevTools for actual response

