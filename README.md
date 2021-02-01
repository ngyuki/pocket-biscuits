# Pocket Biscuits

## Build

```sh
composer install
npm ci
rsync -cv \
  node_modules/bootstrap/dist/js/bootstrap.min.js \
  node_modules/bootstrap/dist/css/bootstrap.min.css \
  node_modules/bootstrap/dist/css/bootstrap.min.css.map \
  node_modules/jquery/dist/jquery.min.js \
    public/lib/
```

## Deploy to heroku

```sh
heroku create pocket-biscuits
heroku git:remote -a pocket-biscuits
heroku buildpacks:set heroku/php
heroku config:set APP_ENV=prod
heroku config:set POCKET_CUSTOMER_KEY=$YOUR_POCKET_CUSTOMER_KEY

git push heroku master
```

## Demo

[http://pocket-biscuits.herokuapp.com/](http://pocket-biscuits.herokuapp.com/)
