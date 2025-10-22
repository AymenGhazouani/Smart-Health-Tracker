<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\HealthDataSeeder;

class SeedHealthData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'health:seed {--user-id= : Specific user ID to seed data for}';

    /**
     * The console command description.
     */
    protected $description = 'Seed realistic health data for testing AI features';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🏥 Starting Health Data Seeding...');
        
        $seeder = new HealthDataSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('✅ Health data seeding completed!');
        $this->info('');
        $this->info('📊 You can now test the AI features:');
        $this->info('   • Health AI Dashboard: /health-ai');
        $this->info('   • Health Predictions: /health-predictions');
        $this->info('   • Machine Learning: /health-ml');
        
        return Command::SUCCESS;
    }
}