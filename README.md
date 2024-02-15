# Installation

- To install the php backend copy [example.settings.php](Api%2Fexample.settings.php) to settings.php and fill in the database credentials.

- To install the frontend, run `npm install` and `npm run build` in the `frontend` directory.
For convenience, I've kept the output of the build in the css folder ([output.css](Client%2Fsrc%2Fcss%2Foutput.css))

- Finally, to run the sites create two vhosts one to point to the Client/src directory and one to point to the Api/public directory.
- The site was developed & tested on a local Apache server with PHP 8.1

## Standards
- PSR2 as the coding standards using codesniffer.
- PHPstan to Level 6.

## Further additions
I would likely use a library such as Doctrine for any database queries as preventing the many different 
attack vectors such as SQL Injection.

I would also use a proper rendering engine such as Twig, or Blade to prevent XSS attacks.

I would also use a lightweight framework such as Slim, or Lumen for the backend for the ease of routing / autoloading.