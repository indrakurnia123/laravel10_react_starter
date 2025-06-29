# Copilot Instructions

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

## Project Overview
This is a Laravel + React boilerplate project with the following architecture:
- **Backend**: Laravel 10 with Sanctum authentication, Spatie permissions, and RESTful API
- **Frontend**: React 18 with TypeScript, Tailwind CSS, and React Router
- **Database**: MySQL with role-based access control and dynamic menu system

## Code Guidelines

### Backend (Laravel)
- Use Laravel Resource classes for API responses
- Implement proper validation using Form Request classes
- Follow Repository pattern for data access
- Use Service classes for business logic
- Implement proper error handling and logging
- Use Eloquent relationships efficiently

### Frontend (React + TypeScript)
- Use TypeScript for all components and services
- Implement proper type definitions for API responses
- Use React hooks for state management
- Follow component composition patterns
- Implement proper error boundaries
- Use proper loading states and user feedback

### API Design
- Follow RESTful conventions
- Use consistent JSON response structure
- Implement proper HTTP status codes
- Use API versioning (v1, v2, etc.)
- Implement proper pagination for list endpoints

### Security
- Always validate user permissions before API calls
- Use CSRF protection
- Implement rate limiting
- Sanitize user inputs
- Use proper authentication middleware

### Testing
- Write unit tests for business logic
- Write feature tests for API endpoints
- Write component tests for React components
- Use factories for test data

## File Naming Conventions
- **Laravel**: PascalCase for classes, snake_case for files and methods
- **React**: PascalCase for components, camelCase for hooks and utilities
- **Database**: snake_case for tables and columns
