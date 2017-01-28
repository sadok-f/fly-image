<img alt="Flyimglogo" src="https://raw.githubusercontent.com/flyimg/graphic-assets/master/logo/raster/flyimg-logo-rgb.png" width="300">
# Flyimg

[![Build Status](https://travis-ci.org/flyimg/flyimg.svg?branch=master)](https://travis-ci.org/flyimg/flyimg)
[![Code Climate](https://codeclimate.com/github/flyimg/flyimg/badges/gpa.svg)](https://codeclimate.com/github/flyimg/flyimg)
[![Issue Count](https://codeclimate.com/github/flyimg/flyimg/badges/issue_count.svg)](https://codeclimate.com/github/flyimg/flyimg)
[![Test Coverage](https://codeclimate.com/github/flyimg/flyimg/badges/coverage.svg)](https://codeclimate.com/github/flyimg/flyimg/coverage)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/89b18390-ac79-4c3e-bf6c-92cd9993e8d3/mini.png)](https://insight.sensiolabs.com/projects/89b18390-ac79-4c3e-bf6c-92cd9993e8d39)

Image resizing, cropping and compression on the fly with the impressive [MozJPEG](http://calendar.perfplanet.com/2014/mozjpeg-3-0) compression algorithm. A one Docker container to build your own Cloudinary-like service.

You pass the image URL and a set of keys with options, like size or compression. Flyimg will fetch the image, convert it, store it, cache it and serve it. The next time the request comes, it will serve the cached version.

The application is based on [Silex](http://silex.sensiolabs.org/) microframework.

# Installation and setup

## Requirements

You will need to have Docker on your machine. Optionally you can use Docker machine to create a virtual environment.

## Instalation

Create the project with `composer create` or clone it into your server.

```sh
composer create-project flyimg/flyimg
```

CD into the folder and to build the images run:

```sh
docker build -t flyimg .
```
This will download and build the main image, It will take a few minutes. If you get some sort of error related to files not found by apt-get or simmilar, try this same command again.

Then run the container:

```sh
docker run -t -d -i -p 8080:80 -v $(pwd):/var/www/html --name flyimg flyimg
```

For Fish shell users: 

```sh
docker run -t -d -i -p 8080:80 -v $PWD:/var/www/html --name flyimg flyimg
```

Dockerfile run supervisord command which lunch 2 process nginx and php-fpm

If you cloned the project, for the first time you need to run composer install inside the main container:

```sh
docker exec -it flyimg composer install
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

Currently, only the local and S3 are implemented as Storage Provider in Flyimg application, but you can add your specific one easily in `src/Core/Provider/StorageProvider.php` 

### Using AWS S3 as Storage Provider:

in parameters.yml change the `storage_system` option from local to s3, and fill in the aws_s3 options :

```yml
storage_system: s3

aws_s3:
  access_id: "s3-access-id"
  secret_key: "s3-secret-id"
  region: "s3-region"
  bucket_name: "s3-bucket-name"
```

Options keys:
-------------

```yml
options_keys:
  moz: mozjpeg
  q: quality
  unsh: unsharp
  fc: face-crop
  fcp: face-crop-position
  fb: face-blur
  w: width
  h: height
  c: crop
  bg: background
  st: strip
  rz: resize
  g: gravity
  f: filter
  r: rotate
  sc: scale
  sf: sampling-factor
  rf: refresh
  ett: extent
  par: preserve-aspect-ratio
  pns: preserve-natural-size
  webp: webp-support
  webpl: webp-lossless
```

Default options values:
-----------------------

```yml
default_options:
  mozjpeg: 1
  quality: 90
  unsharp: null
  face-crop: 0
  face-crop-position: 0
  face-blur: 0
  width: null
  height: null
  crop: null
  background: null
  strip: 1
  resize: null
  gravity: Center
  filter: Lanczos
  rotate: null
  scale: null
  sampling-factor: 1x1
  refresh: false
  extent: null
  preserve-aspect-ratio: 1
  preserve-natural-size: 1
  webp-support: 1
  webp-lossless: 1
```

Most of these options are ImageMagick flags, many can get pretty advanced, use the [ImageMagick docs](http://www.imagemagick.org/script/command-line-options.php).

### mozjpeg `bool`
**default: 1** : Use moz-jpeg compression library, if `false` it fallback to the default ImageMagick compression algorithm.

**example:`moz_0`** 

`moz_0` 

[![moz_0](http://oi.flyimg.io/upload/moz_0/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/moz_0/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)
 
`moz_1` 

[![moz_0](http://oi.flyimg.io/upload/moz_1/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/moz_1/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)


### quality `int` (0-100)
**default: 90** : Sets the compression level for the output image.

**example:`q_100`,`q_75`,...** 

`q_30` 

[![moz_0](http://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

`q_100` 

[![moz_0](http://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)


### width `int`
**default: null** : Sets the target width of the image. If not set, width will be calculated in order to keep aspect ratio.

**example:`w_100`** 

`w_100` 

[![moz_0](http://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### height `int`
**default: null** : Sets the target height of the image. If not set, height will be calculated in order to keep aspect ratio.

**example:`h_100`** 

`h_100`
 
[![moz_0](http://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### Using width AND height

**example:`h_300,w_300`** 
By default setting width and height together, works like defining a rectangle that will define a **max-width** and **max-height** and the image will scale propotionally to fit that area without cropping.
<!-- in the future put example images here-->

By default; width, height, or both will **not scale up** an image that is smaller than the defined dimensions.
<!-- in the future put example images here-->

`h_300,w_300` 

[![moz_0](http://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### crop `bool` 
**default: false** : When both width and height are set, this allows the image to be cropped so it fills the **width x height** area.

**example:`c_1`** 

`c_1,h_400,w_400` 

[![moz_0](http://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)


### gravity `string`
**default: Center** : When crop is applied, changing the gravity will define which part of the image is kept inside the crop area.
The basic options are: `NorthWest`, `North`, `NorthEast`, `West`, `Center`, `East`, `SouthWest`, `South`, `SouthEast`.

**example:`g_West`** 

```sh
   [...] -gravity NorthWest ...

```

### background `color` (multiple formats) 
**default: white** : Sets the background of the canvas for the cases where padding is added to the images. It supports hex, css color names, rgb. 
Only css color names are supported without quotation marks.
For the hex code, the hash `#` character should be replaced by `%23` 

**example:`bg_red`,`bg_%23ff4455`,`bg_rgb(255,120,100)`,...** 

```sh
  [...] -background red ...
  [...] -background "#ff4455" -> "%23ff4455"
  [...] -background "rgb(255,120,100)" ...
```

[![bg_red](http://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### strip `int`
**default: 1** : removes exif data and additional color profile.

**example:`st_1`** 

### resize `int`
**default: null** : The alternative resizing method to -thumbnail.

**example:`rz_1`** 

### unsharp `radiusxsigma{+gain}{+threshold}` 
**default: null** : Sharpens an image with a convolved Gausian operator. A good example `0.25x0.25+8+0.065`.

**example:`unsh_0.25x0.25+8+0.065`** 

```sh
   [...] -unsharp 0.25x0.25+8+0.065 ...
```

### filter `string`
**default: Lanczos** : Resizing algorithm, Triangle is a smoother lighter option

**example:`f_Triangle`** 

```sh
   [...] -filter Triangle
```

### scale `int`
**default: null** : The "-scale" resize operator is a simplified, faster form of the resize command. Useful for fast exact scaling of pixels.

**example:`sc_1`** 


### rotate `string`
**default: null** : Apply image rotation (using shear operations) to the image. 

**example: `r_90`, `r_-180`,...**

`r_45` 

[![moz_0](http://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](http://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

 
### refresh `int`
**default: false** : Refresh will delete the local cached copy of the file requested and will generate the image again. 
Also it will send headers with the command done on the image + info returned by the command identity from IM.

**example:`rf_1`** 

 
### Face Crop `int`
**default: false** : Using [facedetect](https://github.com/wavexx/facedetect) repository to detect faces and passe the coordinates to ImageMagick to crop.

**example:`fc_1`** 

`fc_1` 

[![moz_0](http://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg)](http://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg)

 
### Face Crop Position `int`
**default: false** : When using the Face crop option and when the image contain more than one face, you can specify which one you want get cropped

**example:`fcp_1`,`fcp_0`,...** 

`fcp_2` 

[![moz_0](http://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg)](http://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg)

 
### Face Blur `int`
**default: false** : Apply blur effect on faces in a given image

**example:`fb_1`** 

`fb_1` 

[![moz_0](http://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg)](http://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg)


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

Test:
-----
```sh
docker exec -it flyimg ./vendor/bin/phpunit
```

How to Provision the application on:
-----------------------------------
- [DigitalOcean](https://github.com/flyimg/DigitalOcean-provision)
- [AWS Elastic-Beanstalk](https://github.com/flyimg/Elastic-Beanstalk-provision)


Demo running Application:
-------------------------

[oi.flyimg.io](http://oi.flyimg.io)


Enjoy !
