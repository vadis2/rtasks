# README
## Code Description
Project uses:
 1. simple MVC structure
 2. active record ORM
 3. Carbon class (for datetime operations)
 4. Jquery and Bootstrap for Test page.
 5. Simple .htaccess for routing.
## MVC structure
|goal|file|
|-------|------------|
|view ot Test page|views/test.php|
|routing|index.php|
|controller of Test page|controllers/Test.php|
|test data|controllers/Test.php|
|tasks processing|controllers/Controller.php|
## Config
### Active records
Change
```
mysql://root:456v123@localhost/rtasks'));
```
to
```
mysql://{login}:{password}localhost/{db_name}'));
```
###Project
Change INTERVAL (days) in config/Config.php.

Closed row 21 or 23 in controllers/Controller.php dependence on do you want to work with now() or static START DateTime.
