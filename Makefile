.DEFAULT_GOAL := help

.PHONY: help check deploy

help:
	@echo "Available commands:"
	@echo ""
	@echo "  make check   - Run code formatting checks and tests (Pint, Tests, ESLint, Prettier)"
	@echo "  make deploy  - Full deployment: check → build → commit → push"
	@echo ""

check:
	@echo "🔍 Running code checks..."
	@echo "📝 Formatting PHP code with Pint..."
	@vendor/bin/pint
	@echo "🧪 Running PHP tests..."
	@php artisan test
	@echo "🔧 Linting JavaScript/TypeScript code..."
	@npm run lint
	@echo "💅 Formatting frontend code with Prettier..."
	@npm run format
	@echo "✅ All checks passed!"

deploy: check
	@echo "🚀 Starting deployment..."
	@echo "📦 Building frontend assets..."
	@npm run build
	@echo "📝 Adding files to git..."
	@git add .
	@echo -n "💬 Please enter your commit message: "; \
	read -r msg; \
	git commit -m "$$msg"
	@echo "📤 Pushing to remote..."
	@git push
	@echo "✅ Deployment complete!"

