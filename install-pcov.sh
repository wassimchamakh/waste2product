#!/bin/bash
# Install PCOV extension for PHP code coverage on Jenkins server
# Run this script on your Jenkins server (Ubuntu)

echo "🔍 Checking for PCOV extension..."

if php -m | grep -q pcov; then
    echo "✅ PCOV is already installed!"
    php -m | grep pcov
    exit 0
fi

echo "📦 Installing PCOV extension..."
sudo apt-get update
sudo apt-get install -y php8.2-pcov

echo ""
echo "✅ PCOV installation complete!"
echo ""
echo "🔍 Verifying installation..."
php -m | grep pcov

echo ""
echo "✅ All done! PCOV is now available for code coverage."
echo "📝 Next Jenkins build will use PCOV for coverage reporting."
