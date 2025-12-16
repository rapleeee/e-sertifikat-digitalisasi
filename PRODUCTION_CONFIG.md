# Production Configuration Guide

## Issue: "Koneksi gagal" Error saat Upload di Production

Ini terjadi karena timeout pada request yang panjang atau memory limit yang kurang. Follow panduan ini untuk fix production environment Anda.

---

## 1. Backend Configuration (PHP/Laravel)

### A. php.ini Settings

Edit file `php.ini` di production server Anda:

```ini
# Execution time
max_execution_time = 300          ; 5 menit untuk request normal (upload memerlukan ini)
default_socket_timeout = 300      ; Socket timeout juga
max_input_time = 300              ; Input time untuk file upload

# Memory
memory_limit = 256M               ; Minimum 256M untuk processing files
post_max_size = 100M              ; Max POST request size
upload_max_filesize = 100M        ; Max file upload size

# File uploads
file_uploads = On
upload_tmp_dir = /tmp             ; Temporary upload directory (pastikan writable)

# Session
session.gc_maxlifetime = 1440     ; 24 menit session lifetime
session.save_path = /var/lib/php/sessions  ; Custom session path recommended
```

### B. Verify PHP Config

Jalankan command ini untuk verify settings:

```bash
php -i | grep -E "max_execution_time|memory_limit|post_max_size|upload_max_filesize"
```

Expected output:
```
max_execution_time => 300 => 300
memory_limit => 256M => 256M
post_max_size => 100M => 100M
upload_max_filesize => 100M => 100M
```

### C. Laravel Configuration (.env)

File `.env` di production:

```env
# Basic
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning

# Session - use database untuk production
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

# Cache (gunakan redis atau memcached jika available)
CACHE_STORE=database

# Queue (untuk optional async processing nanti)
QUEUE_CONNECTION=database
```

---

## 2. Web Server Configuration

### A. Apache (.htaccess / apache2.conf)

File `public/.htaccess` sudah ada dengan konfigurasi yang baik.

Tambahkan ke `.htaccess` atau `apache2.conf` (di `<Directory>` block):

```apache
# Timeout settings untuk long uploads
TimeOut 300

# Header settings
<IfModule mod_headers.c>
    Header always set Connection "keep-alive"
    Header always set Keep-Alive "timeout=60, max=100"
</IfModule>

# For file uploads
<IfModule mod_mime.c>
    AddType application/octet-stream .bin .exe .dll .deb .dmg
    AddType application/x-gzip .gz .gzip
</IfModule>
```

Verify Apache modules enabled:

```bash
sudo a2enmod rewrite      # URL rewriting
sudo a2enmod headers      # Header manipulation
sudo a2enmod expires      # Cache expiration
sudo systemctl restart apache2
```

### B. Nginx Configuration

Jika menggunakan Nginx, tambahkan ke server block:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    
    # Timeouts untuk long uploads
    proxy_connect_timeout 300s;
    proxy_send_timeout 300s;
    proxy_read_timeout 300s;
    send_timeout 300s;
    
    # Upload size limit
    client_max_body_size 100M;
    client_body_timeout 300;
    
    # Session/Keep-alive
    keepalive_timeout 60s;
    
    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss;
    gzip_min_length 1000;
    
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;  # Adjust PHP version
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_connect_timeout 300;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

Reload Nginx:

```bash
sudo nginx -t                # Test config
sudo systemctl restart nginx # Restart
```

---

## 3. Laravel Backend Changes (Already Done ✓)

### Implemented in SertifikatController

✓ **Constructor with timeout configuration:**
```php
public function __construct()
{
    set_time_limit(300);                        // 5 minutes per upload
    ini_set('memory_limit', '256M');            // Increased memory
}
```

✓ **Session close during upload:**
```php
if (session()->isStarted()) {
    session()->save();
    session()->close();                         // Prevent session locking
}
```

✓ **Per-file processing:**
- Sequential upload (one file at a time)
- Each file: 10MB max
- Per-file validation & error reporting
- Partial success allowed (other files continue if one fails)

✓ **Better logging:**
```php
\Log::info("Upload complete", [
    'success_count' => count($ok),
    'skipped_count' => count($skipped),
    'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
    'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
]);
```

---

## 4. Frontend Changes (Already Done ✓)

