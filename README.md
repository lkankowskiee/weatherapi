# WeatherAPI App
This is an application for weather data that is using https://www.weatherapi.com.
Application collects data from two endpoints:
* Current Weather
* Forecast Weather

Location on current weather form is stored in datadase when is correct and can be edited in _Locations_ section.

On _Forecasts_ section there is a pager implementation with a little caching mechanism for counting number of records.


# Setup
add your key into `.env.local` and optionaly into `.env.test.local` (if you want to run a tests)
```shell
WEATHER_API_KEY=api_key_here
```

# Install
```shell
docker-compose up -d
docker-compose exec php-fpm composer install
docker-compose exec php-fpm php bin/console doctrine:schema:create
```
or single command
```shell
. bin/build.sh
```
and go to https://127.0.0.1:8000/ in browser.

Alternatively just run `symfony serve -d` on development environment.

# Sample data
Run in project directory and type "yes":
```shell
docker-compose exec php-fpm php bin/console doctrine:fixture:load
```
It loads 2 sample locations and 10001 random records into forecasts.
