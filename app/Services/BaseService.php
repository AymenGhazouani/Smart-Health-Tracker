<?php

namespace App\Services;

/**
 * Base service class for common business logic operations
 * Services will interact directly with Eloquent models
 * Extend this class for your specific services
 */
abstract class BaseService
{
    // Common service methods can be added here
    // Example: logging, validation, caching, etc.
    
    /**
     * Handle common service operations like logging
     */
    protected function logActivity(string $action, array $data = []): void
    {
        // Add logging logic here if needed
    }
}