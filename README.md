# Pocket Biscuits

## Build

```sh
composer install
npm install
rsync -cv \
  node_modules/bootstrap/dist/js/bootstrap.min.js \
  node_modules/bootstrap/dist/css/bootstrap.min.css \
  node_modules/bootstrap/dist/css/bootstrap.min.css.map \
  node_modules/jquery/dist/jquery.min.js \
    html/lib/
```
