#!/bin/bash

# Simple SonarQube analysis script using Docker
# This can be called from Jenkins without requiring SonarQube Scanner installation

echo "Running SonarQube analysis using Docker..."

# Set default values
SONAR_HOST_URL=${SONAR_HOST_URL:-"http://localhost:9000"}
SONAR_LOGIN=${SONAR_AUTH_TOKEN:-""}
PROJECT_KEY=${SONAR_PROJECT_KEY:-"laravel-health-tracker"}

# Check if Docker is available
if ! command -v docker &> /dev/null; then
    echo "Docker not found, cannot run SonarQube analysis"
    exit 0
fi

# Run SonarQube analysis using Docker
docker run --rm \
    -v "$(pwd):/usr/src" \
    -w /usr/src \
    sonarsource/sonar-scanner-cli:latest \
    -Dsonar.projectKey=${PROJECT_KEY} \
    -Dsonar.projectName="Laravel Health Tracker" \
    -Dsonar.projectVersion=1.0 \
    -Dsonar.sources=. \
    -Dsonar.exclusions="vendor/**,node_modules/**,storage/**,bootstrap/cache/**,public/build/**,tests/**,sonar-scanner/**" \
    -Dsonar.host.url=${SONAR_HOST_URL} \
    -Dsonar.login=${SONAR_LOGIN} || {
    echo "SonarQube analysis failed or SonarQube server not available"
    echo "Continuing without analysis..."
    exit 0
}

echo "SonarQube analysis completed successfully"