## Requirements

You will need to have [**Docker**](https://docs.docker.com/get-docker/) on your machine.

## Options 1: Run the ready Docker image

Pull the docker image

```sh
docker pull flyimg/flyimg
```

Start the container

```sh
docker run -itd -p 8080:80 flyimg/flyimg
```

To use custom parameters, make a copy of [parameters.yml](https://github.com/flyimg/flyimg/blob/main/config/parameters.yml) to your current directory. Update to suit your needs and run the command with volume parameter to replace the original parameters file.

```bash
docker run -itd -p 8080:80 -v $(pwd)/parameters.yml:/var/www/html/config/parameters.yml flyimg/flyimg
```

## Options 2: Build from source

You can spin up your own working server in 10 minutes using the provision scripts for [AWS Elastic Beanstalk](https://github.com/flyimg/Elastic-Beanstalk-provision) or the [DigitalOcean Ubuntu Droplets](https://github.com/flyimg/DigitalOcean-provision).

For other environments or if you want to tweak and play in your machine before rolling out, read along...


```sh
git clone https://github.com/flyimg/flyimg.git
```

**CD into the folder** and to build the docker image by running:

```sh
docker build -t flyimg .
```

This will download and build the main image, It will take a few minutes.

**IMPORTANT!** If you cloned the project, only for the first time, you need to run `composer install` **inside** the container:

```sh
docker exec -it flyimg composer install
```

Again, it will take a few minutes to download the dependencies.

Then run the container:

```sh
docker run -itd -p 8080:80 -v $(pwd):/var/www/html --name flyimg flyimg
```

For Fish shell users:

```sh
docker run -itd -p 8080:80 -v $PWD:/var/www/html --name flyimg flyimg
```

The above command will make the Dockerfile run s6-overlay command which launches 2 processes: **nginx** and **php-fpm** and starts listening on port 8080.

## Testing Flyimg service

You can navigate to your machine's IP in port 8080 (ex: [http://127.0.0.1:8080/](http://127.0.0.1:8080/) ) ; you should get the demo homepage of Flyimg already working. If you get any errors at this stage it's most likely that composer has not finished installing or skipped something.

You can test your image resizing service by navigating to: [http://127.0.0.1:8080/upload/w_600,h_500,q_90/https://mudawn.com/assets/butterfly-3000.jpg](http://127.0.0.1:8080/upload/w_600,h_500,q_90/https://mudawn.com/assets/butterfly-3000.jpg)

**It's working!**

This is fetching an image , resizing it, saving it and serving it.