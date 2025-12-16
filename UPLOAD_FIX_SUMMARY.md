# Production Upload Fix Summary

## Problem Statement

User reported "Error Upload - Koneksi gagal. Pastikan internet stabil" when uploading multiple files or large files in production environment.

**Root Cause:** PHP timeout (30s default) + insufficient memory + all files processed in single request.

---

## Solution Implemented

### ✓ Backend Changes

#### 1. SertifikatController Constructor
```php
public function __construct()
{
    set_time_limit(300);                    // Extend to 5 minutes per request
    ini_set('memory_limit', '256M');        // Increase from default 128M
}
```

#### 2. Better Validation & Error Handling
- Per-file size validation (max 10MB per file, not 100MB)
- Detailed error messages with request ID for debugging
- Comprehensive logging untuk production monitoring

#### 3. Sequential File Processing (Already Implemented)
- One file at a time instead of all files at once
- Each file gets separate request
- Reduces memory usage significantly
- Better error isolation (know exactly which file failed)

---

### ✓ Frontend Changes

#### 1. Improved Error Detection
```javascript
// 2 minute timeout per file
const controller = new AbortController();
const timeoutId = setTimeout(() => {
    controller.abort();
}, 120000); // 120 seconds

// Detect specific errors
if (error.name === 'AbortError') {
    // Show timeout message
}
```

#### 2. Better Error Messages
- Detect server errors (502, 503, 504)
- Detect file size errors (413)
- Detect request length errors (414)
- Show network vs timeout vs validation errors

---

### ✓ Test Verification

All 5 upload tests passing:
```
✓ handleValidationErrorAsJson()      - Tests validation error responses
✓ handleInvalidFileTypeAsJson()      - Tests file format validation (only JPG/PNG/PDF)
✓ handleFileTooLargeAsJson()         - Tests file size limit (11MB rejected)
✓ handleMissingFormFieldsAsJson()    - Tests required form fields
✓ handleMissingFilesAsJson()         - Tests empty file array rejection
```

---

## Configuration Files Created

### 1. PRODUCTION_CONFIG.md
Complete guide untuk configure production environment:
- PHP settings (php.ini)
- Apache/Nginx configuration
- Web server timeout settings
- Database configuration
- Troubleshooting checklist

### 2. DEPLOYMENT_GUIDE.md
Step-by-step deployment procedure:
- Server preparation
- Code deployment
- Laravel environment setup
- PHP configuration (critical)
- SSL/HTTPS setup
- Log rotation
- Cron jobs setup
- Verification & testing

---

## Key Configuration Values

### PHP Settings (php.ini)
```ini
max_execution_time = 300          # 5 minutes for upload request
max_input_time = 300              # Input processing time
memory_limit = 256M               # File processing memory
post_max_size = 100M              # Max POST request size
upload_max_filesize = 100M        # Individual file limit
```

### Web Server Settings

**Apache:**
```apache
TimeOut 300                        # 5 minute timeout
```

**Nginx:**
```nginx
client_max_body_size 100M;
proxy_read_timeout 300s;
fastcgi_read_timeout 300;
```

### Application Settings (Laravel)
- Per-file limit: 10MB (validated with `max:10240`)
- Total upload limit: No limit (unlimited sequential files)
- Session timeout: 120 minutes
- Cache store: Database or Redis

---

## Upload Workflow Now

