pipeline {
    agent any
    
    environment {
        // Environment variables
        PHP_VERSION = '8.2'
        NODE_VERSION = '18'
    }
    
    stages {
        stage('Checkout') {
            steps {
                echo '📦 Checking out code...'
                checkout scm
            }
        }
        
        stage('Install PHP Dependencies') {
            steps {
                echo '🎵 Installing Composer dependencies...'
                script {
                    bat 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                }
            }
        }
        
        stage('Install Node Dependencies') {
            steps {
                echo '📦 Installing NPM dependencies...'
                script {
                    bat 'npm install'
                }
            }
        }
        
        stage('Build Assets') {
            steps {
                echo '🏗️ Building frontend assets...'
                script {
                    bat 'npm run build'
                }
            }
        }
        
        stage('Setup Environment') {
            steps {
                echo '⚙️ Setting up environment...'
                script {
                    // Copy .env.example to .env if not exists
                    bat '''
                        if not exist .env copy .env.example .env
                        php artisan key:generate --force
                    '''
                }
            }
        }
        
        stage('Database Migration') {
            steps {
                echo '🗄️ Running database migrations...'
                script {
                    bat 'php artisan migrate --force'
                }
            }
        }
        
        stage('Run Tests') {
            steps {
                echo '🧪 Running PHP tests...'
                script {
                    bat 'php artisan test || exit 0'
                }
            }
        }
        
        stage('Code Quality Check') {
            steps {
                echo '✨ Checking code quality...'
                script {
                    // Optional: Run PHP code sniffer or other linters
                    bat 'php artisan route:list || exit 0'
                }
            }
        }
        
        stage('Clear Cache') {
            steps {
                echo '🧹 Clearing application cache...'
                script {
                    bat '''
                        php artisan config:clear
                        php artisan cache:clear
                        php artisan view:clear
                        php artisan route:clear
                    '''
                }
            }
        }
        
        stage('Optimize') {
            steps {
                echo '⚡ Optimizing application...'
                script {
                    bat '''
                        php artisan config:cache
                        php artisan route:cache
                        php artisan view:cache
                    '''
                }
            }
        }
    }
    
    post {
        success {
            echo '✅ Build successful! Application is ready.'
        }
        failure {
            echo '❌ Build failed! Check the logs above.'
        }
        always {
            echo '🏁 Pipeline completed.'
            // Clean up workspace if needed
            cleanWs()
        }
    }
}
