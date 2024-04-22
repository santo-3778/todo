# Todo Management Application 

This is a simple web application to manage todos within projects. It allows users to create new projects and manage todos within each project. The application also provides functionality to export project summaries as secret gists on GitHub.

## Features

- User authentication using Basic Auth.
- Create, update, and delete projects.
- Add, edit, update,delete  and mark todos as complete or pending within each project.
- Export project summaries as secret gists on GitHub.

## Technologies Used

- Backend:
  - php core
  - Sql
- Frontend:
  - html
  - bootstrap
  - css
- Authentication:
  - Basic Auth 
- Other Tools:
  - GitHub API

## Installation

1. Clone the repository:

    ```bash
    git clone <repository-url>
    ```

2. Navigate to the project directory:

    ```bash
    cd todo-management-app
    ```

3. Install dependencies:

    install xamp (it contain php and sql).

4. Set up environment variables:
   import the mydb.sql file and copy all the others in the htdocs folder in xamp .

5. Run the application:

 Open your browser and navigate to `http://localhost:sample/index` to use the application.

## API Endpoints

- **Authentication:**
  - `/api/login` (POST)
- **Projects:**
  - `/api/projects` (GET, POST)
  - `/api/projects/:projectId` (GET, PUT, DELETE)
- **Todos:**
  - `/api/projects/:projectId/todos` (GET, POST)
  - `/api/projects/:projectId/todos/:todoId` (GET, PUT, DELETE)
- **Export Gist:**
  - `/api/projects/:projectId/export` (POST)

## Usage

1. Sign in/Sign up to the application using your username and password.
2. Create a new project from the home page.
3. Click on a project to view its details.
4. Add, update, or delete todos within the project.
5. Mark todos as complete or pending.
6. Export the project summary as a secret gist on GitHub.


