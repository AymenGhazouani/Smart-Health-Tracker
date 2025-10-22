// Groovy
node {
    // Compute dynamic branch list for the choice parameter. This runs before the declarative pipeline is parsed.
    BRANCH_NAMES = "master\n" // fallback
    try {
        // Replace credentialsId and git URL with your values if needed.
        withCredentials([usernamePassword(credentialsId: 'user-credential-in-gitlab', usernameVariable: 'GIT_USERNAME', passwordVariable: 'GITSERVER_ACCESS_TOKEN')]) {
            BRANCH_NAMES = sh(
                script: """git ls-remote -h https://${GIT_USERNAME}:${GITSERVER_ACCESS_TOKEN}@dns.name/gitlab/PROJS/PROJ.git | awk -F'/' '{print \\$NF}'""",
                returnStdout: true
            ).trim()
        }
    } catch (err) {
        echo "Could not fetch remote branches: ${err}. Falling back to 'master' only."
        BRANCH_NAMES = "master"
    }
}

pipeline {
    agent any

    environment {
        COMPOSE_PROJECT_NAME = "laravel-app"
        APP_PORT = "8081"
    }

    parameters {
        booleanParam(name: 'REFRESH_BRANCHES', defaultValue: false, description: 'Refresh branch list; pipeline will stop after refresh.')
        choice(name: 'BranchName', choices: "${BRANCH_NAMES}", description: 'Choose a branch (ignored if REFRESH_BRANCHES is true)')
    }

    stages {
        stage('Refresh Only') {
            when {
                expression { return params.REFRESH_BRANCHES == true }
            }
            steps {
                echo "REFRESH_BRANCHES selected — branch list refreshed. Re-run with parameters to use a branch."
            }
        }

        stage('Ensure allowed branch') {
            when {
                expression { return !params.REFRESH_BRANCHES }
            }
            steps {
                script {
                    def selected = params.BranchName ?: ((env.BRANCH_NAME ?: env.GIT_BRANCH) ? (env.BRANCH_NAME ?: env.GIT_BRANCH).tokenize('/').last() : null)
                    if (!selected) {
                        error "Cannot determine selected branch. Pipeline allowed only on 'master'."
                    }
                    if (selected != 'master') {
                        error "Pipeline is allowed only on 'master'. Current selected branch: ${selected}"
                    }
                    echo "Branch validation passed: ${selected}"
                }
            }
        }

        stage('Checkout') {
            when {
                expression { return !params.REFRESH_BRANCHES }
            }
            steps {
                checkout([$class: 'GitSCM',
                    branches: [[name: "*/${params.BranchName}"]],
                    userRemoteConfigs: [[url: 'https://github.com/AymenGhazouani/Smart-Health-Tracker.git']]
                ])
            }
        }

        stage('Stop Old Containers') {
            when {
                expression { return !params.REFRESH_BRANCHES }
            }
            steps {
                sh 'docker compose down || true'
            }
        }

        stage('Build and Deploy') {
            when {
                expression { return !params.REFRESH_BRANCHES }
            }
            steps {
                sh 'docker compose up -d --build'
            }
        }

        stage('Run Migrations') {
            when {
                expression { return !params.REFRESH_BRANCHES }
            }
            steps {
                // Use exec -T for non-interactive runs; adjust if your service name differs.
                sh '''
                    # wait a bit for the app container to be healthy (adjust as needed)
                    sleep 5
                    docker compose exec -T app php artisan migrate --force || docker compose run --rm app php artisan migrate --force
                '''
            }
        }

        stage('Show Running Containers') {
            when {
                expression { return !params.REFRESH_BRANCHES }
            }
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
            sh 'docker compose logs || true'
        }
    }
}
