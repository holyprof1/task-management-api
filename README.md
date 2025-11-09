# Task Management API

A Laravel 11 RESTful API for managing tasks with secure user authentication using Laravel Sanctum.

## Project Overview

This API provides a complete task management system where users can register, authenticate, and manage their personal tasks. Each user has isolated access to only their own tasks, ensuring data privacy and security.

## Requirements Met

### 1. Entities
✅ **User** - name, email, password  
✅ **Task** - title, description, status (pending/in-progress/completed), user_id

### 2. Core Functionality
✅ User registration and login with Laravel Sanctum authentication  
✅ Complete CRUD operations for tasks  
✅ Task access restricted to authenticated users only  
✅ Comprehensive input validation  
✅ Proper error handling with meaningful messages

### 3. Bonus Features Implemented
✅ Filter tasks by status (pending, in-progress, completed)  
✅ Pagination on task listings (10 items per page)

## Technology Stack

- **Framework:** Laravel 11
- **Authentication:** Laravel Sanctum (Token-based)
- **Database:** MySQL
- **PHP:** 8.2+

## Installation Instructions

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL database
- Git

### Setup Steps

1. **Clone the repository**
```bash
   git clone <your-repository-url>
   cd task-api
```

2. **Install dependencies**
```bash
   composer install
```

3. **Configure environment**
```bash
   cp .env.example .env
```

4. **Update database credentials in `.env`**
```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_api
   DB_USERNAME=root
   DB_PASSWORD=
```

5. **Generate application key**
```bash
   php artisan key:generate
```

6. **Create the database**
   
   Create a MySQL database named `task_api`

7. **Run database migrations**
```bash
   php artisan migrate
```

8. **Start the development server**
```bash
   php artisan serve
```

The API will be accessible at `http://127.0.0.1:8000`

## API Endpoints

### Base URL
```
http://127.0.0.1:8000/api
```

### Authentication Endpoints

#### Register User
```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "access_token": "4|w5IB1CkX7Bv07RD85rTfEZouz6j3LaRnUaYX7ET853e168f5",
  "token_type": "Bearer",
  "user": {
    "id": 4,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-11-08T04:29:53.000000Z",
    "updated_at": "2025-11-08T04:29:53.000000Z"
  }
}
```

#### Login
```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (200):**
```json
{
  "access_token": "5|zZO9BJ0rokUrkUNcmGuZgP7WX4qSFrU2eUoAcnlH7dde06ea",
  "token_type": "Bearer",
  "user": {
    "id": 4,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

#### Logout
```http
POST /api/logout
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Logged out successfully"
}
```

### Task Endpoints (Protected)

All task endpoints require authentication. Include the Bearer token in the Authorization header.

#### Get All Tasks (with pagination)
```http
GET /api/tasks
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "user_id": 4,
      "title": "Complete Laravel Assessment",
      "description": "Build task management API",
      "status": "in-progress",
      "created_at": "2025-11-08T04:12:01.000000Z",
      "updated_at": "2025-11-08T04:12:01.000000Z"
    }
  ],
  "per_page": 10,
  "total": 1
}
```

#### Filter Tasks by Status (Bonus Feature)
```http
GET /api/tasks?status=pending
Authorization: Bearer {token}
```

Available status values: `pending`, `in-progress`, `completed`

#### Create Task
```http
POST /api/tasks
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Complete project documentation",
  "description": "Write comprehensive API docs",
  "status": "pending"
}
```

**Response (201):**
```json
{
  "id": 1,
  "user_id": 4,
  "title": "Complete project documentation",
  "description": "Write comprehensive API docs",
  "status": "pending",
  "created_at": "2025-11-08T04:12:01.000000Z",
  "updated_at": "2025-11-08T04:12:01.000000Z"
}
```

#### Get Single Task
```http
GET /api/tasks/{id}
Authorization: Bearer {token}
```

#### Update Task
```http
PUT /api/tasks/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "completed"
}
```

#### Delete Task
```http
DELETE /api/tasks/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Task deleted successfully"
}
```

## Validation & Error Handling

### Validation Errors (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Authentication Errors (401)
```json
{
  "message": "Unauthenticated."
}
```

### Authorization Errors (403)
```json
{
  "message": "Unauthorized"
}
```

## Database Schema

### Users Table
- id (Primary Key)
- name (varchar)
- email (varchar, unique)
- password (varchar, hashed)
- created_at, updated_at (timestamps)

### Tasks Table
- id (Primary Key)
- user_id (Foreign Key → users.id, cascade on delete)
- title (varchar, required)
- description (text, nullable)
- status (enum: pending, in-progress, completed)
- created_at, updated_at (timestamps)
- **Indexes:** user_id, status, composite(user_id, status)

## Development Approach

### Architecture
- **MVC Pattern:** Clear separation between Models, Controllers, and business logic
- **RESTful Design:** Follows REST principles for intuitive API structure
- **Policy-Based Authorization:** Users can only access their own tasks

### Security Measures
- Password hashing using bcrypt
- Token-based authentication with Laravel Sanctum
- SQL injection protection via Eloquent ORM
- Input validation on all endpoints
- User-specific data isolation

### Code Quality
- Followed Laravel coding standards and best practices
- Clean, readable code with proper naming conventions
- Comprehensive error handling
- Proper HTTP status codes for all responses

## Testing the API

### Using Postman

1. **Register a new user** via `POST /api/register`
2. **Copy the access_token** from the response
3. **Add Authorization header** to all task requests:
```
   Authorization: Bearer {your_access_token}
```
4. **Test all CRUD operations** on tasks

### Using cURL

**Register:**
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'
```

**Login:**
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'
```

**Get Tasks:**
```bash
curl -X GET http://127.0.0.1:8000/api/tasks \
  -H "Authorization: Bearer {your_token}"
```

## Project Structure
```
task-api/
├── app/
│   ├── Http/Controllers/Api/
│   │   ├── AuthController.php
│   │   └── TaskController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Task.php
│   └── Policies/
│       └── TaskPolicy.php
├── database/migrations/
├── routes/api.php
└── README.md
```

## Key Features

✅ User registration with email validation  
✅ Secure login with credential verification  
✅ Token-based authentication (stateless)  
✅ Complete task CRUD operations  
✅ Task filtering by status (Bonus)  
✅ Pagination support (Bonus)  
✅ User-specific task access  
✅ Comprehensive validation  
✅ Proper error handling  
✅ Clean, maintainable code

## Conclusion

This API demonstrates proficiency in:
- Laravel framework fundamentals
- RESTful API design principles
- Authentication and authorization
- Database design and relationships
- Input validation and error handling
- Security best practices

The implementation is production-ready with proper validation, error handling, and security measures in place.

## License

MIT License

---

**Developed by holyprof for Afrifounders Startup Studio - Laravel Developer Assessment**
