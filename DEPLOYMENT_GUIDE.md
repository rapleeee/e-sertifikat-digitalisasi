# Production Deployment & Setup Guide

## Overview

Panduan lengkap untuk deploy dan configure aplikasi e-certifikat di production environment agar upload fitur berfungsi optimal tanpa "Koneksi gagal" error.

---

## Step 1: Prepare Production Server

### 1.1 Update Server

```bash
sudo apt-get update && sudo apt-get upgrade -y
```

### 1.2 Install Required Extensions

```bash
# PHP extensions untuk file handling
sudo apt-get install php-gd php-xml php-zip php-curl php-json php-mbstring -y

# Web server (pilih salah satu)
# Untuk Apache
sudo apt-get install apache2 libapache2-mod-php -y

# Atau untuk Nginx + PHP-FPM
sudo apt-get install nginx php-fpm -y
```

### 1.3 Create Database

```bash
mysql -u root -p

# Create database
CREATE DATABASE e_certifikat;
CREATE USER 'e_cert_user'@'localhost' IDENTIFIED BY 'securePassword123!';
GRANT ALL PRIVILEGES ON e_certifikat.* TO 'e_cert_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Step 2: Deploy Application Code

### 2.1 Clone Repository

```bash
# CD ke web root
cd /var/www

# Clone ke folder aplikasi
git clone <your-repository-url> e-certifikat
cd e-certifikat
```

### 2.2 Set Permissions

```bash
# Set proper permissions untuk Laravel
sudo chown -R www-data:www-data /var/www/e-certifikat
sudo find /var/www/e-certifikat -type f -exec chmod 644 {} \;
sudo find /var/www/e-certifikat -type d -exec chmod 755 {} \;

# Storage & bootstrap cache (must be writable)
sudo chmod -R 775 /var/www/e-certifikat/storage
sudo chmod -R 775 /var/www/e-certifikat/bootstrap/cache
```

### 2.3 Install Dependencies

```bash
cd /var/www/e-certifikat

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install NPM dependencies (untuk frontend assets)
npm install && npm run build
```

---

## Step 3: Configure Laravel Environment

### 3.1 Create .env File

```bash
cp .env.example .env
```

Edit `.env` dengan production settings:

```env
# Application
APP_NAME=E-Certifikat
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxx...xxx  # Generate: php artisan key:generate
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_certifikat
DB_USERNAME=e_cert_user
DB_PASSWORD=securePassword123!

# Cache & Session
CACHE_STORE=database
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false

# Mail (configure as needed)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

### 3.2 Generate App Key

```bash
php artisan key:generate
```

### 3.3 Run Migrations

```bash
php artisan migrate --force
```

Create session table (jika belum):

```bash
php artisan session:table
php artisan migrate
```

---

## Step 4: Configure PHP (Critical for Uploads)

### 4.1 Edit php.ini

Locate php.ini:

```bash
# Find PHP config file location
php -r "phpinfo();" | grep "Loaded Configuration File"

# Common locations:
# /etc/php/8.2/apache2/php.ini (Apache)
# /etc/php/8.2/fpm/php.ini (Nginx FPM)
```

Edit dengan command:

```bash
sudo nano /etc/php/8.2/apache2/php.ini  # Or FPM version
```

Update values (cari dengan Ctrl+W, edit dengan Ctrl+X Y):

```ini
; Max execution time untuk long uploads
max_execution_time = 300

; Input processing time
max_input_time = 300

; Memory limit (process files besar)
memory_limit = 256M

; POST request size limit
post_max_size = 100M

; Individual file size limit
upload_max_filesize = 100M

; Upload directory (pastikan exist dan writable)
upload_tmp_dir = /tmp

; Session settings
session.gc_maxlifetime = 1440
session.save_path = /var/lib/php/sessions
```

Verify permissions temporary directory:

```bash
ls -ld /tmp
chmod 1777 /tmp  # Jika diperlukan
```

### 4.2 Verify Configuration

