# Entropia landing page

The live site is available here: https://entropia.de

![Screenshot](screenshot.png)

## Setup with Docker

1. Build the container

```bash
docker build -t entropia-landing-page:latest .
```

2. Run the container

```bash
docker run -p 3000:80 entropia-landing-page                             # production
docker run -p 3000:80 -v ./public:/var/www/html entropia-landing-page   # development
```
