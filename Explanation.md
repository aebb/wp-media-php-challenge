 ### PHP Technical Assessment 

 #### Tech Stack:
- Docker
- PHP 7.4
- Symfony 5.2
- MySQL
- Redis

#### Install and Run:

##### Create the database
```php bin/console doctrine:database:create```


##### Run the migrations
```php bin/console doctrine:migration:migrate```


##### Create a local user with admin privileges (user: admin, password: admin)
```php bin/console doctrine:fixtures:load```


##### Run the consumer to process admin requests
```php bin/console messenger:consume async -vv```


##### Edit crontab and schedule new crawl tasks
```crontab -e```

```* * * * * /usr/local/bin/php /app/bin/console ts:run >> /tmp/ts.log 2>&1```

#### Endpoints:

##### /
Displays a random list of links to simulate dynamic content

##### /sitemap
Displays the sitemap

##### /login
Login

##### /logout
Logout

##### /admin
Displays the results from the last crawl, has a button to trigger a new crawl

#### Tests:

##### To run the tests

```vendor/bin/phpunit -c phpunit.xml ./tests```

#### Other:

##### Run code beautifier 

```vendor/bin/phpcbf```

##### Run code sniffer 

```vendor/bin/phpcs```

##### Run mess detector

```vendor/bin/phpmd ./src text ./phpmd.xml```

composer.json also contains shortcuts for these commands

#### Solution:

- Access to the /admin page requires a user with ROLE_ADMIN privilege (see config/packages/security.yaml)

- App uses rewieer/taskschedulerbundle to programmatically schedule new tasks, similar to crontab (see src/Command/AdminTask.php)

- Since crawling is an intensive task (http requests, dom parsing, file handling) when the admin requests a new crawl the admin actually sends a message to a redis stream. A consumer picks up the message and runs a new crawl in parallel. The results are available shortly after on the admin page. (see src/Services/AdminService.php and src/Message/CrawlMessageHandler.php)

- PHPUnit for tests (code coverage ~100%):
1) Integration: In-memory SQLLite for persistence, In-memory transport for messaging system, mikey179/vfsstream for virtual file system.

2) Unit: PHPUnit mock objects for most nearly all dependencies:







