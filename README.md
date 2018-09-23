
<p align="center"><a href="http://flyimg.io" target="_blank">
    <img alt="Flyimglogo" src="https://raw.githubusercontent.com/flyimg/graphic-assets/master/logo/raster/flyimg-logo-rgb.png" width="300">
</a></p>

<p align="center">
<a href="#backers"><img alt="Backers on Open Collective" src="https://opencollective.com/flyimg/backers/badge.svg"></a>
<a href="#sponsors"><img alt="Sponsors on Open Collective" src="https://opencollective.com/flyimg/sponsors/badge.svg"></a>
<a href="https://travis-ci.org/flyimg/flyimg"><img alt="Build Status" src="https://travis-ci.org/flyimg/flyimg.svg?branch=master"></a>
<a href="https://codeclimate.com/github/flyimg/flyimg"><img alt="Code Climate" src="https://codeclimate.com/github/flyimg/flyimg/badges/gpa.svg"></a>
<a href="https://codeclimate.com/github/flyimg/flyimg"><img alt="Issue Count" src="https://codeclimate.com/github/flyimg/flyimg/badges/issue_count.svg"></a>
<a href="https://codeclimate.com/github/flyimg/flyimg/coverage"><img alt="Test Coverage" src="https://codeclimate.com/github/flyimg/flyimg/badges/coverage.svg"></a>
<a href="https://insight.sensiolabs.com/projects/89b18390-ac79-4c3e-bf6c-92cd9993e8d3"><img alt="SensioLabsInsight" src="https://insight.sensiolabs.com/projects/89b18390-ac79-4c3e-bf6c-92cd9993e8d3/mini.png"></a>
<a href="https://packagist.org/packages/flyimg/flyimg"><img alt="License" src="https://poser.pugx.org/flyimg/flyimg/license.svg"></a>
<a href="https://packagist.org/packages/flyimg/flyimg"><img alt="Latest Stable Version]" src="https://poser.pugx.org/flyimg/flyimg/v/stable.svg"></a>
</p>

# Flyimg

