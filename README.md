# Car Bid Calculation Tool

The Car Bid Calculation Tool is a web application designed to help buyers calculate the total price of a vehicle (common or luxury) at a car auction. The application takes into account various fees associated with the transaction, which are calculated based on the base price of the vehicle and its type. The total price is the sum of the winning bid amount (vehicle base price) and the dynamically computed fees.

## Table of Contents

1. [Installation](#installation)
    1. [Backend](#backend)
    2. [Frontend](#frontend)
2. [Starting the Application](#starting-the-application)
3. [Testing](docs/testing.md)
    1. [Backend](docs/testing.md#backend)
    2. [Frontend](docs/testing.md#frontend)
4. Architecture Decision Records (ADRs)
    1. [ADR-1: Use of SQLite](docs/backend/adr-1-use-of-sqlite.md)

<hr />

### Installation

To install the Car Bid Calculation Tool, follow these steps:

#### Backend

1. Clone the repository to your local machine.
2. Navigate to the `/backend` directory.
3. Run `composer install` to install the required dependencies.
4. Copy the `.env.example` file to `.env`.
5. Run the migrations:

    `bin/console doctrine:migrations:migrate`

6. Load the fixtures:

    `bin/console doctrine:fixtures:load`

#### Frontend

1. Navigate to the `frontend` directory.
2. Run `npm install` to install the required dependencies.
3. Copy the `.env.example` file to `.env`.

<hr />

### Starting the Application

To start the Car Bid Calculation Tool, follow these steps:

1. From the `/backend` directory, start the Symfony server:

    `symfony serve`

2. Using a second terminal window, navigate to the `frontend` directory and start the Vue development server:

    `npm run serve`

3. Open your browser and navigate to `http://localhost:8080` to access the application.

