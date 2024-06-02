#!/usr/bin/env bash

set -e

# check if ab installed
ab -V >/dev/null 2>&1 || { echo >&2 "Benchmark require Apache ab tools but it's not installed. Aborting."; exit 1; }

# download the test image
curl "https://upload.wikimedia.org/wikipedia/commons/b/b7/The_sculptures_of_two_mythical_giant_demons%2C_Thotsakan_and_Sahatsadecha%2C_guarding_the_eastern_gate_of_the_main_chapel_of_Wat_Arun%2C_Bangkok.jpg" -o web/wat-arun.jpg

# which port
port=8099
# a random name for the container and the image
randName=$(LC_CTYPE=C tr -dc "[:lower:]" < /dev/random | head -c 8)

# build the image
docker build -t "$randName" .

# run the container
docker run -itd -p $port:80 --name "$randName" "$randName"

# sleep 2 sec until the container is ready
sleep 2

# install php dependencies
docker exec "$randName" composer install --no-dev --optimize-autoloader

# run vegeta attack
run() {
  url="http://localhost:$port/upload/$2/wat-arun.jpg"
  printf "\n"
  echo "------------------------------------------------------------------------------"
  echo "------------------------------------------------------------------------------"
  echo "$1 $url"
  echo "------------------------------------------------------------------------------"

  ab -n 1000 -c 4 "$url"
  sleep 1
}

# run benchmark
run "Crop" "w_500,h_500,c_1"
run "Simple Resize" "w_500,h_500"
run "Simple Resize with refresh" "w_500,h_500,rf_1"
run "Resize" "w_500,h_500,rz_1"
run "Rotate" "r_-45,w_400,h_400"

# remove the container and the image
docker rm -f "$randName"
docker rmi -f "$randName"
rm -rf web/wat-arun.jpg
