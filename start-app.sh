cp env.example env.example.example

# just to be sure that no traces left
docker-compose down -v

# building and running docker-compose file
docker-compose build && docker-compose up -d

# container id by image name
app_container=$(docker ps -aqf "name=genesis-app")
db_container=$(docker ps -aqf "name=genesis-db")

# creating empty database
echo "Creating empty database"
while ! docker exec ${db_container} mysql --user=root --password=root -e "CREATE DATABASE genesis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" >/dev/null 2>&1; do
    sleep 1
done

# installing composer dependencies inside container
docker exec -i ${app_container} bash -c "composer install"

# executing final commands
docker exec -i ${app_container} bash -c "php artisan key:generate --force && php artisan optimize:clear"
echo "Running migrations"
docker exec -i ${app_container} bash -c "php artisan migrate"
