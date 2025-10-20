pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "laravel-app"
        APP_PORT = "8081"
    }

    stages {
        stage('Ensure allowed branch') {
            steps {
                script {
                    def branch = (env.BRANCH_NAME ?: env.GIT_BRANCH)?.tokenize('/').last()
                    if (!branch) {
                        error "Cannot determine branch. Pipeline allowed only on 'main' or 'master'."
                    }
                    if (!(branch == 'main' || branch == 'master')) {
                        error "Pipeline is allowed only on 'main' or 'master'. Current branch: ${branch}"
                    }
                }
            }
        }

        stage('Checkout') {
            steps {
                git branch: 'master',
                    url: 'https://github.com/AymenGhazouani/Smart-Health-Tracker.git'
            }
        }

        stage('Stop Old Containers') {
            steps {
                sh 'docker compose down || true'
            }
        }

        stage('Build and Deploy') {
            steps {
                sh 'docker compose up -d --build'
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
            echo "Deployment successful! Access your app at http://<VM-IP>:\${APP_PORT}"
        }
        failure {
            echo 'Deployment failed!'
            sh 'docker compose logs'
        }
    }
}
