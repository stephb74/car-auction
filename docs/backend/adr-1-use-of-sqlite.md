# ADR-1: Use of SQLite

## Context

The application uses various types of fixed and variable to costs to calculate
the total bidding price of a car. The application needs to store these costs
and the total bidding price in some type of storage.

## Decision

An SQLite database will be used to store the costs and the total bidding price.

## Consequences

While a simple JSON file could've been used to store the data, an SQLite database
is used to showcase how this application can be scaled easily in a real-world scenario.

This also allows the use of Doctrine ORM to interact with the database, making it easier
to manage and query the data.
