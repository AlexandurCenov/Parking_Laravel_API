# Parking_Laravel_API

Steps to use the API:

# you need db connection
# copy .env.example into .env
# update DB... variables in .env with your own database properties
# Create tables in your database
- php artisan migrate

# Fill your tables with default data
- php artisan db:seed

# Run your server via
- php artisan
# or your own server

# endpoints that can be used
- Check free spaces : /api/parking-spaces

- Check parking bill : /api/check-bill
  JSON example
    {
      "registration_number": "СA3386TB"
    }

- Enter in to the parking : /api/enter-parking
  JSON example
    {
      "registration_number": "СA3295A",
      "category": "Car",
      "discount_card": "Silver",
      "entered_on": "2023-06-15 07:12:37"
    }

- Exit from the parking : /api/exit-parking
  JSON example
    {
      "registration_number": "СA3395A"
    }