✓ **Sequential upload architecture:**
- One file uploaded at a time (not all at once)
- Each file: own Fetch request
- Reduces memory spike & timeout risk

✓ **Improved error handling:**
```javascript
// 2 minute timeout per file
const timeoutId = setTimeout(() => {
    controller.abort();
}, 120000); // 120 seconds

// Detect timeout vs network error
if (error.name === 'AbortError') {
    // Show specific timeout message
}
```

✓ **Better error messages:**
- Detects 502/503/504 server errors
- Shows file size error (413)
- Shows request length error (414)
- Shows network/timeout error

---

## 5. Database Configuration

### For Session Storage

Jika menggunakan database untuk session, ensure table exists:

```bash
php artisan session:table    # Create session table
php artisan migrate          # Run migrations
```

---

## 6. Troubleshooting Checklist

### If still getting "Koneksi gagal" Error:

1. **Check PHP config:**
   ```bash
   php -i | grep -i timeout
   php -i | grep -i memory
   ```

2. **Check server logs:**
   ```bash
   # Apache
   tail -f /var/log/apache2/error.log
   
   # Nginx
   tail -f /var/log/nginx/error.log
   
   # PHP
   tail -f /var/log/php-fpm.log
   
   # Laravel
   tail -f storage/logs/laravel.log
   ```

3. **Check file permissions:**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 bootstrap/cache/
   ```

4. **Test upload directly:**
   - Open browser DevTools (F12)
   - Go to Network tab
   - Upload a file
   - Check Response headers for actual error
   - Check Console for JavaScript errors

5. **Monitor server resources during upload:**
   ```bash
   # CPU, memory, disk I/O
   top -u www-data
   
   # Disk write speed
   iostat -x 1
   ```

---

## 7. Performance Optimization

### A. Enable Caching

```env
CACHE_STORE=redis
```

Or use APCu:

```bash
php -m | grep apcu  # Check if installed
```

### B. Enable Gzip Compression

In `.env` or config:

```env
RESPONSE_CACHE_ENABLED=true
```

### C. Database Query Optimization

Already implemented: Sequential uploads + per-file transaction.

### D. Storage Optimization

Uploaded files stored in:
```
storage/app/public/sertifikat_photos/
```

Ensure folder exists and has proper permissions:

```bash
mkdir -p storage/app/public/sertifikat_photos
chmod -R 755 storage/app/public
```

Link storage to public:

```bash
php artisan storage:link
```

---

## 8. Monitoring & Debugging

### A. Enable Query Logging

Add to `.env`:

```env
DB_LOG_QUERIES=true
```

Then check `storage/logs/laravel.log` untuk slow queries.

### B. Monitor Upload Progress

Upload logs juga tersimpan di `storage/logs/laravel.log`:

```bash
tail -f storage/logs/laravel.log | grep "UPLOAD_MASSAL"
```

Shows:
- Request ID
- File count
- Success/skip count
- Memory usage
- Execution time

---

## 9. Summary Fixes untuk "Koneksi gagal"

| Issue | Solution | Status |
|-------|----------|--------|
| Timeout (30s default) | Increase to 300s | ✓ Done |
| Memory spike | Increase to 256M | ✓ Done |
| Session locking | Close session during upload | ✓ Done |
| Large file in one request | Sequential upload | ✓ Done |
| No error tracking | Add logging | ✓ Done |
| Frontend timeout not detected | Add AbortController | ✓ Done |

---

## 10. Quick Start Checklist

For new production deployment:

- [ ] Update `php.ini` (max_execution_time, memory_limit, post_max_size, upload_max_filesize)
- [ ] Configure web server (Apache timeout or Nginx proxy_read_timeout)
- [ ] Set `.env` to `APP_ENV=production`
- [ ] Run `php artisan migrate` (include session table)
- [ ] Test upload with multiple files
- [ ] Monitor logs during upload
- [ ] Set up log rotation for `storage/logs/`

---

## Additional Resources

- [Laravel File Uploads](https://laravel.com/docs/11.x/requests#files)
- [PHP Upload Configuration](https://www.php.net/manual/en/ini.file-uploads.php)
- [Apache TimeOut Directive](https://httpd.apache.org/docs/2.4/mod/core.html#timeout)
- [Nginx proxy_read_timeout](https://nginx.org/en/docs/http/ngx_http_proxy_module.html#proxy_read_timeout)