1. User selects multiple files (e.g., 20 files of 8MB each)
2. Frontend validates all form fields are filled
3. **Sequential upload starts:**
   - Upload file 1
   - If success → upload file 2
   - If error → stop and show error (other files won't upload)
   - Repeat until all files done
4. On final success → redirect to dashboard
5. Each upload request has 300s timeout + 256M memory available

---

## Deployment Checklist

Before going to production, ensure:

- [ ] PHP `max_execution_time` set to 300
- [ ] PHP `memory_limit` set to 256M minimum
- [ ] PHP `post_max_size` set to 100M
- [ ] PHP `upload_max_filesize` set to 100M
- [ ] Web server timeout configured (Apache TimeOut, Nginx proxy_read_timeout)
- [ ] Storage folder has write permissions (775)
- [ ] Temporary upload directory exists and writable (/tmp)
- [ ] Sufficient disk space available (check with `df -h`)
- [ ] Laravel migrations run including session table
- [ ] APP_DEBUG set to false
- [ ] Logs rotation configured
- [ ] SSL/HTTPS certificate installed

---

## Testing in Production

```bash
# 1. Verify PHP config
php -i | grep -E "max_execution_time|memory_limit|post_max_size|upload_max_filesize"

# 2. Check disk space
df -h

# 3. Test with single file first
# - Upload 1 file (5MB)
# - Monitor storage/logs/laravel.log

# 4. Test with multiple files
# - Upload 5 files (8MB each)
# - Monitor memory usage: top -u www-data

# 5. Test edge case
# - Upload 10+ files
# - Check logs for timeout messages
# - Verify all files uploaded
```

---

## Monitoring in Production

### Check Logs for Upload Activity

```bash
# Watch upload logs in real-time
tail -f storage/logs/laravel.log | grep "UPLOAD_MASSAL"

# Should show:
# - === UPLOAD MASSAL START === with request ID
# - Processing file X: filename
# - === UPLOAD MASSAL COMPLETE === with stats
```

### Monitor Resources During Upload

```bash
# In separate terminal, watch resources
top -u www-data

# Look for:
# - Memory usage peaks (should not exceed 256M)
# - CPU spikes (normal for file processing)
# - Execution time (should complete within 300s per file)
```

### Check for Errors

```bash
# PHP errors
tail -20 /var/log/php8.2-fpm.log

# Web server errors
tail -20 /var/log/apache2/e-certifikat_error.log  # Apache
tail -20 /var/log/nginx/error.log                  # Nginx

# Laravel errors
tail -20 storage/logs/laravel.log
```

---

## If Issues Still Occur

1. **"Koneksi gagal" error:**
   - Check `max_execution_time` is actually 300 (not 30)
   - Verify `memory_limit` is 256M or higher
   - Monitor network connection during upload
   - Try uploading smaller files first

2. **Memory exhaustion:**
   - Increase `memory_limit` to 512M temporarily
   - Check for memory leaks in logs
   - Ensure each file processed sequentially, not all at once

3. **File upload still fails:**
   - Check `post_max_size` and `upload_max_filesize` are >= 100M
   - Verify `/tmp` directory exists and writable
   - Check disk space with `df -h` (min 1GB recommended for uploads)
   - Monitor network connectivity

4. **Timeout during upload:**
   - Increase Apache TimeOut or Nginx proxy_read_timeout
   - Check if network is slow (may need to increase timeout further)
   - Consider chunked upload for very large files (50MB+)

---

## Performance Metrics

After fix is deployed, you should see:

| Metric | Expected |
|--------|----------|
| Max execution time per file | < 60 seconds for 10MB file |
| Memory usage per file | < 200MB (out of 256M available) |
| Success rate for multiple files | 99%+ (only fail if file-specific issue) |
| Error messages | Clear, specific, actionable |
| Upload responsiveness | File-by-file feedback to user |

---

## Related Files

### Modified/Created Files
- `app/Http/Controllers/SertifikatController.php` - Added constructor with timeout/memory config
- `resources/views/sertifikat/upload.blade.php` - Enhanced error handling, timeout detection
- `PRODUCTION_CONFIG.md` - New: Production configuration guide
- `DEPLOYMENT_GUIDE.md` - New: Step-by-step deployment guide

### Tests
- `tests/Feature/LargeFileUploadTest.php` - 5 tests, all passing ✓

---

## Next Steps for Production

1. **Read DEPLOYMENT_GUIDE.md** - Follow exact steps for your server
2. **Read PRODUCTION_CONFIG.md** - Configure php.ini and web server
3. **Verify Configuration** - Run PHP config check commands
4. **Test Upload** - Single file, then multiple files
5. **Monitor Logs** - Check for errors during testing
6. **Deploy to Production** - When confident it works
7. **Monitor Continuously** - Watch logs for any timeout issues

---

## Contact & Support

If "Koneksi gagal" error still appears after following deployment guide:

1. Provide output of PHP config check
2. Provide last 50 lines of `storage/logs/laravel.log`
3. Describe exact steps to reproduce (file count, file size)
4. Check network connectivity during upload (use browser Network tab)

This will help identify if issue is:
- Server configuration (most likely)
- Network connectivity (intermediate issue)
- Application bug (unlikely after these fixes)

