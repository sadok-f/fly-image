# Flyimg

<p align="center">
  <a href="https://flyimg.io" target="_blank">
    <img alt="Flyimg" src="https://raw.githubusercontent.com/flyimg/graphic-assets/main/logo/raster/flyimg-logo-rgb.png" width="300">
  </a>
  <br />
    <br />
    <a href="https://github.com/flyimg/flyimg">GitHub</a>
    ·
    <a href="https://github.com/flyimg/flyimg/issues">Bugs Report</a>
</p>

<p align="center">
<a href="#backers"><img alt="Backers on Open Collective" src="https://opencollective.com/flyimg/backers/badge.svg"></a>
<a href="#sponsors"><img alt="Sponsors on Open Collective" src="https://opencollective.com/flyimg/sponsors/badge.svg"></a>
<a href="https://github.com/flyimg/flyimg/actions?query=workflow%3ACI"><img alt="Build Status" src="https://github.com/flyimg/flyimg/workflows/CI/badge.svg?branch=main"></a>
<a href="https://codecov.io/gh/flyimg/flyimg"><img alt="Codecov" src="https://codecov.io/gh/flyimg/flyimg/branch/main/graph/badge.svg?token=jgryCuAGjF"></a>
<a href="https://packagist.org/packages/flyimg/flyimg"><img alt="License" src="https://poser.pugx.org/flyimg/flyimg/license.svg"></a>
<a href="https://packagist.org/packages/flyimg/flyimg"><img alt="Latest Stable Version]" src="https://poser.pugx.org/flyimg/flyimg/v/stable.svg"></a>
</p>

The Flyimg project is a Dockerized application that allows you to resize, crop, and compress images on the fly.  One Docker container to build your own Cloudinary-like service.

By default, Flyimg generates the **AVIF** image format (when the browser supports it)  which provides superior compression compared to other formats.

Additionally, Flyimg also generates the **WebP** format, along with the impressive **MozJPEG** compression algorithm to optimize images, other formats are supported also such as **PNG** and **GIF**.

## How it works:

**Fetch an image from anywhere; resize, compress, cache and serve...and serve, and serve, and serve...**

You pass the image URL and a set of keys with options, like size or compression. Flyimg will fetch the image, convert it, store it, cache it and serve it. The next time the request comes, it will serve the cached version.

```html
<!-- https://mudawn.com/assets/butterfly-3000.jpg -->
<img
  src="https://demo.flyimg.io/upload/w_300,q_90/https://mudawn.com/assets/butterfly-3000.jpg"
/>
```

![Flyimg-demo](https://demo.flyimg.io/upload/w_300,q_90/https://mudawn.com/assets/butterfly-3000.jpg)

## Demo
Check out our demo page where you can test and review Flying's features:

[https://demo.flyimg.io/](https://demo.flyimg.io)

## Star History

[![Star History Chart](https://api.star-history.com/svg?repos=flyimg/flyimg&type=Date)](https://star-history.com/#flyimg/flyimg&Date)

## Cloud Run Button

Flyimg can be deployed to GCP as a serverless container in one click with Cloud Run Button:

<a href="https://deploy.cloud.run/" target="_blank"><img src="https://storage.googleapis.com/cloudrun/button.svg?git_repo=https://github.com/flyimg/flyimg.git" alt="Run on Google Cloud" style="width:180px;margin-top:20px;"/></a>



## How to transform images

You go to your server URL`http://imgs.kitty.com` and append `/upload/`; after that you can pass these options below, followed by an underscore and a value `w_250,q_50` Options are separated by coma (configurable to other separator).

After the options put the source of your image, it can be relative to your server or absolute: `/https://my.storage.io/imgs/pretty-kitten.jpg`

So to get a pretty kitten at 250 pixels wide, with 50% compression, you would write.
`<img src="http://imgs.kitty.com/upload/w_250,q_50/https://my.storage.io/imgs/pretty-kitten.jpg">`

---

## Demo Application running

[https://demo.flyimg.io](https://demo.flyimg.io)

`https://demo.flyimg.io/upload/w_300,h_250,c_1,o_jpg/https://mudawn.com/assets/butterfly-3000.jpg`

![resize-test](https://demo.flyimg.io/upload/w_300,h_250,c_1,o_jpg/https://mudawn.com/assets/butterfly-3000.jpg)

## Community

- Follow us on [GitHub][1], [Twitter][2] and [LinkedIn][3].

## Adopters

See the [ADOPTERS.md](https://github.com/flyimg/flyimg/blob/main/ADOPTERS.md) file for a list of companies / organisations that are using Flyimg.

## Supporters

A special thanks to JetBrains for supporting our project with their [open source license program](https://www.jetbrains.com/buy/opensource/).

![Jetbrains](https://demo.flyimg.io/upload/w_150/https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.png)

## Contributors

This project exists thanks to all the people who contributed to it.
<a href="https://github.com/flyimg/flyimg/graphs/contributors"><img src="https://opencollective.com/flyimg/contributors.svg?width=890" /></a>

## Backers & Sponsors

Support us with a monthly donation and help us continue our activities.[[opencollective.com](https://opencollective.com/flyimg)]

<a href="https://opencollective.com/flyimg" target="_blank"><img src="https://opencollective.com/flyimg/backers.svg?width=890"></a>
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

## License

The MIT License (MIT). Please see [License File](https://github.com/flyimg/flyimg/blob/main/LICENSE) for more information.

Enjoy your Flyimaging!

[1]: https://github.com/flyimg
[2]: https://twitter.com/flyimg_
[3]: https://www.linkedin.com/company/flyimg
