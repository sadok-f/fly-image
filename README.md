# Fly-Image

[![Build Status](https://travis-ci.org/sadok-f/fly-image.svg?branch=master)](https://travis-ci.org/sadok-f/fly-image)
[![Code Climate](https://codeclimate.com/github/sadok-f/fly-image/badges/gpa.svg)](https://codeclimate.com/github/sadok-f/fly-image)
[![Issue Count](https://codeclimate.com/github/sadok-f/fly-image/badges/issue_count.svg)](https://codeclimate.com/github/sadok-f/fly-image)
[![Test Coverage](https://codeclimate.com/github/sadok-f/fly-image/badges/coverage.svg)](https://codeclimate.com/github/sadok-f/fly-image/coverage)

Image resizing, cropping and compression on the fly with the impressive [MozJPEG](http://calendar.perfplanet.com/2014/mozjpeg-3-0) compression algorithm. A one Docker container to build your own Cloudinary-like service.

You pass the image URL and a set of keys with options, like size or compression. Fly-image will fetch the image, convert it, store it, cache it and serve it. The next time the request comes, it will serve the cached version.

The application is based on [Silex](http://silex.sensiolabs.org/) microframework.

# Installation and setup

## Requirements

You will need to have Docker on your machine. Optionally you can use Docker machine to create a virtual environment.

## Instalation

Create the project with `composer create` or clone it into your server.

```sh
    $ composer create-project sadok-f/fly-image
```

CD into the folder and to build the images run:

```sh
    $ docker build -t fly-img .
```
This will download and build the main image, It will take a few minutes. If you get some sort of error related to files not found by apt-get or simmilar, try this same command again.

Then run the container:

```sh
    $ docker run -t -d -i -p 8080:80 -v /Users/s.ferjani/DockerProjects/flyimage:/var/www/html --name fly-img fly-img
```

Dockerfile run supervisord command which lunch 2 process nginx and php-fpm

Now, only for the first time you need to run composer install inside the main container:

```sh
    $ docker exec -it fly-img composer install
```


Again, it will take a few minutes. Same as before, if you get some errors you should try running `composer install` again. After it's done, you can navigate to your machine's IP in port 8080 (ex: http://192.168.99.100:8080/ ) an you should get a message saying: **Hello from Docker!**. This means fpm is ready to work.

You can test your image resizing service by navigating to: http://127.0.0.1:8080/upload/w_333,h_333,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg

This is fetching an image from Mozilla, resizing it, saving it and serving it.

More configuration details below.

---

Storage:
--------
Storage files based on [Flysystem](http://flysystem.thephpleague.com/) which is `a filesystem abstraction allows you to easily swap out a local filesystem for a remote one. Technical debt is reduced as is the chance of vendor lock-in.`

Default storage is Local, but you can use other Adapters like AWS S3, Azure, FTP, Dropbox, ... 


Options keys:
-------------

```yml
options_keys:
  moz: mozjpeg
  q: quality
  unsh: unsharp
  w: width
  h: height
  c: crop
  bg: background
  st: strip
  rz: resize
  g: gravity
  th: thread
  thb: thumbnail
  f: filter
  sc: scale
  sf: sampling-factor
  rf: refresh
```

Default options values:
-----------------------

```yml
default_options:
  mozjpeg: 1
  quality: 90
  unsharp: null
  width: null
  height: null
  crop: null
  background: null
  strip: 1
  resize: null
  gravity: Center
  thread: 1
  thumbnail: null
  filter: Lanczos
  scale: null
  sampling-factor: 1x1
  refresh: false
```
Most of these options are Imagemagick flags, many can get pretty advanced, use the [Imagemagick docs](http://www.imagemagick.org/script/command-line-options.php).

### mozjpeg `bool`
**default: 1** - Use moz-jpeg compression library, if `false` it fallsback to the default Imagemagick compression algorithm. 


### quality `int` (0-100)
**default: 90** - Sets the compression level for the output image.

### unsharp `radiusxsigma{+gain}{+threshold}` 
**default: null** - Sharpens an image with a convolved Gausian operator. A good example `0.25x0.25+8+0.065`.
```sh
   [...] -unsharp 0.25x0.25+8+0.065 ...
```

### width `int` 
**default: null** - blah_bla_blah
```sh
   hhhh -width smthing ...
```

### height `int` 
**default: null** - blah_bla_blah
```sh
   hhhh -height smthing ...
```

### crop `int` 
**default: null** - blah_bla_blah
```sh
   hhhh -crop smthing ...
```

### background `int` 
**default: null** - blah_bla_blah
```sh
   hhhh -background smthing ...
```

### strip `int` 
**default: 1** - blah_bla_blah
```sh
   hhhh -strip smthing ...
```

### resize `int` 
**default: null** - blah_bla_blah
```sh
   hhhh -resize smthing ...
```

### gravity `int` 
**default: Center** - blah_bla_blah
```sh
   hhhh -gravity smthing ...
```

### thread `int` 
**default: 1** - blah_bla_blah
```sh
   hhhh -thread smthing ...
```

### thumbnail `int` 
**default: null** - blah_bla_blah
```sh
   hhhh -thumbnail smthing ...
```

### filter `int` 
**default: Lanczos** - Resizing algorithm, Triangle is a smoother lighter option
```sh
   [...] -filter Triangle
```

### scale `int` 
**default: null** - blah_bla_blah
```sh
   hhhh -scale smthing ...
```

### sampling `int` 
**default-factor: 1x1** - blah_bla_blah
```sh
   hhhh -sampling smthing ...
```

### refresh `int` 
**default: false** - blah_bla_blah
```sh
   hhhh -refresh smthing ...
```



Example of using AWS S3 Adapter:
--------------------------------
in app.php:

```php
$s3Client = \Aws\S3\S3Client::factory([
        'credentials' => [
            'key'    => 'your-key',
            'secret' => 'your-secret',
        ],
        'region' => 'your-region',
        'version' => 'latest|version',
    ]);

$app->register(new WyriHaximus\SliFly\FlysystemServiceProvider(), [
    'flysystem.filesystems' => [
        'upload_dir' => [
            'adapter' => 'League\Flysystem\AwsS3v3\AwsS3Adapter',
            'args' => [
                $s3Client,
                'your-bucket-name'
            ]
        ]
    ]
]);
```
 

Enable Restricted Domains:
--------------------------

Restricted domains disabled by default. This means that you can fetch a resource from any URL. To enable the domain restriction, change in config/parameters.yml 
```yml
restricted_domains: true
```

After enabling, you need to put the white listed domains
```yml
whitelist_domains:
    - www.domain-1.org
    - www.domain-2.org
```

Demo:
-----
restricted_domains is activated, only images from www.mozilla.org domain is accepted

- Quality 90%
http://176.31.121.161:8080/upload/w_500,h_500,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg

- Quality 10%
http://176.31.121.161:8080/upload/w_500,h_500,q_10/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg

