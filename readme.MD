
# Simple Storage Solution - Initedit

## ssd with docker

```
docker build -t ssd -f ssd.Dockerfile .

docker run -d -p 80:80 -v /data:/var/www/html/uploads ssd

```


## Installation :

1. upload files to /public_html/
2. Done( Goto https://yourip/)

Note : /upload directory should have write permission)

Live code : [https://ssd.initedit.com](https://ssd.initedit.com)

# Prerequisite :

ubuntu: apt-get update; apt-get install apache2 php
