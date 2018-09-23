# URL Options

This document lists and describes the full list of options available to be passed as parameters in the URL.

For server options check the [server options](/docs/server-options.md)

The server is setup to perform operations based on the following URL pattern.

```
https://server.address.io/process-type/image_options/path_to_image
```

for example:

```
https://oi.flyimg.io/upload/w_500/Rovinj-Croatia.jpg
```

Explanation from end-to-begining 

## path_to_image

It's the first operation the server does, it will try to get an image from the URI in the path, it can be relative to the local server, or absolute to the internet.

**example:** `Rovinj-Croatia.jpg` or `https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

---

# image_options

Here you set all the transformations and output settings you want to apply to the image you are fetching.

Most of these options are ImageMagick flags, many can get pretty advanced, use the [ImageMagick docs](http://www.imagemagick.org/script/command-line-options.php). 
We put a lot of defaults in place to prevent distortion, bad quality, weird cropping and unwanted paddings. 

The script **does a lot of sanitizing** of the parameters, so many options will not work or have to be carefullly escaped. Priority is given to safety and eas of use.

## Basic geometry

### `w` : width
`int`
*Default:* `null`
*Description:* Sets the target width of the image. If not set, width will be calculated in order to keep aspect ratio.

**example:`w_100`** 

`w_100` :   `https://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![w_100](https://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/w_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### `h` : height
`int`
*Default:* `null`
*Description:* Sets the target height of the image. If not set, height will be calculated in order to keep aspect ratio.

**example:`h_100`** 

`h_100`  : `https://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`
 
