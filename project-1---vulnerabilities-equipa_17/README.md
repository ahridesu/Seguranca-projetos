-----------------------------------
DESCRIPTION: 

For this project a Book Database was agreed to be created. A simple website that allows the listing of searched books by name, author or publisher.

-----------------------------------
AUTHORS: 

Ariana Gemelgo (89194), Gustavo Inácio (85016), Hugo Moinheiro (84931),  Mariana Pinto (84792)

-----------------------------------
HOST: 0.0.0.0
PORT: 9001

-----------------------------------
SETUP and INTRUCTIONS:

docker-compose build

docker-compose up

docker exec -i [mysql_container_name] mysql -uroot -proot bookdb < [path/to/sql/file]

NOTE: if there's an error when creating the database, verify if the same is inside the container. For that run:
docker ps -> to see the id container of the mysql

docker exec -it [idcontainer] bash

mysql -uroot -proot

create database bookdb;

exit

exit

docker exec -i [mysql_container_name] mysql -uroot -proot bookdb < [path/to/sql/file]

NOTE: in case of having trouble running both containers, delete the volumes

docker volume ls 

docker volume rm [volume_name]

-----------------------------------
APP:

- LOG IN:

Username: admin

Password: admin

APP_SEC:

- LOG IN:

Username: admin

Password: admin

-----------------------------------
VULNERABILITIES IMPLEMENTED: 

CWE-89
CWE-79
CWE-521
CWE-620

