## Laravel Admin Panel By CODEMAN

A simple Admin Panel with all necessary functionality for making simple websites via Laravel without spending much time.
This package support Laravel 5.8.8 or higher.

## Installation

  ## Step 1

    Install the package through [Composer](http://getcomposer.org/). 

    Run the Composer require command from the Terminal:

      composer require codemanstudio/admin-panel

  ## Step 2
    
    Publish the vendor
  
      php artisan vendor:publish
     
  ## Step 3
    
    Migrate the database
  
      php artisan migrate
  
  ## Step 4
    
    Create images/users folder under public puth

  ## Step 5
    
    Seed the database
  
      php artisan db:seed --class=Codeman\\Admin\\Database\\Seeds\\AdminSeeder
  
  ## Step 6
    
    Follow the URL yoursite.com/admin/login
      
Now you're ready to start using the CODEMAN Admin Panel in your application.