[![h_100](https://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/h_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### Using width AND height

**example:`h_300,w_300`** 
By default setting width and height together, works like defining a rectangle that will define a **max-width** and **max-height** and the image will scale propotionally to fit that area without cropping.
<!-- in the future put example images here-->

By default; width, height, or both will **not scale up** an image that is smaller than the defined dimensions.
<!-- in the future put example images here-->

`h_300,w_300` : `https://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![h_300,w_300](https://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/h_300,w_300/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### `c` : crop
`bool`
*Default:* `false`
*Description:* When both width and height are set, this allows the image to be cropped so it fills the **width x height** area.

**example:`c_1`** 

`c_1,h_400,w_400` : `https://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![c_1,h_400,w_400](https://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/c_1,h_400,w_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

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

[![r_45](https://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/r_-45,w_400,h_400/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

---

## Output file formats

### `o` : output
`string`
*Default:* `auto`
*Description:* Output format requested, for example you can force the output as jpeg file in case of source file is png. The default `auto` will try to output the best format for the requesting browser, falling back to the same format as the source image or finally with a fallback to **jpg**.

If `input` is passed, no "optimal" format will be attempted. Flyimg will try to respond with the source format or fallback to `jpg`.

**example:`o_auto`,`o_input`,`o_png`,`o_webp`,`o_jpeg`,`o_jpg`**


### `q` : quality
`int` (0-100)
*Default:* `90`
*Description:* Sets the compression level for the output image. Your best results will be between **70** and **95**.

**example:`q_100`,`q_75`,...** 

`q_30`  :  `https://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg` 

[![q_30](https://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/q_30/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)


`q_100`  :  `https://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![q_100](https://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/q_100/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### `webpl` : webp-lossless
`int`
*Default:* `0`
*Description:* If output is set to webp, it will default to lossy compression, but if you want lossless webp encoding you have to set it to 1.

**example:`webpl_1`** 

---

## Refresh or re-fetch source image

### `rf` : refresh
*Default:* `false`
*Description:* When this parameter is 1, it will force a re-request of the original image and run it throught the transformations and compression again. It will delete the local cached copy.

**example:`rf_1`** 

The nginx server will send headers to prevent caching of this request.

It will also send headers with the command done on the image + info returned by the command identity from Imagemagik.

--- 

## Fancy options

### `bg` : background
`color` (multiple formats)
*Default:* `null`
*Description:* Sets the background of the canvas for the cases where padding is added to the images. It supports hex, css color names, rgb. 
Only css color names are supported without quotation marks.
For the hex code, the hash `#` character should be replaced by `%23` 

**example:`bg_red`,`bg_%23ff4455`,`bg_rgb(255,120,100)`,...** 

```sh
  [...] -background red ...
  [...] -background "#ff4455" -> "%23ff4455"
  [...] -background "rgb(255,120,100)" ...
```

`https://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg`

[![bg_red](https://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)](https://oi.flyimg.io/upload/r_45,w_400,h_400,bg_red/https://raw.githubusercontent.com/flyimg/flyimg/master/web/Rovinj-Croatia.jpg)

### `st` : strip
`int`
*Default:* `1`
*Description:* removes exif data and additional color profile. Leaving your image with the default sRGB color profile.

**example:`st_1`** 

### `rz` : resize
*Default:* `null`
*Description:* The alternative resizing method to -thumbnail.

**example:`rz_1`** 

### `moz` : mozjpeg
*Default:* `1`
*Description:* Use moz-jpeg compression library, if `0` it fallsback to the default ImageMagick compression algorithm.

### `unsh` : unsharp
`radiusxsigma{+gain}{+threshold}`
*Default:* `null`
*Description:* Sharpens an image with a convolved Gausian operator. A good example `0.25x0.25+8+0.065`.

**example:`unsh_0.25x0.25+8+0.065`** 

### `f` : filter
`string`
*Default:* `Lanczos`
*Description:* Resizing algorithm, Triangle is a smoother lighter option

**example:`f_Triangle`** 

### `sc` : scale
*Default:* `null`
*Description:* The "-scale" resize operator is a simplified, faster form of the resize command. Useful for fast exact scaling of pixels.

**example:`sc_1`** 

### `fc` : face-crop
`int`
*Default:* `0`
*Description:* Using [facedetect](https://github.com/wavexx/facedetect) repository to detect faces and passe the coordinates to ImageMagick to crop.

**example:`fc_1`** 

`fc_1` :  `https://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg`

[![fc_1](https://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg)](https://oi.flyimg.io/upload/fc_1/http://facedetection.jaysalvat.com/img/faces.jpg)

### `fcp` : face-crop-position
`int`
*Default:* `0`
*Description:* When using the Face crop option and when the image contain more than one face, you can specify which one you want get cropped

**example:`fcp_1`,`fcp_0`,...** 

`fcp_2` : `https://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg`

[![fcp_2](https://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg)](https://oi.flyimg.io/upload/fc_1,fcp_2/http://facedetection.jaysalvat.com/img/faces.jpg)

### `fb` : face-blur
`int`
*Default:* `0`
*Description:* Apply blur effect on faces in a given image

**example:`fb_1`** 

`fb_1`  : `https://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg`

[![fb_1](https://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg)](https://oi.flyimg.io/upload/fb_1/http://facedetection.jaysalvat.com/img/faces.jpg)

### `e` : extract
#### `p1x` : extract-top-x
#### `p1y` : extract-top-y
#### `p2x` : extract-bottom-x
#### `p2y` : extract-bottom-y
*Default:* `null`

*Description:* Extract and crop an image with given the x/y coordinates of each booth top and bottom.

### `sf` : sampling-factor
*Default:* `1x1`
*Description:* ...

### `ett` : extent
*Default:* `null`
*Description:* ... not ready

### `par` : preserve-aspect-ratio
`int`
*Default:* `1`
*Description:* If set to 0, when passing width and height to an image, the image will be distorted to fill the size of the rectangle defined by width and height.

### `pns` : preserve-natural-size
`int`
*Default:* `1`
*Description:* If set to 0 and if the source image is smaller than the target dimensions, the image will be stretched to the target size.

### `gf` : gif-frame
`int`
*Default:* `0`
*Description:* ...

--- 

# process-type: *upload* or *path*

There are 2 main proceses you can do for images.

The first: **upload**, grabs an image from a URL, transforms it, saves it, and serves it, as an image, with all the apropiate headers.

The second: **path**, grabs an image from a URL, transforms it, saves it, and returns the absolute path to the image as a string, in the body of the response.

--- 

## Options keys

```yml
options_keys:
  moz: mozjpeg
  q: quality
  o: output
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
  webpl: webp-lossless
```

## Default options values

```yml
default_options:
  mozjpeg: 1
  quality: 90
  output: auto
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
  webp-lossless: 0
```
