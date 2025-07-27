# Yii 2 Advanced Project with Docker

This is a Yii 2 Advanced Project Template with a Docker setup for local development.

## Prerequisites

- [Docker](https.docs.docker.com/get-docker/)
- [Docker Compose](https.docs.docker.com/compose/install/)

## Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yiisoft/yii2-app-advanced.git
   cd yii2-app-advanced
   ```

2. **Initialize the project:**

   Run the `init` script to initialize the application with a specific environment.

   ```bash
   ./init
   ```

   Select `dev` for development environment.

3. **Install dependencies with Composer:**

   You can run composer inside the container to avoid installing it on your host machine.

   ```bash
   docker-compose run --rm frontend composer install
   ```

4. **Apply database migrations:**

   ```bash
   docker-compose run --rm frontend yii migrate
   ```

## Running the application

To start the application, run:

```bash
docker-compose up -d
```

- The frontend will be available at `http://localhost:20080`
- The backend will be available at `http://localhost:21080`

To stop the application, run:

```bash
docker-compose down
```

## Running tests

To run the tests, you can use the following commands:

- **Common tests:**

  ```bash
  docker-compose run --rm frontend vendor/bin/codecept run -c common
  ```

- **Frontend tests:**

  ```bash
  docker-compose run --rm frontend vendor/bin/codecept run -c frontend
  ```

- **Backend tests:**

  ```bash
  docker-compose run --rm backend vendor/bin/codecept run -c backend
  ```
