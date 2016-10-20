# cakephp-aclcomponent

CakePHP3 does not come with ACL anymore. 
For those of us fond with this, some might remember the complications with the extra 3 database tables, 
difficulties testing the system, etc. 

Handle requests to your Cake3 controller methods based on a permissions array.

## About 

Pass in the array of permissions to the component after the user has been detected by the AuthComponent.

* This component restricts access to controller methods. 
* Keep the permissions in an easy to see & test PHP array. 
* Load the component in the AppController, and pass it the permissions the user requires. 
* Wildcard the whole app or controller for specific user groups!

## Using

```PHP
 // keep a $permissions array, somewhere, use a db or flat-file:
 $permissions = [
     'admin' => '*', // admins can access all controllers & all methods
     'customer' => [
         'Products' => '*', // customers can access all the methods of the ProductsController
         'Users' => ['my_account', 'contact'], // customers can only access these two methods in UsersController
     ],
     'banned' => [], // banned users cannot access anything
 ];
 
 // In your AppController:::initialize()
 $user = $this->Auth->user();
 if ($user) {
 // user is logged in, so we can load the Acl
 // no need to load Acl if we are not logged in, right?
 
 // users.role in your db corresponds to the $permissions key, admin, customer or banned in this example
 $this->loadComponent('Acl', $permissions[$user->role]); 

```

For small apps you might keep the role as a string in your db, but larger applications will require a users.group_id and a groups table. The same strategy applies, just use the groups.name value. 


## Todo, 

* package as a plugin, publish @packagist
* tests

## Contribute
please do! 
fork and pr






