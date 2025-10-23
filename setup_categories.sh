#!/bin/bash

# Quick Setup Script for Category Management System
# This script helps you configure the necessary API keys

echo "================================================="
echo "  Category Management System - Quick Setup"
echo "================================================="
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found!"
    echo "Creating .env from .env.example..."
    cp .env.example .env
    php artisan key:generate
    echo "✅ .env file created"
    echo ""
fi

echo "📝 Configuration Required:"
echo ""
echo "1. Google Gemini API Key (FREE - for AI certification suggestions)"
echo "   Get your key at: https://makersuite.google.com/app/apikey"
echo ""
echo "2. Google Cloud Vision API (OPTIONAL - for advanced image recognition)"
echo "   Set up at: https://console.cloud.google.com/"
echo ""

# Prompt for Gemini API Key
echo "Enter your Gemini API Key (or press Enter to skip):"
read -r gemini_key

if [ ! -z "$gemini_key" ]; then
    # Update .env file
    if grep -q "^GEMINI_API_KEY=" .env; then
        sed -i "s|^GEMINI_API_KEY=.*|GEMINI_API_KEY=$gemini_key|" .env
    else
        echo "GEMINI_API_KEY=$gemini_key" >> .env
    fi
    echo "✅ Gemini API Key configured"
else
    echo "⏭️  Skipped Gemini API Key (you can add it later in .env)"
fi

echo ""
echo "================================================="
echo "  Next Steps:"
echo "================================================="
echo ""
echo "1. If you skipped API key setup, edit .env and add:"
echo "   GEMINI_API_KEY=your_key_here"
echo ""
echo "2. Create storage directory for category images:"
echo "   mkdir -p public/storage/categories"
echo ""
echo "3. Run migrations if not done:"
echo "   php artisan migrate"
echo ""
echo "4. Start the development server:"
echo "   php artisan serve"
echo ""
echo "5. Visit http://localhost:8000/admin/categories to start!"
echo ""
echo "📚 Full documentation: CATEGORY_MANAGEMENT_GUIDE.md"
echo ""
echo "✨ Setup complete!"
echo "================================================="
