# Parking_Laravel_API

## Steps to use the API:

1. you need db connection
2. copy .env.example into .env
3. update DB... variables in .env with your own database properties
4. Create tables in your database
  ```sh
    php artisan migrate
  ```

5. Fill your tables with default data
  ```sh
    php artisan db:seed
  ```

6. Run your server via
  ```sh
    php artisan serve
  ```

 * or your own server

7. endpoints that can be used
- Check free spaces : /api/parking-spaces

- Check parking bill :
```sh
  /api/check-bill
```
  * JSON example
  ```sh
    {
      "registration_number": "СA3386TB"
    }
  ```

- Enter in to the parking :
```sh
  /api/enter-parking
```
  * JSON example
  ```sh
    {
      "registration_number": "СA3295A",
      "category": "Car",
      "discount_card": "Silver",
      "entered_on": "2023-06-15 07:12:37"
    }
  ```

- Exit from the parking :
```sh
  /api/exit-parking}
```
  * JSON example
  ```sh
    {
      "registration_number": "СA3395A"
    }
  ```
