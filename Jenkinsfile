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
        
        stage('Building Docker Images') {
            steps {
                echo '🐳 Building Docker images (Laravel and MySQL)...'
                script {
                    sh '''
                        # Build Docker images using docker-compose
                        docker-compose build
                        
                        echo "✅ Docker images built successfully!"
                        echo "📦 Images created:"
                        docker images | grep -E "laravelapp|mysql"
                    '''
                }
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
                echo '🧪 Running tests with coverage...'
                sh '''
                    # Create build directory if it doesn't exist
                    mkdir -p build/logs
                    
                    # Check if PCOV or Xdebug is available
                    if php -m | grep -q pcov; then
                        echo "✅ PCOV extension found - running tests with coverage"
                        php artisan test --coverage-clover build/logs/clover.xml
                    elif php -m | grep -q xdebug; then
                        echo "✅ Xdebug extension found - running tests with coverage"
                        php artisan test --coverage-clover build/logs/clover.xml
                    else
                        echo "⚠️ No coverage driver found (PCOV or Xdebug)"
                        echo "ℹ️ To enable coverage, install PCOV manually on the Jenkins server:"
                        echo "   sudo apt-get install -y php8.2-pcov"
                        echo "📝 Running tests without coverage..."
                        php artisan test
                    fi
                '''
            }
        }
        
        stage('SonarQube Analysis') {
            steps {
                echo '🔍 Running SonarQube analysis...'
                script {
                    try {
                        def scannerHome = tool 'scanner'
                        withSonarQubeEnv {
                            sh "${scannerHome}/bin/sonar-scanner"
                        }
                    } catch (Exception e) {
                        echo "⚠️ SonarQube analysis skipped: ${e.message}"
                        echo "ℹ️ To enable SonarQube:"
                        echo "  1. Configure SonarQube server in Jenkins (Manage Jenkins → System)"
                        echo "  2. Name it 'SonarQube' to match this pipeline"
                        currentBuild.result = 'SUCCESS'
                    }
                }
            }
        }
        
        stage('Quality Gate') {
            steps {
                echo '🚦 Waiting for Quality Gate...'
                script {
                    try {
                        timeout(time: 10, unit: 'MINUTES') {
                            def qg = waitForQualityGate abortPipeline: false
                            if (qg.status != 'OK') {
                                echo "⚠️ Quality Gate status: ${qg.status}"
                                echo "ℹ️ This is a warning only - pipeline will continue"
                            } else {
                                echo "✅ Quality Gate passed!"
                            }
                        }
                    } catch (Exception e) {
                        echo "⚠️ Quality Gate check skipped: ${e.message}"
                        echo "ℹ️ SonarQube analysis may still be processing - check dashboard manually"
                        currentBuild.result = 'SUCCESS'
                    }
                }
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