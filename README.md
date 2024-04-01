# Job Manager
Technical test created for Web Atrio. 

## Contexte

Develop a web application for managing employment records for individuals. Ensure to expose an API to address various requirements:

- Save individuals' records.
- Add job details.
- Retrieve individuals based on multiple criteria.

## Requirements

- Php ^8.1 http://php.net/manual/fr/install.php;
- Composer https://getcomposer.org/download/;

## Installation

1. Clone the current repository.

3. Move into the directory, create an `.env.local` file, and custom it with your own database.

4. Execute the following commands in your working folder to install the project:

```bash
# Install dependencies
composer install

# Create 'checkpoint3' DB
php bin/console d:d:c

# Execute migrations and create tables
php bin/console d:m:m

# Import fixtures
php bin/console d:f:l

# Launch the Symfony server
symfony server:start
```

## Usage

Use [Postman](https://www.postman.com/) in order to test endpoints (I didn't have time for the documentation so I won't cheat by adding them here, but if you're good with code reading, you'll find them. See this as a treasure hunt 😅
