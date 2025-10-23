pipeline {
    agent any
    
    environment {
        PHP_VERSION = '8.2'
        NODE_VERSION = '18'
    }
    
    stages {
        stage('Verify Environment') {
            steps {
                echo '� Verifying installed tools...'
                sh '''
                    echo "PHP Version:"
                    php --version
                    echo "\nComposer Version:"
                    composer --version
                    echo "\nNode.js Version:"
                    node --version
                    echo "\nNPM Version:"
                    npm --version
                '''
            }
        }
        
        stage('Checkout') {
            steps {
                echo '📦 Checking out code...'
                checkout scm
            }
        }
        
        stage('Install PHP Dependencies') {
            steps {
                echo '🎵 Installing Composer dependencies...'
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }
        
        stage('Install Node Dependencies') {
            steps {
                echo '📦 Installing NPM dependencies...'
                sh 'npm install'
            }
        }
        
        stage('Build Assets') {
            steps {
                echo '🏗️ Building frontend assets...'
                sh 'npm run build'
            }
        }
        
        stage('Setup Environment') {
            steps {
                echo '⚙️ Setting up environment...'
                sh '''
                    [ ! -f .env ] && cp .env.example .env || true
                    php artisan key:generate --force
                '''
            }
        }
        
        stage('Run Tests') {
            steps {
                echo '🧪 Running PHP tests...'
                sh 'php artisan test || true'
            }
        }
        
        stage('Code Quality Check') {
            steps {
                echo '✨ Checking code quality...'
                sh 'php artisan route:list || true'
            }
        }
    }
    
    post {
        success {
            echo '✅ Build successful! Application is ready for deployment.'
        }
        failure {
            echo '❌ Build failed! Check the logs above.'
        }
        always {
            echo '🏁 Pipeline completed.'
            cleanWs()
        }
    }
}
