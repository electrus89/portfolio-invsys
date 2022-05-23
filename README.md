# portfolio-invsys
Portfolio project. Inventory system using MariaDB, PHP, and nginx.
## Installation
Not presently set up... the idea is that: 
* The database would be configured using install/create-database.sql
* A symlink to conf/site.conf to /etc/nginx/sites-enabled/(any name)
## Not implemented:
* Using a database user
* Setting up the salt_seed value, from which the seed algorithm will give different values for different IDs.
* Creation of a main admin account, using that salt seed.
* Pretty much everything but parts of the frontend...