Image resizing, cropping and compression on the fly with the impressive [MozJPEG](http://calendar.perfplanet.com/2014/mozjpeg-3-0) compression algorithm. One Docker container to build your own Cloudinary-like service.

### Fetch an image from anywhere; resize, compress, cache and serve...<small> and serve, and serve, and serve...</small>

You pass the image URL and a set of keys with options, like size or compression. Flyimg will fetch the image, convert it, store it, cache it and serve it. The next time the request comes, it will serve the cached version.

```
<!-- https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg -->
<img src="https://www.myservice.io/upload/w_333,h_333,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg">
```
# Basic Usage Examples
## Get an image to fill exact dimensions
* Image: `http://medula.cl/t/resize-test_1920.jpg` 
* Width: 300
* Height: 250
* Crop if necesary: `c_1`

https://oi.flyimg.io/upload/w_300,h_250,c_1/http://medula.cl/t/resize-test_1920.jpg

![lago_ranco](https://oi.flyimg.io/upload/w_300,h_250,c_1/http://medula.cl/t/resize-test_1920.jpg)

This will serve the image.

## Get the path to the generated image instead of serving it
Change the first part of the path from `upload` to `path`, like so:

https://oi.flyimg.io/path/w_300,h_250,c_1/http://medula.cl/t/resize-test_1920.jpg will output in the body of the response:


```
http://localhost:8080/uploads/752d2124eef87b3112779618c96468da.jpg
```

## Get an image to fit maximum dimensions
* Image: `http://medula.cl/t/resize-test_1920.jpg` 
* Width: 300
* Height: 250
* Note that we ommit the crop parameter

https://oi.flyimg.io/upload/w_300,h_250/http://medula.cl/t/resize-test_1920.jpg

![lago_ranco](https://oi.flyimg.io/upload/w_300,h_250/http://medula.cl/t/resize-test_1920.jpg)

## Crop to a square and rotate 90 degrees clockwise
* Image: `http://medula.cl/t/resize-test_1920.jpg` 
* Width: 200
* Height: 200
* Crop: `c_1`
* Rotate: 90

https://oi.flyimg.io/upload/w_200,h_200,c_1,r_90/http://medula.cl/t/resize-test_1920.jpg

![lago_ranco](https://oi.flyimg.io/upload/w_200,h_200,c_1,r_90/http://medula.cl/t/resize-test_1920.jpg)

## Get an image with exact dimensions and low quality
* Image: `http://medula.cl/t/resize-test_1920.jpg` 
* Width: 200
* Height: 200
* Crop: `c_1`
* Quality: 30

https://oi.flyimg.io/upload/w_200,h_200,c_1,q_30/http://medula.cl/t/resize-test_1920.jpg

![lago_ranco](https://oi.flyimg.io/upload/w_200,h_200,c_1,q_30/http://medula.cl/t/resize-test_1920.jpg)


# Table of Contents

   * [Requirements](#requirements)
   * [Installation [Deployment mode]](#installation-deployment-mode)
   * [Installation [Development Mode]](#installation-development-mode)
      * [Installation](#installation)
         * [with git](#with-git)
         * [with composer](#with-composer)
   * [Testing Flyimg service](#testing-flyimg-service)
   * [How to transform images](#how-to-transform-images)
   * [Basic Option details](#basic-option-details)
   * [Application Server Options](#server-options)
   * [Security: Restricting Source Domains](#security-restricting-source-domains)
   * [Security: Signature Generation](#security-signature-generation)
   * [Run Unit Tests](#run-unit-tests)
   * [How to Provision the application on](#how-to-provision-the-application-on)
   * [Technology stack](#technology-stack)
      * [Abstract storage with Flysystem](#abstract-storage-with-flysystem)
   * [Benchmark](#benchmark)
   * [Enable Xdebug](https://github.com/flyimg/flyimg/blob/master/docs/enabling-xdebug.md)
   * [Demo Application running](#demo-application-running)
   * [Roadmap](#roadmap)
   * [Community](#community)
   * [Supporters](#supporters)
   * [Contributors](#contributors)
   * [Backers](#backers)
   * [Sponsors](#sponsors)
   * [License](#license)
   
   
# Requirements

You will need to have **Docker** on your machine. Optionally you can use Docker machine to create a virtual environment. We have tested on **Mac**, **Windows** and **Ubuntu**.

# Installation [Deployment mode]

Pull the docker image

```bash
docker pull flyimg/flyimg-build
```

Start the container

```bash
docker run -itd -p 8080:80 flyimg/flyimg-build
```
Check [how to provision the application](#how-to-provision-the-application-on)

# Installation [Development Mode]

You can spin up your own working server in 10 minutes using the provision scripts for [AWS Elastic Beanstalk](https://github.com/flyimg/Elastic-Beanstalk-provision) or the [DigitalOcean Ubuntu Droplets](https://github.com/flyimg/DigitalOcean-provision) <small>(more environments to come)</small>. For other environments or if you want to tweak and play in your machine before rolling out, read along...

## Installation

You can use `git` or `composer` for the first step. 

### with git

```sh
git clone https://github.com/flyimg/flyimg.git
```
### with composer
Create the project with `composer create` .

```sh
composer create-project flyimg/flyimg
```

**CD into the folder** and to build the docker image by running:

```sh
docker build -t flyimg .
```
This will download and build the main image, It will take a few minutes. If you get some sort of error related to files not found by apt-get or similar, try this same command again.

Then run the container:

```sh
docker run -itd -p 8080:80 -v $(pwd):/var/www/html --name flyimg flyimg
```

For Fish shell users: 

```sh
docker run -itd -p 8080:80 -v $PWD:/var/www/html --name flyimg flyimg
```

The above command will make the Dockerfile run supervisord command which launches 2 processes: **nginx** and **php-fpm** and starts listening on port 8080.

**IMPORTANT!** If you cloned the project, only for the first time, you need to run `composer install` **inside** the container:

```sh
docker exec -it flyimg composer install
```

Again, it will take a few minutes to download the dependencies. Same as before, if you get some errors you should try running `composer install` again.
 

# Testing Flyimg service

You can navigate to your machine's IP in port 8080 (ex: http://127.0.0.1:8080/ ) ; you should get a message saying: **Hello from Flyimg!** and a small homepage of Flyimg already working. If you get any errors  at this stage it's most likely that composer has not finished installing or skipped something.

You can test your image resizing service by navigating to: http://127.0.0.1:8080/upload/w_130,h_113,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg

![ff-logo](https://oi.flyimg.io/upload/w_130,h_113,q_90/https://www.mozilla.org/media/img/firefox/firefox-256.e2c1fc556816.jpg)

**It's working!**

This is fetching an image from Mozilla, resizing it, saving it and serving it.


# How to transform images

You go to your server URL`http://imgs.kitty.com` and append `/upload/`;  after that you can pass these options below, followed by an underscore and a value `w_250,q_50` Options are separated by coma (configurable to other separator).

After the options put the source of your image, it can be relative to your server or absolute: `/https://my.storage.io/imgs/pretty-kitten.jpg`

So to get a pretty kitten at 250 pixels wide, with 50% compression, you would write.
`<img src="http://imgs.kitty.com/upload/w_250,q_50/https://my.storage.io/imgs/pretty-kitten.jpg">`


## Basic Option details
You can see the full list of options configurable by URL params, **with examples**, in the [URL-Options document](docs/path-expression.md) 

We put a lot of defaults in place to prevent distortion, bad quality, weird cropping and unwanted padding.

The most common URL options are:

### `w` : width
`int`  
*Default:* `null`  
*Description:* Sets the target width of the image. If not set, width will be calculated in order to keep aspect ratio.

**example:`w_100`** 

`w_100` :   `https://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

### `h` : height
`int`  
*Default:* `null`  
*Description:* Sets the target height of the image. If not set, height will be calculated in order to keep aspect ratio.

**example:`h_100`** 

`h_100`  : `https://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

### Using width AND height

**example:`h_300,w_300`**  
By default setting width and height together, works like defining a rectangle that will define a **max-width** and **max-height** and the image will scale proportionally to fit that area without cropping.

By default; width, height, or both will **not scale up** an image that is smaller than the defined dimensions.

`h_300,w_300` : `https://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`


### `c` : crop
`bool`  
*Default:* `false`  
*Description:* When both width and height are set, this allows the image to be cropped so it fills the **width x height** area.

**example:`c_1`** 

`c_1,h_400,w_400` : `https://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

### `g` : gravity
`string`  
*Default:* `Center`  
*Description:* When crop is applied, changing the gravity will define which part of the image is kept inside the crop area.
The basic options are: `NorthWest`, `North`, `NorthEast`, `West`, `Center`, `East`, `SouthWest`, `South`, `SouthEast`.

**example:`g_West`** 

### `r` : rotate
`string`  
*Default:* `null`  
*Description:* Apply image rotation (using shear operations) to the image. 

**example: `r_90`, `r_-180`,...**

`r_45` :  `https://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

### `o` : output
`string`  
*Default:* `auto`  
*Description:* Output format requested, for example you can force the output as jpeg file in case of source file is png. The default `auto` will try to output the best format for the requesting browser, falling back to the same format as the source image or finally with a fallback to **jpg**.

**example:`o_auto`,`o_input`,`o_png`,`o_webp`,`o_jpeg`,`o_jpg`**

### `q` : quality
`int` (0-100)  
*Default:* `90`  
*Description:* Sets the compression level for the output image. Your best results will be between **70** and **95**.

**example:`q_100`,`q_75`,...** 

`q_30`  :  `https://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg` 


`q_100`  :  `https://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

### Refresh or re-fetch source image
`rf` : refresh  
*Default:* `false`  
*Description:* When this parameter is 1, it will force a re-request of the original image and run it through the transformations and compression again. It will delete the local cached copy.

**example:`rf_1`** 

--- 

## Server Options

There are some easy to setup server configurations in the `config/parameters.yml` file, you can see the full list of options and server configurations in the **[Application Options Document](docs/application-options.md)** 

## Security: Restricting Source Domains:

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
## Security: Signature Generation:

Based on this [RFC](https://github.com/flyimg/flyimg/issues/96) Signature Generation was added to Flyimg in order to avoid DDOS attacks.

First you need to edit `security_key` and `security_iv` in  parameters.yml file and add a proper values.
Than any request to Fyimg app will throw an error unless it's encrypted.

To generate the encrypted url you need to run this command:

```sh
docker exec flyimg php app.php encrypt w_200,h_200,c_1/http://medula.cl/t/resize-test_1920.jpg
```

it'll return something like this:

```sh
Hashed request: TGQ1WWRKVGUrZUpoNmJMc2RMUENPL2t6ZDJkWkdOejlkM0p0U0F3WTgxOU5IMzF3U3R0d2V4b3dqbG52cFRTSFZDcmhrY1JnaGZYOHJ3V0NpZDNNRmc9PQ==
```

Now you can request the image throw this new url:

```
http://localhost:8080/upload/TGQ1WWRKVGUrZUpoNmJMc2RMUENPL2t6ZDJkWkdOejlkM0p0U0F3WTgxOU5IMzF3U3R0d2V4b3dqbG52cFRTSFZDcmhrY1JnaGZYOHJ3V0NpZDNNRmc9PQ==
```


## Run Unit Tests:

```sh
docker exec flyimg vendor/bin/phpunit
```

Generate Html Code Coverage
```sh
docker exec flyimg vendor/bin/phpunit --coverage-html build/html
```

## How to Provision the application on:

- [DigitalOcean](https://github.com/flyimg/DigitalOcean-provision)
- [AWS Elastic-Beanstalk](https://github.com/flyimg/Elastic-Beanstalk-provision)

# Technology stack

* Server: nginx
* Application:  [Silex](http://silex.sensiolabs.org/) , a PHP micro-framework.
* Image manipulation: ImageMagick
* JPEG encoder: MozJpeg
* Storage: [Flysystem](http://flysystem.thephpleague.com/)
* Containerisation:  Docker

## Abstract storage with Flysystem

Storage files based on [Flysystem](http://flysystem.thephpleague.com/) which is `a filesystem abstraction allows you to easily swap out a local filesystem for a remote one. Technical debt is reduced as is the chance of vendor lock-in.`

Default storage is Local, but you can use other Adapters like AWS S3, Azure, FTP, DropBox, ... 

Currently, only the **local** and **S3** are implemented as Storage Provider in Flyimg application, but you can add your specific one easily in `src/Core/Provider/StorageProvider.php`. Check an [example for AWS S3 here](https://github.com/flyimg/flyimg/blob/master/docs/application-options.md#using-aws-s3-as-storage-provider).

# Benchmark

See [benchmark.sh](https://github.com/flyimg/flyimg/blob/master/benchmark.sh) for more details.

Requires: **Vegeta**[http://github.com/tsenart/vegeta](http://github.com/tsenart/vegeta)

```
./benchmark.sh
```

Latest Results:
```
Crop http://localhost:8080/upload/w_200,h_200,c_1/Rovinj-Croatia.jpg
Requests      [total, rate]            500, 50.10
Duration      [total, attack, wait]    9.991377689s, 9.97999997s, 11.377719ms
Latencies     [mean, 50, 95, 99, max]  19.402096ms, 12.844271ms, 54.65001ms, 96.276948ms, 135.597203ms
Bytes In      [total, mean]            5337500, 10675.00
Bytes Out     [total, mean]            0, 0.00
Success       [ratio]                  100.00%
Status Codes  [code:count]             200:500

Resize http://localhost:8080/upload/w_200,h_200,rz_1/Rovinj-Croatia.jpg
Requests      [total, rate]            500, 50.10
Duration      [total, attack, wait]    9.992435445s, 9.979999871s, 12.435574ms
Latencies     [mean, 50, 95, 99, max]  16.676093ms, 12.376525ms, 49.676187ms, 97.354697ms, 127.14737ms
Bytes In      [total, mean]            3879500, 7759.00
Bytes Out     [total, mean]            0, 0.00
Success       [ratio]                  100.00%
Status Codes  [code:count]             200:500

Rotate http://localhost:8080/upload/r_-45,w_400,h_400/Rovinj-Croatia.jpg
Requests      [total, rate]            500, 50.10
Duration      [total, attack, wait]    9.992650741s, 9.979999937s, 12.650804ms
Latencies     [mean, 50, 95, 99, max]  13.634143ms, 11.587252ms, 26.873827ms, 50.446923ms, 68.222253ms
Bytes In      [total, mean]            17609000, 35218.00
Bytes Out     [total, mean]            0, 0.00
Success       [ratio]                  100.00%
Status Codes  [code:count]             200:500
```

# Demo Application running

[https://oi.flyimg.io](https://oi.flyimg.io)

![resize-test](https://oi.flyimg.io/upload/w_300,h_250,c_1/http://medula.cl/t/resize-test_1920.jpg)


# Roadmap

- [x] Benchmark the application.
- [ ] Decouple the core logic from Silex in order to make it portable.
- [ ] Test it with couple of frameworks, Phalcon Php is a good candidate.
- [ ] Add overlays functionality (Text on top of the image)
- [ ] Storage auto-mapping
- [ ] Add support for FLIFF, BPG and JPEG2000


# Community

* Follow us on [GitHub][1] and [Twitter][2].

# Supporters

A special thanks to JetBrains for supporting our project with their [open source license program](https://www.jetbrains.com/buy/opensource/).

![Jetbrains](https://oi.flyimg.io/upload/w_300/jetbrains-variant-3.png)


# Contributors

This project exists thanks to all the people who contribute.
<a href="https://github.com/flyimg/flyimg/graphs/contributors"><img src="https://opencollective.com/flyimg/contributors.svg?width=890" /></a>


# Backers

Thank you to all our backers! [[Become a backer](https://opencollective.com/flyimg#backer)]

<a href="https://opencollective.com/flyimg#backers" target="_blank"><img src="https://opencollective.com/flyimg/backers.svg?width=890"></a>


# Sponsors

Thank you to all our sponsors! (please ask your company to also support this open source project by [becoming a sponsor](https://opencollective.com/flyimg#sponsor))

<a href="https://opencollective.com/flyimg/sponsor/0/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/0/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/1/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/1/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/2/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/2/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/3/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/3/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/4/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/4/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/5/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/5/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/6/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/6/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/7/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/7/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/8/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/8/avatar.svg"></a>
<a href="https://opencollective.com/flyimg/sponsor/9/website" target="_blank"><img src="https://opencollective.com/flyimg/sponsor/9/avatar.svg"></a>



# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.


Enjoy your Flyimaging!

[1]: https://github.com/flyimg
[2]: https://twitter.com/flyimg_
