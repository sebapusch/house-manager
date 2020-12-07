# house-manager
house manager REST api based on Phalcon 4.0.0

### Installation
#### Requirements
- Php 7.4 or higher
- Mysql
#### Process
1. Import the database dumb located inside the database folder
2. Clone the project  
3. Install composer dependencies
4. Fill the .env file with required information
5. Open terminal where you cloned the project and run `php -S localhost:{port} -t public .htrouter.php`

### Usage
#### Requests
**Available roots**

| Method    | Route                   | Parameters                                                  | Action                                               | 
|-----------|-------------------------|-------------------------------------------------------------|------------------------------------------------------|
| `POST`    | `login`                 | `username`, `password`                                      | Login - get Token. 401 if credentials are wrong      |
| `POST`    | `houses`                | `street`, `number`, `addition`, `zipcode`, `city`, `[rooms]`| Add a house record in the database                   |
| `GET`     | `houses?{filter}`       |  `{filters}`                                                | Get houses. Empty resultset if no data present       |
| `GET`     | `houses/{id}`           |                                                             | Get house by Id.404 if record does not exist         |
| `PUT`     | `houses/{id}`           | `name`, `description`, `services`, `type`                   | Update company by id. 404 if record does not exist   |
| `DELETE`  | `houses/{id}`           |                                                             | Delete by id. 404 if record does not exist           |

 *Rooms:* `type['bedroom', 'toilet', 'bathroom', 'lving room', 'kitchen']`, `width`, `height`, `length`

 **Available filters**
 
| Filter                        | Type   | Description                        |
|-------------------------------|--------|------------------------------------|
| `search={value}`              | string | Search by word on any field        |
| `minimalToiletCount={value}`  | int    | filter houses with less then value |
|`minimalBedroomsCount={value}` | int    | filter houses with less then value |

**Authentication**
 
To authenticate you have to retrieve a [jwt](https://jwt.io/) access token trough the login route.
The access token has an expiration time. Once expired you have to request a new one by repeating the login process.
This token needs to set on every request inside the authorization header (examples below).
Not setting it will result in a 401 Unauthorized response code.

**Authorization**

*Roles:* `user`, `admin`

As a `user` you can perform all GET requests (view all or specific houses). 
You will also be able to insert new houses, edit and delete the houses inserted by yourself.
You are not authorized to update or delete houses not inserted by yourself.
Trying to do so will result in a 401 Unauthorized response code.

As an `admin` you can perform every available operation without restrictions. 

#### Requests
**Login**
- Request
```json
POST /login HTTP/1.1
Host: {hostname}
Content-Type: application/json
Content-Length: 53

{
  "username" : "admin",
  "password" : "password"
}
```
- Response
```json
HTTP/1.1 200 OK
connection: close
content-type: application/json; charset=UTF-8
date: Mon, 07 Dec 2020 10:21:26 GMT
host: {hostname}:{port}
status: 200 OK
x-powered-by: PHP/7.X.X

{ "accessToken":"{jwt}"}
```

**Get list of houses**
- Request
```json
 GET /houses HTTP/1.1
 Authorization: Bearer {jwt}
 Host: {hostname} 
```
- Response
```json
connection: close
content-type: application/json; charset=UTF-8
date: Sun, 06 Dec 2020 19:27:23 GMT
host: {hostname}:{port}
status: 200 OK
x-powered-by: PHP/7.X.X

{
    "houses" : [
        {
            "id" : 1,
            "street" : "example street",
            "number" : 100,
            "addition": null,
            "zipcode" : "12345 A",
            "user" : "Gandalf" 
        }, 
        {
             "id": 3,
             "street": "second example street",
             "number": 123,
             "addition": null,
             "zipcode": "54321",
             "city": "Genova",
             "user": "sebastian"
        }
    ] 
}
```
**Insert new house**
- Request
```json
POST /houses HTTP/1.1
Authorization: Bearer {jwt}
Host: {hostname}
Content-Type: application/json
Content-Length: 305

{
    "city": "Genova",
    "street" : "example street",
    "number" : 100,
    "addition": null,
    "zipcode" : "12345 A",
    "user" : "Gandalf",
  	"rooms" : [
        {"type" : "toilet", "width" : 2, "height" : 2, "length" : 2},
        {"type" : "toilet", "width" : 3, "height" : 2, "length" : 3}
    ]
}
```
- Response
```json
HTTP/1.1 200 OK
connection: close
content-type: application/json; charset=UTF-8
date: Sun, 06 Dec 2020 19:58:00 GMT
host: {hostname}:{port}
status: 200 OK
x-powered-by: PHP/7.X.X

{
    "house" : {
        "city": "Genova",
        "street" : "example street",
        "number" : 100,
        "addition": null,
        "zipcode" : "12345 A",
        "user" : "Gandalf"
    }, 
    "rooms" : [
        {"type" : "toilet", "width" : 2, "height" : 2, "length" : 2},
        {"type" : "toilet", "width" : 3, "height" : 2, "length" : 3}
    ]
}
```
**Update a house by id**
> Update requests are the same as insert, changing the verb to PUT and by specifying the id of the house to update. 
> When doing a PUT request, all the passed data will entirely replace the already existing data; 
> the same goes for correlated rooms. 
> So if you want to keep a specific already existing room, by leaving it out of the request it will be deleted,
> so remember to put it in the update request.

### Database
#### Structure
**Tables**: 
- users 
- houses
- rooms 

**Relationships:**
- users:houses : 1:N
- houses:rooms : 1:N

#### Test Data
The database dump is filled with roughly 40 users, 150 houses and 1000 rooms.
This means every user is the owner of ca. 3-4 houses.

**User credentials**
- admin
    - username: admin
    - password: password
- users
    - username: user[0-48]
    - password: password

