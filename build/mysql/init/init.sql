CREATE USER 'main_user'@'localhost' IDENTIFIED BY 'mainuserpass';
GRANT ALL ON infotech.* TO 'main_user'@'localhost';
FLUSH PRIVILEGES;