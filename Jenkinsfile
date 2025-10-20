pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "laravel-app"
        APP_PORT = "8081"  // Different port for your Laravel app
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main',
                    url: 'https://github.com/YOUR_USERNAME/YOUR_REPO.git',
                    credentialsId: 'github-credentials'
            }
        }

        stage('Stop Old Containers') {
            steps {
                sh '''
                    docker compose down || true
                '''
            }
        }

        stage('Build and Deploy') {
            steps {
                sh '''
                    docker compose up -d --build
                '''
            }
        }

        stage('Show Running Containers') {
            steps {
                sh 'docker ps'
            }
        }
    }

    post {
        success {
            echo 'Deployment successful! Access your app at http://<VM-IP>:' + env.APP_PORT
        }
        failure {
            echo 'Deployment failed!'
            sh 'docker compose logs'
        }
    }
}
