In laradock/nginx/sites folder add testing.conf file.
```nginx
server {

    listen 81;
    listen [::]:81;

    server_name testing;

    root /var/www/dist;

    location / {
        root /var/www/dist;
        try_files $uri $uri/;
    }

     location /tutorial {
        alias /var/www/dist;
        try_files $uri $uri/ @tutorial;
    }

    location @tutorial {
        rewrite /tutorial/(.*)$ /tutorial/index.php?/$1 last;
    }
}
```

Allow to listen port 81 in the section ### NGINX Server ### in laradock/docker-compose.yml
```yaml
- "${NGINX_HOST_HTTP_PORT}:80"
- "81:81"
```        
In vendor/spatie/laravel-export/config/export.php add a hook.

```PHP
    'after' => [
        // 'deploy' => '/usr/local/bin/netlify deploy --prod',
        'cp -r dist/tutorial/* dist && rm -r dist/tutorial/'
    ],
```

To export to static website run the following command into workspace:
```bash
php artisan export
```

Test the application in `http://localhost:81/tutorial/`

To export to static website run:

```bash
sudo bash copy-submodule-tutorial.sh
```
