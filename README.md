# Tasks-api-with-doc

Tasks-api-with-doc is a laravel powered api of tasks gestion. 
Using token for connextion.
This project is made mainly for learning how to use and make feature&unit test.

## Quick Start

``` bash
# Install Dependencies
composer install

# Run Migrations
php artisan migrate

# MAke a copy of your .env file
cp .env.example .env

## Create your db and like it to your project via the .env

# If you get an error about an encryption key
php artisan key:generate
```
## Current test

### User
- register
    - everything is well filled 

    - ***email*** empty
    - ***email*** wrong format
    - ***email*** already taken 
    - ***email*** is not a string 

    - ***password*** empty 
    - ***password*** wrong format 
    - ***password*** too short 
    - ***password*** is not a string 

    - ***username*** empty 
    - ***username*** is not a string 

- login
    - everything is well filled

    - ***email*** empty 
    - ***email*** wrong format 
    - ***email*** do not match the records
    - ***email*** is not a string

    - ***password*** empty 
    - ***password*** wrong format
    - ***password*** too short
    - ***password*** do not match the record
    - ***password*** is not a string 

### Tasks
- create
    - everything is well filled

    - ***body*** empty 
    - ***body*** is not a string

    - ***token*** empty or expired

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
