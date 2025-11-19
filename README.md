# Refada Project

**refada** is a comprehensive digital solutions platform that provides innovative technical services for government entities, private organizations, and individuals. The platform specializes in programming and development services for websites, applications, and digital platforms, delivering exceptional user experiences with professional interfaces.

## 🌟 About Refada

refada Digital focuses on delivering smart technical solutions through:
- **Creative Programming Services** - Building innovative software solutions
- **Performance Enhancement** - Optimizing and improving software performance
- **Professional Application Development** - Creating applications with the highest standards
- **Superior Service Delivery** - Providing top-level service, performance, and security

This repository contains the **Dockerized Laravel application** that powers the refada platform,. The setup is designed for easy development and deployment using **Docker Compose**.

## 📑 Table of Contents

- [Prerequisites](#prerequisites)
- [Project Structure](#project-structure)
- [Setup Instructions](#setup-instructions)

## Prerequisites

Make sure you have the following installed:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Git](https://git-scm.com/)

## Project Structure

This project is containerized using Docker and served via Nginx. It includes a Laravel backend and optional Node.js frontend assets managed through Vite.


## Setup Instructions

### 1. Clone the Repository

```bash
git clone <your-repo-url>
cd <your-repo-directory>
```


### 2. Setup Environment

Copy the environment file to create a `.env` file in the directory:

```bash
cp .env.example .env
```

Then update the database configurations DB_* to a mysql database

### 3. Build and Run the Application

Start the application in detached mode:

```bash
docker compose -f compose.dev.yaml up -d 
```

The `-d` flag runs the containers in detached mode (in the background).

### 4. Access the Application

Open your browser and visit:

[http://localhost:8000](http://localhost:8000)

### 5. Stop the Application

To stop and remove all running containers and networks:

```bash
docker compose -f compose.dev.yaml down -v 
```


---

## PS: Run Custom Commands

To run custom command you need to pass the command to the running container:

```bash
docker exec -it refada-local <your command>
```

And here an example:
```bash
docker exec -it refada-local ls
```
to  enter the shell inside the container:

```bash
docker exec -it refada-local bash
```
---


## 🚀 Ready to Launch!

Your  Laravel application is now ready to run! Follow the setup instructions above and you'll have your Dockerized Laravel app up and running in no time. Happy coding! 🚀 