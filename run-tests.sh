#!/bin/bash

# Script to run PHPUnit tests with coverage for SonarQube

echo "Running PHPUnit tests with coverage..."

# Create tests directory if it doesn't exist
mkdir -p tests/results

# Run PHPUnit with coverage (requires xdebug)
php artisan test --coverage-clover=coverage.xml --log-junit=tests/results.xml

echo "Tests completed. Coverage report generated at coverage.xml"