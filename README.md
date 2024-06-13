<p>
    <h1>Foodics Coding Challenge</h1>

## About Project
This project is a small demo for creating order and managing inventory stocks.

## Project Deployment

Steps on how to deploy (Make sure docker-compose is installed on your machine):
- Clone project on your local machine
- Open the command line and change the current directory to the project directory
- Create <b>[.env](./.env)</b> file and copy it's content from the <b>[.env.deploy](./deploy-docker/.env.deploy)</b>
- Update mail environment variables to actually receive the mail (I used MailTrap sandbox mode to test the mails)
- Run Command ```docker-compose up -d```
- Enjoy! :star_struck:

<b>**Note</b> The main process of processing orders are managed using background jobs. This was done to apply queueable order processing and to avoid any race condition on the stocks records.

<b>**Note</b> The deployment seeds the database automatically. Please refer to the <b>[run.sh](./deploy-docker/run.sh)</b> bash file. It contains the commands that the docker container executes after creating the container. 

The project will install 2 docker containers:
- **MySQL Container**
- **Laravel Application Container**

To access any of the containers run ```docker exec -it {container-name} bash```

To Run Unit Tests:
- Access the Application Container ```docker exec -it foodics-api bash```
- Run the tests command ```./vendor/bin/phpunit```
- The testing environment uses on SQL Lite and runs in memory

## What is Implemented
- Docker Containers & Deployment (Application Container - Database Container)
- Application Configuration
- Control - Service - Repository Architecture
- Database Design & Data [Migrations](./database/migrations)
- Supervisor & Queue worker for managing background jobs
- [Factories](./database/factories) & [Seeders](./database/seeders)
- Clean Code
  - Custom [Exceptions](./app/Exceptions)
  - Entities [Controllers](./app/Http/Controllers)
  - Entities [Services](./app/Services)
  - Entities [Repositories](./app/Repositories)
  - Entities [Models](./app/Models)
  - Entities [Creation & Response Units](./app/Units)
  - Constant [Utility Classes](./app/Utils)
  - Dispatched [Jobs](./app/Jobs)
- Unit & Feature [Tests](./tests)
- Readme [File](./README.md)

## Project Architecture

The project is developed using **Controller - Service - Repository architecture**
- **Controller Layer**: Gather data from request, performs validation and pass user input data to service.
- **Service Layer**: The middleware between controller and repository. It is responsible for gathering data from controllers, performing business logic, and calling repositories for data manipulation.
- **Repository Layer**: Layer for interaction with models and performing DB operations.

This provides a clear separation of responsibilities and achieve many degrees of the **SOLID Principles** which reduces dependencies and make the project better in readability, maintainability, design patterns, and testability.

Each of this layer has its own **Abstract Base Class** which provides the common operations for this layer. 
- **BaseModel**
- **BaseRepository**
- **BaseService**
- **BaseController**

Also, you can find different code structures for single responsibilities such as:
- **Exceptions**
- **Units**
- **Utils**
- **..etc**
