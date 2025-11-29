# API Chat - WhatsApp Clone API

A robust backend API for a real-time chat application, built with Laravel 10. This project serves as the core infrastructure for a WhatsApp-like messaging platform, supporting one-on-one chats, group conversations, and file attachments.

## 🚀 Key Features

*   **Authentication & Security**: Secure user authentication using Laravel Sanctum, including registration, login, password reset, and email verification.
*   **Real-time Messaging**: Powered by `beyondcode/laravel-websockets` and Pusher for instant message delivery.
*   **Group Chats**: Full support for creating groups, managing members, and group messaging.
*   **File Attachments**: Capability to send and download files within chats.
*   **User Management**: Endpoints for updating user profiles and retrieving user lists.
*   **Vault/Credentials**: Integrated features for managing secure credentials (password manager functionality).
*   **API Documentation**: Auto-generated API documentation using `rakutentech/laravel-request-docs`.

## 🛠️ Tech Stack

*   **Framework**: [Laravel 10](https://laravel.com)
*   **Language**: PHP 8.1+
*   **Real-time**: Laravel WebSockets, Pusher
*   **Authentication**: Laravel Sanctum
*   **Build Tool**: Vite

## 📦 Installation

1.  **Clone the repository**
    ```bash
    git clone https://github.com/dergahab/whatsapp_clone_api.git
    cd api-chat
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies**
    ```bash
    npm install
    ```

4.  **Environment Setup**
    Copy the example environment file and configure your database and websocket settings.
    ```bash
    cp .env.example .env
    ```
    Update `.env` with your database credentials and other configurations.

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations**
    ```bash
    php artisan migrate
    ```

7.  **Start the Server**
    ```bash
    php artisan serve
    ```

8.  **Start WebSockets (if applicable)**
    ```bash
    php artisan websockets:serve
    ```

## 🏗️ Code Architecture

This project follows a modular and service-oriented architecture to ensure scalability and maintainability.

### 📂 Directory Structure Highlights

*   **`app/Http/Controllers`**: Handles incoming HTTP requests and returns responses. Controllers are kept thin by delegating business logic to Services.
*   **`app/Http/Requests`**: Manages form validation. Each request has a dedicated class to ensure data integrity before it reaches the controller.
*   **`app/Http/Resources`**: Transforms models into JSON responses, ensuring a consistent API output format.
*   **`app/Services`**: Contains the core business logic. Services isolate complex operations from controllers, making the code reusable and easier to test.
*   **`app/Repositories`**: Handles data access logic. This layer abstracts database interactions, allowing for cleaner code and easier swapping of data sources if needed.
*   **`app/Policies`**: Manages authorization logic, defining who can perform specific actions on resources.

### 🔄 Request Lifecycle

1.  **Route**: The request hits a route defined in `routes/api.php`.
2.  **Middleware**: Authentication and other checks are performed (e.g., `auth:sanctum`).
3.  **Request Validation**: The incoming data is validated using a Form Request class.
4.  **Controller**: The controller receives the validated request.
5.  **Service**: The controller calls a Service method to perform the business logic.
6.  **Repository**: The Service interacts with a Repository to fetch or persist data.
7.  **Resource**: The result is transformed into a JSON response using an API Resource.
8.  **Response**: The final JSON response is sent back to the client.

## 🔌 API Endpoints

The API provides a comprehensive set of endpoints. Here are some of the main routes:

*   **Auth**: `/api/login`, `/api/user_register`, `/api/logout`
*   **Users**: `/api/users`, `/api/users/{uuid}`
*   **Chats**: `/api/chats`, `/api/messages`
*   **Groups**: `/api/groups`, `/api/group-messages`
*   **Attachments**: `/api/fileDownload/{uuid}`

For full documentation, ensure `rakutentech/laravel-request-docs` is configured and visit the documentation route (usually `/request-docs`).

## 🤝 Contributing

Contributions are welcome! Please fork the repository and submit a pull request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
