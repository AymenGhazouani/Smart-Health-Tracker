pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "laravel-app"
        APP_PORT = "8081"  // Different port for your Laravel app
    }

    stages {
        stage('Ensure master') {
            steps {
                script {
                    // Prefer BRANCH_NAME (multibranch), fallback to GIT_BRANCH if present
                    def branch = env.BRANCH_NAME ?: env.GIT_BRANCH
                    if (!branch) {
                        // If neither is set, fail explicitly to avoid accidental runs
                        error "Cannot determine branch. Pipeline allowed only on 'master'."
                    }
                    // normalize possible prefixes like origin/master
                    if (!branch.tokenize('/').last().equals('master')) {
                        error "Pipeline is allowed only on 'master'. Current branch: ${branch}"
                    }
                }
            }
        }

        stage('Checkout') {
            steps {
                git branch: 'master',
                    url: 'hhttps://github.com/AymenGhazouani/Smart-Health-Tracker',
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