```bash
php -i | grep -E "max_execution_time|memory_limit|post_max_size|upload_max_filesize"
```

Expected:
```
max_execution_time => 300 => 300
memory_limit => 256M => 256M
post_max_size => 100M => 100M
upload_max_filesize => 100M => 100M
```

---

## Step 5: Configure Web Server

### 5.1A - Apache Configuration

Enable required modules:

```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod expires
```

Create/Edit virtual host file:

```bash
sudo nano /etc/apache2/sites-available/e-certifikat.conf
```

Content:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    ServerAdmin admin@your-domain.com

    DocumentRoot /var/www/e-certifikat/public

    <Directory /var/www/e-certifikat>
        AllowOverride All
        Require all granted
    </Directory>

    # Timeouts untuk long uploads
    TimeOut 300

    # Logs
    ErrorLog ${APACHE_LOG_DIR}/e-certifikat_error.log
    CustomLog ${APACHE_LOG_DIR}/e-certifikat_access.log combined

    # SSL (gunakan Let's Encrypt untuk production!)
    # SSLEngine on
    # SSLCertificateFile /etc/letsencrypt/live/your-domain.com/fullchain.pem
    # SSLCertificateKeyFile /etc/letsencrypt/live/your-domain.com/privkey.pem
</VirtualHost>
```

Enable site:

```bash
sudo a2ensite e-certifikat
sudo a2dissite 000-default
sudo apache2ctl configtest  # Should output "Syntax OK"
sudo systemctl restart apache2
```

### 5.1B - Nginx Configuration

Create/Edit server block:

```bash
sudo nano /etc/nginx/sites-available/e-certifikat
```

Content:

```nginx
upstream php_backend {
    server unix:/run/php/php8.2-fpm.sock;
}

server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/e-certifikat/public;
    index index.php index.html;

    # Timeouts untuk long uploads
    client_max_body_size 100M;
    proxy_connect_timeout 300s;
    proxy_send_timeout 300s;
    proxy_read_timeout 300s;
    send_timeout 300s;
    
    client_body_timeout 300;
    keepalive_timeout 60s;

    # Gzip compression
    gzip on;
    gzip_types text/css application/javascript application/json text/xml;
    gzip_min_length 1000;

    location ~ /\.ht {
        deny all;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php_backend;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_connect_timeout 300;
        include fastcgi_params;
    }

    # SSL (uncomment for HTTPS)
    # listen 443 ssl http2;
    # ssl_certificate /etc/letsencrypt/live/your-domain.com/fullchain.pem;
    # ssl_certificate_key /etc/letsencrypt/live/your-domain.com/privkey.pem;
    # ssl_protocols TLSv1.2 TLSv1.3;
    # ssl_ciphers HIGH:!aNULL:!MD5;
}
```

Enable site:

```bash
sudo ln -s /etc/nginx/sites-available/e-certifikat /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t  # Should output "successful"
sudo systemctl restart nginx
```

---

## Step 6: Setup SSL Certificate (HTTPS)

### 6.1 Install Certbot

```bash
sudo apt-get install certbot python3-certbot-apache -y  # Apache
# atau
sudo apt-get install certbot python3-certbot-nginx -y   # Nginx
```

### 6.2 Get Certificate

```bash
# Apache
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# Nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 6.3 Auto Renew

```bash
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

---

## Step 7: Setup Log Rotation

```bash
sudo nano /etc/logrotate.d/e-certifikat
```

Content:

```
/var/www/e-certifikat/storage/logs/*.log {
    daily
    missingok
    rotate 7
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
}

/var/log/apache2/e-certifikat*.log {
    daily
    rotate 7
    compress
    delaycompress
    notifempty
}
```

---

## Step 8: Setup Cron Jobs

```bash
sudo crontab -e -u www-data
```

Add:

```cron
# Laravel task scheduler
* * * * * cd /var/www/e-certifikat && php artisan schedule:run >> /dev/null 2>&1

# Session cleanup
0 */6 * * * /usr/lib/php/sessionclean
```

---

## Step 9: Test & Verify

### 9.1 Test Application

```bash
# Check Laravel app
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run health check
php artisan tinker
>> echo 'OK';
```

### 9.2 Test Upload

Buka browser, navigate ke aplikasi, test upload file:

1. Single file upload (10MB)
2. Multiple files upload (5 files × 8MB)
3. PDF upload
4. Monitor storage space

### 9.3 Check Logs

```bash
# Laravel logs
tail -f /var/www/e-certifikat/storage/logs/laravel.log

