# house-manager
house manager REST api based on Phalcon 4.0.0

### Features
##### JWT Tokens
Jwt are used for the authentication process. 
Every request (except for login) requires a bearer token to be set, obtained during the login process 

### Usage
#### Requests
Available roots

| Method    | Route                   | Parameters                                      | Action                                               | 
|-----------|-------------------------|-------------------------------------------------|------------------------------------------------------|
| `POST`    | `login`                 | `username`, `password`                          | Login - get Token. 401 if credentials are wrong      |
| `POST`    | `houses`                | `name`, `description`, `services`, `type`, rooms| Add a house record in the database                   |
| `GET`     | `houses`                |                                                 | Get houses. Empty resultset if no data present       |
| `GET`     | `houses/{id}`           |                                                 | Get house by Id.404 if record does not exist         |
| `PUT`     | `houses/{id}`           | `name`, `description`, `services`, `type`       | Update company by id. 404 if record does not exist   |
| `DELETE`  | `houses/{id}`           |                                                 | Delete by id. 404 if record does not exist           |

