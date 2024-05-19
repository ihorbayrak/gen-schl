## Installation

### 1. Clone repository
```bash
git clone https://github.com/ihorbayrak/gen-schl.git
```

### 2. Copy .env file from .env.example
```bash
cp .env.example .env
```

### 3. Run command

```bash
sh start-app.sh
```

This command will install dependencies and create following containers to run this application:

* [php](https://www.php.net/) (this is for actual application)
* [nginx](https://www.nginx.com/) (this will serve application)
* [mysql](https://www.mysql.com/) (MySQL 8.0 which will store all the data
  of application)
* [redis](https://redis.io/) (this will manage caching and queuing)

### 3. Using application
Add smtp server configuration to your .env (I'm using mailtrap https://mailtrap.io)
```
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD
MAIL_ENCRYPTION=
```
* to get currency rate - [http://localhost:8001/api/rate](http://localhost:8001/api/rate)
* to subscribe - [http://localhost:8001/api/subscribe](http://localhost:8001/api/subscribe)
