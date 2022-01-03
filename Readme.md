### Laravel test
Laravel 8

### Overview
Memo app

### IDE-helper

```bash
sail artisan ide-helper:model
```

### build docker

```shell
# app
$ export WWWGROUP=${WWWGROUP:-$(id -g)}; docker image build -f infra/docker/8.1/php/Dockerfile --build-arg WWWGROUP=$WWWGROUP -t app .
$ docker image build -f infra/docker/8.1/php/Dockerfile -t app .

# nginx
$ docker image build -f infra/docker/8.1/nginx/Dockerfile -t web .
```

