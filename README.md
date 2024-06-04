 <div align="center">
  <h1>  GoldADY Application </h1>
</div>


# 📋 Objective

The objective of this assessment is to evaluate your proficiency in setting up a Laravel project, designing and implementing a database schema, creating RESTful API endpoints for CRUD operations, and incorporating user authentication functionalities, including registration and login.

# 📜 Task Description

In this task, you are required to set up a Laravel project for a blog application. The application should have two main entities: posts and categories. In addition to the previously mentioned tasks, you need to implement user registration and login functionalities. Your primary responsibilities include:

Authentication Endpoints :
 - Implement API endpoints for user authentication.
API Endpoints for Posts :
 - Implement API endpoints for CRUD operations on posts.
API Endpoints for Categories : 
 - Implement API endpoints for CRUD operations on categories.
Additional Features :
 - Implement logging for post creation, update, and deletion operations. Log entries should include relevant details for 
   auditing purposes.
 - Enhance the GET /api/posts endpoint to include information about the user who created each post.
Test Cases :
 - Write comprehensive unit tests for all API endpoints, ensuring functionality, edge cases, and error handling are covered.
 - Include tests for user authentication, post operations, category operations, and logging.

   
# 🛠️ Technologies Used
 - Laravel
 - PHP 
 - MySQL 
 - PHPUnit for testing
   
# 🔧 Prerequisites
 - PHP 
 - Composer
 - MySQL 
 - Laravel Installer (optional)
   
# 🚀 Installation

- Clone the repository
   
       git clone https://github.com/mohamed775/GOLDADY.git
       cd GoldADY

- Install dependencies
  
       composer install

- Create a copy of the .env file

       cp .env.example .env

- Generate an application key

       php artisan key:generate
  
- Set up the database

  - Update your .env file with your database credentials
    
         DB_CONNECTION=mysql
         DB_HOST=127.0.0.1
         DB_PORT=3306
         DB_DATABASE=your_database
         DB_USERNAME=your_username
         DB_PASSWORD=your_password
      
- Run database migrations

          php artisan migrate

# ▶️ Running the Project
- Serve the application
  
        php artisan serve
  
Access the application
 - Open your browser and go to http://localhost:8080

# 📚 API Endpoints

Authentication Endpoints :
 - Register
     - POST /api/register
     - Request Body:

            {
            "name": "username",
            "email": "username@example.com",
            "password": "password",
            "password_confirmation": "password"
            }
- Login

  - GET /api/login
  - Request Body:

          {
          "email": "username@example.com",
          "password": "password"
          }
  - Logout
     - POST /api/logout
       
- API Endpoints for Posts
  - Fetch All Posts
      - GET /api/posts
  - Fetch Specific Post
     - GET /api/posts/{id}
  - Add Post
     - POST /api/posts
     - Request Body:

            {
            "category_id": 1,
            "title": "Post Title",
            "content": "Post content"
            }
       
- Update Post

   - PUT /api/posts/{id}
   - Request Body:

         {
         "title": "Updated Title",
         "content": "Updated content"
         }
  - Delete Post
     - DELETE /api/posts/{id}

  - API Endpoints for Categories
      - Fetch All Categories
          - GET /api/categories
     - Fetch Specific Category
          - GET /api/categories/{id}
     - Add Category
         - POST /api/categories
     - Request Body:

           {
           "name": "Category Name"
           }
- Update Category

  - PUT /api/categories/{id}
  - Request Body:

        {
        "name": "Updated Category Name"
        }
- Delete Category

  - DELETE /api/categories/{id}
 

# 🧪 Testing

- Run the following command to execute the test cases:

         php artisan test
  
# 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.
