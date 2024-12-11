# Blog Management System

A dynamic blog management system built using **Core PHP**, **MySQL**, and **PHP Mailer**. This system allows users to manage blogs, view posts, comment on them, and provides an admin panel to manage users, comments, and posts.

---

 **Getting Started**

Follow the steps below to set up and run the project locally:

 **Prerequisites**

1. **Server**:
   - Install **Apache** and **PHP** (using XAMPP, WAMP, or similar server stack).
2. **Database**:
   - MySQL database server should be running.
3. **Composer**:
   - Make sure you have Composer installed to manage dependencies.
   - [Install Composer here](https://getcomposer.org/).

---

**Setup Instructions**

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Lokesh516/blog-management.git

  
2. Navigate to the project directory:
   cd blog-management

3. Install PHP Mailer with Composer:
   composer install

4. Set up the database:
   Create a database in MySQL with the structure defined below.
   Update your database credentials in db.php.

5. Import database structure:
   
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `publish_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
);

 CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `is_approved` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comments_fk` (`post_id`),
  CONSTRAINT `comments_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
);


6. Start your local server environment (XAMPP, WAMP, MAMP, or others).

Register & Login
To get started, you can visit the following pages:

Registration Page: http://localhost/blog-management/views/Registration.php
Login Page: http://localhost/blog-management/views/login.php



# sample data for users table:
-- Note: The passwords have been hashed using bcrypt for security. The original plaintext passwords are:
-- Admin's password: Admin@123
-- User's password: User@123

 INSERT INTO users (username, email, password, is_admin, created_at, reset_token, token_created_at) VALUES ("Admin", "admin@gmail.com", "$2y$10$xQXfcTHRfKvoyVUoACgI4O5Lb8kpOm3Z.zllAAkteuzRb1QPhg6Qm", 1, "2024-12-10 12:24:46", "", "");

 INSERT INTO users (username, email, password, is_admin, created_at, reset_token, token_created_at) VALUES ("User", "user@gmail.com", "$2y$10$4rRyd/g.aC7o0ZMt1W6CHeWalQoPWHo1UtXme2.KQwJT6CeQNa4vG", 0, "2024-12-10 12:33:43", "", "");


# Sample data for posts table:

INSERT INTO posts (title, content, author_id, image, publish_date, created_at) VALUES ("Iphone 16 pro", "iPhone 16 Pro 128GB 5G smartphone with Camera Control 4K 120fps Dolby Vision improved battery life and AirPods compatibility.", 1, "6757e63028583-iphone 16 pro.png", "2024-12-10 06:56:48", "2024-12-10 12:26:48"); 

INSERT INTO posts (title, content, author_id, image, publish_date, created_at) VALUES ("XIAOMI 14 Ultra 16GB 512GB", "Xiaomi 14 features a 50MP Leica camera system with Light Fusion 900 sensor TelephotoMacro and Ultra wide lenses supporting 8K video and Dolby Vision Its 6.36 inch 1.5K 120Hz LTPO AMOLED display offers 3000nits brightness 68 billion colors and Gorilla Glass Victus protection Powered by HyperOS based on Android 14.", 1, "6757e68ce7ab3-XIAOMI 14 Ultra.png", "2024-12-10 06:58:20", "2024-12-10 12:28:20");

INSERT INTO posts (title, content, author_id, image, publish_date, created_at) VALUES ("Google Pixel 9 5G", "Compatible with Google Pixel 9 5G this ultra clear soft silicone case offers 360 shockproof protection Easy to install it flaunts your phones original look.", 1, "6757e6d56558f-Google Pixel 9 5G.png", "2024-12-10 06:59:33", "2024-12-10 12:29:33");

INSERT INTO posts (title, content, author_id, image, publish_date, created_at) VALUES ("Poco X6 Pro 5G 512 GB", 1.5K 120Hz AMOLED display with Dolby Vision 68B colors 1800 nits brightness and Gorilla Glass Victus Powered by Snapdragon 7s Gen 2 up to 20GB RAM 64MP OIS triple camera 5100mAh battery with 67W fast charging and Android 13.", 1, "6757e71dc3810-Poco X6 Pro 5G.png", "2024-12-10 07:00:45", "2024-12-10 12:30:45");

INSERT INTO posts (title, content, author_id, image, publish_date, created_at) VALUES ("Nothing Phone 2a 5G", "Nothing Phone 2a 5G 6.7 AMOLED display 50MP OIS 50MP rear cameras 32MP front camera 8GB RAM 128GB storage Mediatek Dimensity 7200 Pro Glyph Interface 45W charging 100 percent  in 59 mins.", 1, "6757e76cb7582-Nothing Phone 2a 5G.png", "2024-12-10 07:02:04", "2024-12-10 12:32:04");


# sample data for comments table:

INSERT INTO comments (post_id, user_name, comment, is_approved, created_at) VALUES (5, "User", "This is a great phone with excellent features", 0, "2024-12-10 12:42:57");

INSERT INTO comments (post_id, user_name, comment, is_approved, created_at) VALUES (4, "User", "A sleek design with powerful performance", 0, "2024-12-10 12:43:52"); 

INSERT INTO comments (post_id, user_name, comment, is_approved, created_at) VALUES (3, "User", "Perfect for multitasking and gaming on the go", 0, "2024-12-10 12:44:23");

INSERT INTO comments (post_id, user_name, comment, is_approved, created_at) VALUES (2, "User", "This phone offers great value for money", 0, "2024-12-10 12:44:54");

INSERT INTO comments (post_id, user_name, comment, is_approved, created_at) VALUES (1, "User", "A stylish device with impressive battery life", 0, "2024-12-10 12:45:19");