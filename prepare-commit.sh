#!/bin/bash

# =================================================================
# Prepare Repository for Commit
# =================================================================
# This script prepares all deployment files for GitHub commit
# =================================================================

echo "🚀 Preparing Minecraft Hosting Billing for GitHub..."

# Make scripts executable
chmod +x install-one-command.sh
chmod +x update-system.sh
chmod +x quick-deploy.sh
chmod +x docker-deploy.sh
chmod +x deploy.sh

# Create .gitignore additions for deployment
cat >> .gitignore << 'EOF'

# Deployment files (keep tracked)
# install-one-command.sh
# update-system.sh
# deploy.sh
# quick-deploy.sh
# docker-deploy.sh
# docker-compose.yml
# DEPLOYMENT.md
# version.json

# Local deployment configs
deploy.conf
.installation-info
EOF

echo "✅ Repository prepared for commit!"
echo
echo "🎯 Next steps:"
echo "1. git add ."
echo "2. git commit -m 'Add one-command installation and update system'"
echo "3. git push origin main"
echo
echo "🚀 After pushing, users can install with:"
echo "curl -fsSL https://raw.githubusercontent.com/anshpatel2/minecraft-hosting-billing/main/install-one-command.sh | sudo bash -s -- domain.com email@domain.com"
