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

To export to static website run:

```bash
sudo bash copy-submodule-tutorial.sh
```
