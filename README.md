photo-gallery
This is a RESTful api created in PHP and MYSQL for the Photo Gallery app.

I have developed this api using XAMPP Apache server and this files were inside htdocs folder in XAMPP. The SQL file for table structure and data in also added.

Api endpoints
Login
POST /api/login Login user.

User
GET /api/users Read all users

GET /api/users/{user_id} Read one user having id=user_id

GET /api/users/{username} Read one user having username=username

POST /api/users/create Create user (Register User)

POST /api/users/delete/{user_id} Delete user having id=user_id

POST /api/users/update/{user_id} Update user having id=user_id

Album
GET /api/albums Read all albums

GET /api/albums/{album_id} Read one album having id=album_id

GET /api/albums/user/{username} Read one album having username=username

POST /api/albums/create Create Album (User id is required)

POST /api/albums/delete/{album_id} Delete Album having id=album_id

POST /api/albums/update/{album_id} Update Album having id=album_id

Photo
GET /api/photos/{photo_id} Read one photo having id=photo_id

POST /api/photos/create Create Photo (Album id is required)

POST /api/photos/delete/{photo_id} Delete Photo having id=photo_id

Authentication
User Authentication is Json Web Token based. For API endpoints requiring authorization the api/login call must be made first with proper credentials. A Token is then generated. All further requests must contain authorization header with the token.
