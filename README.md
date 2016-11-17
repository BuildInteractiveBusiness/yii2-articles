Articles
===================


Installation
------------
#### 1. Install Repository
You can install via [composer](http://getcomposer.org/download/).

Run
```
php composer.phar require --prefer-dist "robot72/yii2-articles" "dev-master"
```

or add to require section of `composer.json:`

```
"robot72/yii2-articles": "dev-master"
```

#### 2. Edit your config files
#####2.1. Check Database Connection Params
Check your `config/db.php` file. Have correct parameters?
#####2.2. Install migrations
`php yii migrate/up --migrationPath=@vendor/robot72/yii2-articles/migrations`

#####2.3. Edit `config/web.php:` file

```
/** Example Articles module config */
'articles' => [
    
],
```

#####2.4 Test
http://YOUR_LOCAL_SERVER/articles