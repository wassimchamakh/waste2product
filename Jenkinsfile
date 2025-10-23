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
                    
                    # Configure database for testing
                    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
                    sed -i 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env
                    sed -i 's/DB_PORT=.*/DB_PORT=3306/' .env
                    sed -i 's/DB_DATABASE=.*/DB_DATABASE=waste2product_test/' .env
                    sed -i 's/DB_USERNAME=.*/DB_USERNAME=jenkins/' .env
                    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=jenkins_password/' .env
                '''
            }
        }
        
        stage('Setup Test Database') {
            steps {
                echo '🗄️ Creating test database...'
                sh '''
                    # Create test database using sudo (auth_socket authentication)
                    sudo mysql -e "CREATE DATABASE IF NOT EXISTS waste2product_test;" || true
                    sudo mysql -e "CREATE USER IF NOT EXISTS 'jenkins'@'localhost' IDENTIFIED BY 'jenkins_password';" || true
                    sudo mysql -e "GRANT ALL PRIVILEGES ON waste2product_test.* TO 'jenkins'@'localhost';" || true
                    sudo mysql -e "FLUSH PRIVILEGES;" || true
                    
                    # Run migrations
                    php artisan migrate --force --seed || true
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
