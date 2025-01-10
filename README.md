# Promotions App
The Promotions App is a lightweight PHP application designed to manage and showcase products, categories, and discounts.
I aimed to apply clean architecture principles and incorporated modern development practices, such as dependency injection, middleware, and testing.
However, I am unsure if they have been implemented correctly, as I haven't had significant free time to fully refine them.
There are still some dependencies that need updating, and the tests do not yet fully cover the application.

## Requirements:
* PHP: Version 8.2 or higher
* Composer: For dependency management
* Docker: For containerized environments
* MySQL: For database integration

## Usage:
* Start Services: You can run the application using Docker Compose.
* For JSON data source: `make json-setup`
* For MySQL data source: `make db-setup`
* For testing: `make test`
* Run the application: Visit the application at http://localhost:8000.
