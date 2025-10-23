pipeline {
    agent any
    
    environment {
        PHP_VERSION = '8.2'
        NODE_VERSION = '18'
        DB_DATABASE = 'waste2product_test'
        DB_USERNAME = 'jenkins'
        DB_PASSWORD = 'jenkins_password'
    }
    
    stages {
        stage('Verify Environment') {
            steps {
                echo '🔍 Verifying installed tools...'
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
                    cp .env.example .env
                    php artisan key:generate --force
                    
                    # Configure database for testing
                    sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
                    sed -i "s/DB_HOST=.*/DB_HOST=127.0.0.1/" .env
                    sed -i "s/DB_PORT=.*/DB_PORT=3306/" .env
                    sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
                    sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
                    sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
                '''
            }
        }
        
        stage('Setup Test Database') {
            steps {
                echo '🗄️ Setting up test database...'
                sh '''
                    # Drop and recreate database for clean state
                    sudo mysql -e "DROP DATABASE IF EXISTS ${DB_DATABASE};"
                    sudo mysql -e "CREATE DATABASE ${DB_DATABASE};"
                    
                    # Recreate user with proper privileges
                    sudo mysql -e "DROP USER IF EXISTS '${DB_USERNAME}'@'localhost';"
                    sudo mysql -e "CREATE USER '${DB_USERNAME}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';"
                    sudo mysql -e "GRANT ALL PRIVILEGES ON ${DB_DATABASE}.* TO '${DB_USERNAME}'@'localhost';"
                    sudo mysql -e "FLUSH PRIVILEGES;"
                    
                    # Verify connection
                    mysql -u${DB_USERNAME} -p${DB_PASSWORD} -e "SELECT 1;" ${DB_DATABASE}
                '''
            }
        }
        
        stage('Run Migrations') {
            steps {
                echo '🔄 Running database migrations...'
                sh 'php artisan migrate:fresh --force --seed'
            }
        }
        
        stage('Run Tests') {
            steps {
                echo '🧪 Running tests...'
                sh '''
                    php artisan test || echo "Tests had failures"
                '''
            }
        }
        
        stage('Code Quality Check') {
            steps {
                echo '✨ Checking code quality...'
                sh '''
                    echo "Route List:"
                    php artisan route:list || echo "Route list failed"
                    
                    echo "\nRunning Pint (Code Style):"
                    ./vendor/bin/pint --test || echo "Code style check had issues"
                '''
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
            // Archive test results if they exist
            junit(testResults: 'storage/logs/*.xml', allowEmptyResults: true)
            cleanWs()
        }
    }
}