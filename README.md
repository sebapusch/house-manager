# house-manager
house manager REST api based on Phalcon 4.0.0

### Features
##### JWT Tokens
Jwt are used for the authentication process. 
Every request (except for login) requires a bearer token to be set, obtained during the login process 

### Usage
#### Requests
**Available roots**

| Method    | Route                   | Parameters                                      | Action                                               | 
|-----------|-------------------------|-------------------------------------------------|------------------------------------------------------|
| `POST`    | `login`                 | `username`, `password`                          | Login - get Token. 401 if credentials are wrong      |
| `POST`    | `houses`                | `name`, `description`, `services`, `type`, rooms| Add a house record in the database                   |
| `GET`     | `houses?{filter}`       |  `filters (listed below)`                       | Get houses. Empty resultset if no data present       |
| `GET`     | `houses/{id}`           |                                                 | Get house by Id.404 if record does not exist         |
| `PUT`     | `houses/{id}`           | `name`, `description`, `services`, `type`       | Update company by id. 404 if record does not exist   |
| `DELETE`  | `houses/{id}`           |                                                 | Delete by id. 404 if record does not exist           |

 **Available filters**
 
| Filter                        | Type   | Description                        |
|-------------------------------|--------|------------------------------------|
| `search={value}`              | string | Search by word on any field        |
| `minimalToiletCount={value}`  | int    | filter houses with less then value |
|`minimalBedroomsCount={value}` | int    | filter houses with less then value |
#### Requests
**Get list of houses**<br>
----------------------
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

**Insert new house**<br>
--------------------
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
