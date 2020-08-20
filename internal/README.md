# Sezzle Magento2 Gateway Frontend

> This setup is for development purpose only, NOT for production purpose.

## Setup
1. `cp ./internal/docker.sample.env ./internal/docker.env`
2. Configure environment variables in `./internal/docker.env`
3. `docker-compose up -d`

## Install Magento 2
> Magento starts support of MySQL 5.7 in version 2.1.2. Before 2.1.2, MySQL 5.6 should be used.

```bash
docker exec -it magento2 install
```

Sezzle plugin directory is mounted to the container. please refer to volumes in `docker-compose.yml`.

Once the installation is complete [magento2 admin page](http://localhost/admin). Login using the admin username and password configured in `docker.env`


## Cleanup
```bash
docker-compose down --rmi local -v --remove-orphans
```

### To create an image with Magento Version 
```bash
MAGENTO_VERSION=2.3.3 && \
docker build -t hisankaran/magento2:$MAGENTO_VERSION --build-arg MAGENTO_VERSION=$MAGENTO_VERSION ./internal/. && \
docker push hisankaran/magento2:$MAGENTO_VERSION
```

### Create a release archive
```bash
`./internal/scripts/create-release.sh`
```