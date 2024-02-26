# Baltpool link checker

## Project installation
1. Clone repository
```
git clone https://github.com/Pofke/baltpool.git
```
2. Copy .env.example file and specify database settings
```
cp .env.example .env
```
3. Install project
```
docker run --rm -v $(pwd):/app composer install --ignore-platform-reqs
```
3. Build Docker container and start it:
```
docker-compose up -d 
```
4. Migrate database
```
docker exec -it baltpool-php php bin/console doctrine:migrations:migrate
```
5. Press enter
6. Seed database
```
docker exec -it baltpool-php php bin/console doctrine:fixtures:load
```
7. Write **_yes_** and press enter

## Commands
### Manual Check links command: 
```
docker exec -it baltpool-php php bin/console app:check-links 
```
## Scheduler
### Symfony Scheduler command:
```
docker exec -it baltpool-php php bin/console messenger:consume scheduler_default
```
#### After starting scheduler it will run two times a day at 10AM and 10PM
## API Commands
### [Postman workspace](https://www.postman.com/docking-module-geologist-64116823/workspace/baltpool)
### Link
#### Create link
```
POST /api/v1/link/new
Body:
{
    "url": "https://www.google.com"
}
```
#### Get links
```
GET /api/v1/link
```
#### Get link
```
GET /api/v1/link/{id}
```
#### Update link
```
PATCH /api/v1/link/{id}
Body:
{
    "url": "https://www.google.com"
}
```
#### Delete link
```
DELETE /api/v1/link/{id}
```
### Keyword
#### Create keyword
```
POST /api/v1/keyword/new
{
    "keyword": "test",
    "link": 1
}
```
#### Get keywords
```
GET /api/v1/keyword
```
#### Get keyword
```
GET /api/v1/keyword/{id}
```
#### Update keyword
```
PATCH /api/v1/keyword/{id}
Body:
{
    "keyword": "test",
    "link": 1
}
```
#### Delete keyword
```
DELETE /api/v1/keyword/{id}
```
### Result
#### Get results
```
GET /api/v1/result
```
#### Get result
```
GET /api/v1/result/{id}
```
## Testing
### Before running first tests
```
docker exec -it baltpool-php php bin/console doctrine:schema:create --env=test
```
### Test command
```
docker exec -it baltpool-php php bin/phpunit
```
## Technologies and Patterns
### Symfony Messenger and Scheduler
One of the goals of the task was to automate the program, that is, to execute it several times a day. Symfony messenger and scheduler are perfect for this. The launched process will run every day at 10 am and 10 pm.
### Strategy Pattern for URL Checks
To implement checks, strategy pattern was used. By applying this pattern, URL checking logic becomes easily expandable. New checking strategies can be added without significant changes to existing code.
