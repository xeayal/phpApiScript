# API script prepared with PHP OOP
Prepare your routes according to the examples under the routes -> folder. The last parameter of the routing function is the middleware name in array format.
It is possible to shorten the middleware name, so it must be defined in the start.Config.php file.

Under the entities -> folder, you can create an entity suitable for each database table and define the table name.
You can start it in the entity controller and quickly use appropriate features such as all(), find($id), findBy($condition), findOneBy($condition), save($data).