# Web server logs
tail -f /var/log/apache2/e-certifikat_error.log  # Apache
tail -f /var/log/nginx/error.log                  # Nginx

# PHP FPM logs (Nginx)
tail -f /var/log/php8.2-fpm.log
```

### 9.4 Monitor Resources

```bash
# CPU dan memory
top

# Disk usage
df -h
du -sh /var/www/e-certifikat/storage

# File descriptors
lsof | grep www-data | wc -l
```

---

## Step 10: Production Checklist

- [ ] `.env` configured untuk production
- [ ] APP_KEY generated
- [ ] APP_DEBUG = false
- [ ] Database migrated
- [ ] File permissions correct (775 untuk storage/bootstrap)
- [ ] php.ini updated (max_execution_time, memory_limit, post_max_size)
- [ ] Web server configured (timeout settings)
- [ ] SSL certificate installed dan working
- [ ] Logs rotation configured
- [ ] Cron jobs active
- [ ] Backup strategy planned
- [ ] Firewall configured (port 80, 443)
- [ ] Upload folder have enough disk space
- [ ] Session cleanup scheduled

---

## Troubleshooting

### Upload Still Failing?

1. **Check current config:**
   ```bash
   php -i | grep -i timeout
   php -i | grep -i memory
   php -i | grep -i upload
   ```

2. **Check disk space:**
   ```bash
   df -h
   du -sh /var/www/e-certifikat/storage
   ```

3. **Check permissions:**
   ```bash
   ls -la /var/www/e-certifikat/storage/
   ls -la /tmp
   ```

4. **Restart PHP:**
   ```bash
   sudo systemctl restart php8.2-fpm  # Nginx
   sudo systemctl restart apache2     # Apache
   ```

5. **Clear cache:**
   ```bash
   cd /var/www/e-certifikat
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

### "Koneksi gagal" Still Appears?

1. Check server logs untuk timeout messages
2. Verify `max_execution_time` is 300
3. Verify `memory_limit` is minimum 256M
4. Check upload file size (must be < 10MB each)
5. Monitor network connectivity
6. Try uploading smaller files first

---

## Performance Optimization

### Database Optimization

```bash
# Optimize database
php artisan db:optimize

# Check slow queries
tail -f /var/log/mysql/slow.log
```

### Cache Optimization

```bash
# Use Redis (if available)
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Storage Optimization

```bash
# Setup symbolic link
php artisan storage:link

# Verify
ls -la /var/www/e-certifikat/public/
# Should see: storage -> ../storage/app/public
```

---

## Monitoring & Maintenance

### Daily Checks

```bash
# Check disk space
df -h

# Check error logs
tail -20 /var/www/e-certifikat/storage/logs/laravel.log

# Check web server logs
tail -20 /var/log/apache2/e-certifikat_error.log
```

### Weekly Tasks

```bash
# Database backup
mysqldump -u e_cert_user -p e_certifikat > backup_$(date +%Y%m%d).sql

# Check system updates
sudo apt list --upgradable
```

---

## Additional Resources

- [Laravel Deployment](https://laravel.com/docs/11.x/deployment)
- [PHP Configuration Guide](https://www.php.net/manual/en/ini.php)
- [Apache Timeout Settings](https://httpd.apache.org/docs/2.4/mod/core.html#timeout)
- [Nginx Proxy Timeouts](https://nginx.org/en/docs/http/ngx_http_proxy_module.html#proxy_read_timeout)
