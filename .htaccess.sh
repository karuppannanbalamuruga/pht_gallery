RewriteEngine on
RewriteBase /

RewriteRule ^api/users/([0-9]+)$ /api/user/read_one.php?id=$1
RewriteRule ^api/users/user/(.+)$ /api/user/read_one.php?username=$1
RewriteRule ^api/users$ /api/user/read.php
RewriteRule ^api/users/create$ /api/user/create.php
RewriteRule ^api/users/delete/([0-9]+)$ /api/user/delete.php?id=$1
RewriteRule ^api/users/update/([0-9]+)$ /api/user/update.php?id=$1

RewriteRule ^api/albums/([0-9]+)$ /api/album/read_one.php?id=$1
RewriteRule ^api/albums/user/(.+)$ /api/album/read.php?username=$1
RewriteRule ^api/albums$ /api/album/read.php
RewriteRule ^api/albums/create$ /api/album/create.php
RewriteRule ^api/albums/delete/([0-9]+)$ /api/album/delete.php?id=$1
RewriteRule ^api/albums/update/([0-9]+)$ /api/album/update.php?id=$1

RewriteRule ^api/photos/([0-9]+)$ /api/photo/read.php?id=$1
RewriteRule ^api/photos/create$ /api/photo/create.php
RewriteRule ^api/photos/delete/([0-9]+)$ /api/photo/delete.php?id=$1

RewriteRule ^api/login$ /api/login.php