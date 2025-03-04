# Running the Tests

<span style="font-size: 15px;">← Back to [Table of Contents](../README.md)</span>

To run the tests for the Car Bid Calculation Tool, follow these steps:

## Backend

1. Navigate to the `/backend` directory.
2. Run the following command:

   `composer run test`

## Frontend

Note: In order to run the end-to-end tests, the backend server must be running.

1. Navigate to the `frontend` directory.
2. Run the following command to run unit tests:

   `npm run test:unit:coverage`

3. Run the following command to run end-to-end tests:

   `npm run test:e2e:run`

4. Run the following command to open the end-to-end test runner:

   `npm run test:e2e:open`
