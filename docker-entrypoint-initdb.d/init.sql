-- Create databases for each frontend environment
CREATE DATABASE IF NOT EXISTS mappit_hvaindestad;
CREATE DATABASE IF NOT EXISTS mappit_lerenmetdestadleiden;
CREATE DATABASE IF NOT EXISTS mappit_sharemystory;
CREATE DATABASE IF NOT EXISTS testing;

-- Create the 'sail' user and grant permissions
CREATE USER IF NOT EXISTS 'sail'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON mappit_hvaindestad.* TO 'sail'@'%';
GRANT ALL PRIVILEGES ON mappit_lerenmetdestadleiden.* TO 'sail'@'%';
GRANT ALL PRIVILEGES ON mappit_sharemystory.* TO 'sail'@'%';
GRANT ALL PRIVILEGES ON testing.* TO 'sail'@'%';

FLUSH PRIVILEGES;
