# 🚀 QS3 (Quick & Simple Storage Service) 📁

QS3 is a small and lightweight web app written in PHP. The reason for choosing PHP was simple but crucial. It works everywhere, from free hosting to shared hosting to Cloud VMs (i.e. EC2 etc.), and it is very simple.

## ⚙️ Setup Instructions

1. 🛠️ **Environment Configuration**

   Configure the following options in your `.env` file at the root of your project:

   ```env    
    
    TARGET_DIR=uploads/                  # Directory where the uploaded files will be stored
    MAX_FILE_SIZE=104857600              # Maximum file size for uploads (in bytes)
    BASE_URL=https://sample-url.com/     # Sample Base URL of the application
    API_KEY=SAMPLE_API_KEY_12345         # Sample API key for authentication
    ```

2. 🌐 **Server Configuration**

    The project assumes you are using an Apache server. If not, please configure things yourself accordingly.
    Use the .htaccess file and .user.ini to configure the project according to your needs. Ensure the uploads folder is not accessible from the URL.

3. 📝 **.htaccess Configuration**

    Here's an example of what your .htaccess file might look like:
    
    ```apache
    # PHP Settings
    php_value display_errors On
    php_value mbstring.http_input auto
    php_value date.timezone America/New_York
    
    # Prevent directory listing
    Options -Indexes
    
    <IfModule mod_rewrite.c>
        RewriteEngine On
        
        # Allow Authorization Header in HTTP
        RewriteCond %{HTTP:Authorization} ^(.*)
        RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
        
        # Force HTTPS (Only enable if you have SSL setup)
        # RewriteCond %{HTTPS} !=on
        # RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
        
        # Disable access to uploads/ and returns a 404 error
        RewriteRule ^uploads/(.*)$ - [R=404,L]
        </IfModule>    
        
        # Allow access only for index.php
        <Files "index.php">
            Allow from all
        </Files>
    ```
4. 🔧 **.user.ini Configuration**

    Here's an example of what your .user.ini file might look like:

    ```ini
    upload_max_filesize = 200M
    post_max_size = 200M
    error_reporting = E_ALL | E_WARNING
    memory_limit = 768M
    max_execution_time = 360
    max_input_vars = 600
    display_errors = on
    ```
## ⚠️ Important Note

Be mindful of the constraints when using free hosting services. They often come with certain limitations such as unmodifiable upload size limits. Additionally, they might only support basic HTTP methods like GET and POST. However, you can still define a variety of other methods such as DELETE, PUT, and PATCH using POST. Stay alert and adapt accordingly!

## 🤝 Contribute

Contributions are always welcome from the community. Don't hesitate to open an issue or submit a pull request!

## 📄 License

QS3 is licensed under the [BSD-4-Clause](https://en.wikipedia.org/wiki/BSD_licenses). See [LICENSE](./LICENSE) for more information.

## 🔗 Helpful References
- https://stackoverflow.com/questions/70211070/what-is-the-difference-between-php-ini-and-user-ini-in-each-folder
- https://www.php.net/manual/en/configuration.file.per-user.php
- https://www.pair.com/support/kb/php-ini-vs-user-ini/