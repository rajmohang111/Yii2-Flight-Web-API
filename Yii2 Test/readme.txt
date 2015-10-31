#Yii2 Framework Airline web API Test

Install Composer Packages

```
composer install
```

#Import Database
Run following script

```
Yii2 Backend Test\DB Script
```

Application Description
======================
Application creates restful web API for following operation
1.List all airports
2.Get details of specific airport
3.Search for airport using airport code
4.Delete specific airport
5.Delete all airport

application uses airport table containing following fields.
id
airport_code
airport_name
country
city

created yii model using above fields.

application contains AirportController class to manage all CRUD operations.

application used Yii2 role based access control

following tables are added for managing access control.

auth_assignment
auth_item
auth_item_child
auth_rule

application contains following access
-------------------------------------
Create-Airport
Delete-Airport
Update-Airport
admin - full acess

Using auth_assignment table we are assigning access to users


Following are the web API URL
============================

Get all airports
----------------

http://localhost/Yii2/api/web/v1/airports

Method : GET

Get airport details by ID
----------------

http://localhost/Yii2/api/web/v1/airports/1

Method : GET

Search airport by airport code
----------------

http://localhost/Yii2/api/web/v1/airports?airport_code=DBX

Method : GET

Create new airport
-------------------

http://localhost/Yii2/api/web/v1/airports

Method: POST

Input parameters 
--------------

{
"id" : 1,
"airport_code":"DBX",
"airport_name":"Dubai Airport",
"country": "UAE",
"city": "Dubai"
}

Update Airport
--------------
http://localhost/Yii2/api/web/v1/airports

Method: POST

Input parameters 
--------------

{
"id" : 1,
"airport_code":"DBX",
"airport_name":"Dubai Airport",
"country": "UAE",
"city": "Dubai"
}

Delete Airport
--------------

http://localhost/Yii2/api/web/v1/airports/1

Method: DELETE

Delete all Airport
--------------

http://localhost/Yii2/api/web/v1/airports/deleteall

Method: POST
