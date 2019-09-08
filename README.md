# user-management
InterNation code challenge for a User Management system


###### Problem description
We have designed a coding task that is intentionally vague and open-ended in its specification
so that you have the opportunity to take it in almost any direction you wish.


###### Tasks
- As an admin I can add users — a user has a name.
- As an admin I can delete users.
- As an admin I can assign users to a group they aren’t already part of.
- As an admin I can remove users from a group.
- As an admin I can create groups.
- As an admin I can delete groups when they no longer have members.
- Show me a small domain model for the processes above (in UML or anything else).
- Show me a database model.
- Design a convenient API that other developers would love to use for the tasks above.


## Solution

Starting from the last three items in the taks list:

###### Show me a small domain model for the processes above (in UML or anything else).
A UML diagram has been included in a PDF file (UML.pdf) which is available when you clone the repository

###### Show me a database model.
A sample database is include in the `DB` directory `intenation.sql`, import the database or symply run `php artisan migrate` to create tables. Note that when runing the migration command you would have configured your database connection details in the `.env` file (explained the How to run the application section)

###### Design a convenient API that other developers would love to use for the tasks above.
API documentation has been included in a Postman collection which can be found in [Postman API collection] (https://documenter.getpostman.com/view/139351/SVmpWMgL?version=latest)

To consume the API, the admin of this system will create API user which will generate an organization code and API access key - described in the API documentation.

## How to run the application

This code challenge was built with Laravel 6.0 (PHP framework).

###### Step to run the application
You must have PHP >= 7.2 installed, Composer, a web server (e.eg Apache or Nginx), MySql and Git on your machine

- Clone this repository `https://github.com/michelkal/user-management.git`
- `cd user-management`
- `composer update`
- Rename `.env.example` to `.env`
- Edit `.env` to include your database configuration as seen here 

`DB_CONNECTION=mysql`
`DB_HOST=127.0.0.1`
`DB_PORT=3306`
`DB_DATABASE=Your_DB_Name`
`DB_USERNAME=Your_DB_User`
`DB_PASSWORD=Your_DB_Password`

- Migrate the DB table by running `php artisan migrate`, this will create all the tables used in this application and can be found in `database/migrations` directory.

- The above command will create a default admin user with the following login details
Username `admin@internation.com`
Password `admin123`
and a default role will be created as `admin`, this is to enable the above user to be able to create users, groups, roles and API users when logged in.

###### Start the application

Run `php artisan serve` to run the application. If everything was set up properly, you should have 
`Laravel development server started: <http://127.0.0.1:8000>`

If you wish to start the application in other port than `8000` you can add the `--port` flag to the command above. E.g `php artisan serve --port=9000`


###### Login

Browse (http://127.0.0.1:8000) and login. Now you can Add User, Create Role, Create Group, Delete/Edit User, Delete Group, Delete/Role, Create API access token that willl be used in the API

## NEXT
No next - Check out the application and also consume the API and ejnoy :)

