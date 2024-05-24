# Application Options

## The file config/parameters.yml

Here are the app options you can configure with the [config/parameters.yml](https://github.com/flyimg/flyimg/blob/main/config/parameters.yml) these options operate at runtime, you don't need to rebuild the container or restart any service, all requests<sup><a name="footnote1">1</a></sup> will check this config.

### debug

_Defaults to:_ `false`
_Description:_ Enables debug mode, currently is used only for the tests, so there's no harm in leaving it as it is.

### enable_avif

_Defaults to:_ `true`
_Description:_ Serve AVIF automatically to Browsers supporting it. You can always request an image in Avif format passing the `o_avif` [URL option key](https://github.com/flyimg/flyimg/blob/main/docs/url-options.md).

### enable_webp

_Defaults to:_ `true`
_Description:_ Serve WebP automatically to Browsers supporting it. You can always request an image in webP format passing the `o_webp` [URL option key](https://github.com/flyimg/flyimg/blob/main/docs/url-options.md).

### header_cache_days

_Defaults to:_ `365`
_Description:_ Number of days for header cache expires `max_age`, this is the header sent to the client or browser requesting the resource. You can pass cache busting parameters to the URL which will break cache in all modern proxies and Browsers.

### options_separator

_Defaults to:_ `,`
_Description:_ URL options are separated by default by comas `,` but you can change that to some other character, like `._~:[]@!$'()*+;` just be carefull that it doesn't conflict with the sintaz of options you are passing to the URL, there is no strict checking of separating characters.
When changing this value, you should change the OPTIONS_SEPARATOR value in web/js/main.js.

### restricted_domains

_Defaults to:_ `false`
_Description:_ This restricts fetching images for transformations only from _whitelisted domains_ (see `whitelist_domains`). A good measure of safety and to prevent abuse of your app from third parties is to set `restricted_domains` to `true`, this way the app will download and try to transform resources only from domains you trust or have control of.

### whitelist_domains

_Defaults to:_

```yml
    - domain-1.com
    - domain-2.com
```

_Description:_ If `restricted_domains` is enabled, put your whitelisted domains in this list, subdomains are also OK. For the [Digital Ocean Provisioning Script](https://github.com/flyimg/DigitalOcean-provision) you can set the restricted domains at the droplet provisioning step.

### disable_cache

_Defaults to:_ `false`
_Description:_ When set to true the generated image will be deleted from the cache in web/upload and served directly in the response


### storage_system

_Defaults to:_ `local`
_Description:_ You can store the transformed images in many different ways taking advantage of the [Flysystem](http://flysystem.thephpleague.com/) file system, like FTP, Dropbox, or whatever, although currently the only two easy options are `local` (the default) and `s3` to use an AWS S3 bucket.

### aws_s3

_Description:_ In case `storage_system` is set to `s3` you need to pass your AWS S3 Bucket credentials, do it here. Read more below at [Abstract storage with Flysystem](#abstract-storage-with-flysystem).
_Defaults to:_

```yml
  access_id: ""
  secret_key: ""
  region: ""
  bucket_name: ""
```

## Abstract storage with Flysystem

Storage files based on [Flysystem](http://flysystem.thephpleague.com/) which is `a filesystem abstraction allows you to easily swap out a local filesystem for a remote one. Technical debt is reduced as is the chance of vendor lock-in.`

Default storage is Local, but you can use other Adapters like AWS S3, Azure, FTP, Dropbox, ...

Currently, only the local and S3 are implemented as Storage Provider in Flyimg application, but you can add your specific one easily in `src/Core/Provider/StorageProvider.php`

### Using AWS S3 as Storage Provider

in parameters.yml change the `storage_system` option from local to s3, and fill in the aws_s3 options :

```yml
storage_system: s3

aws_s3:
  access_id: "s3-access-id"
  secret_key: "s3-secret-id"
  region: "s3-region"
  bucket_name: "s3-bucket-name"
```

## Footnotes

1. All request will check your settings at `config/parameters.yml` but if you are heavily requests caching before the application, there will not affect the response, but if you are caching responses you already knew that ;)
