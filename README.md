# FightClubSA

## 1. Project Overview

FightClubSA is a fitness club management application developed to provide users with access to gym-related information and services. The project consists of a PHP-based website, a MySQL database, and an Android application developed using Android Studio.

The system is intended to support user registration and login, membership management, fitness class information, bookings, payments, notifications, and user settings.

## 2. Project Components

### Website
The website is developed using:
- PHP for server-side functionality.
- HTML and CSS for page structure and styling.
- JavaScript for interactive features.
- MySQL for database storage.
- XAMPP for the local development environment.

### Android Application
The Android application is developed using:
- Android Studio.
- Kotlin.
- Jetpack Compose.
- Android WebView to display the FightClubSA website within the app.

### Database
The MySQL database is named `fightclubsa`. It stores the information required by the website and its API.

## 3. Technologies Used

| Technology | Purpose |
|---|---|
| PHP | Server-side programming |
| MySQL | Database management |
| XAMPP | Local web server and database environment |
| HTML and CSS | Website interface |
| JavaScript | Website interactivity |
| Kotlin | Android application development |
| Android Studio | Android development environment |
| Git and GitHub | Version control and source-code hosting |

## 4. Project Requirements

The following software is required to run and develop the project:

- XAMPP with Apache and MySQL.
- PHP-compatible code editor, such as Visual Studio Code.
- Android Studio.
- A web browser.
- Git for version control.
- An Android phone or emulator for testing.

## 5. Website Installation

1. Install and open XAMPP.
2. Start Apache and MySQL.
3. Place the website folder inside the XAMPP `htdocs` directory.
4. Open phpMyAdmin through `http://localhost/phpmyadmin/`.
5. Create or import the `fightclubsa` database using the project's database SQL script.
6. Check that the database connection settings match the local MySQL configuration.
7. Open the website in a browser.

Example local website address:

`http://localhost/FightClubSA_XISD2/`

## 6. Android Application Setup

1. Open Android Studio.
2. Open the FightClubSA Android project.
3. Allow Gradle to finish syncing.
4. Check the WebView URL in the Android source code.
5. Run the application on an Android emulator or physical device.

For an Android emulator, a local XAMPP website can usually be accessed through:

`http://10.0.2.2/FightClubSA_XISD2/`

For a physical Android phone, the website must be reachable from the phone. A cloud-hosted development computer may require a publicly accessible test URL or online hosting.

## 7. Database and API

The website uses PHP API endpoints to communicate with the database. These endpoints support operations such as user authentication, retrieving fitness classes, membership information, and managing user settings.

Database credentials and other sensitive configuration values should not be committed to the public GitHub repository.

## 8. Testing

Testing should cover the following areas:

- User registration and login.
- Database connectivity.
- Retrieval of fitness class information.
- Membership information and settings.
- Website navigation and page loading.
- Android application launch.
- WebView connectivity on an emulator.
- WebView connectivity on a physical Android phone.
- Handling of invalid input and connection errors.

## 9. Version Control

GitHub is used to store and manage the project's source code. The repository allows the developer to track changes, maintain project files, and share the code for review or assessment.

## 10. Current Development Considerations

The project is being developed using a remote/cloud computer with XAMPP. Consequently, the local development address is not automatically accessible from a physical Android phone.

Before physical-device testing can be completed, the PHP website and its API must be made reachable from the phone. The Android WebView URL must then be updated to use the reachable website address.

## 11. Security Considerations

- Passwords should be stored using secure password hashing.
- Database queries should use prepared statements.
- Authentication tokens should be validated by protected API endpoints.
- Database credentials and private keys should remain outside public source control.
- HTTPS should be used when deploying the application to a public server.
- The application should validate user input and handle errors appropriately.

## 12. Conclusion

FightClubSA combines a PHP website, MySQL database, and Android application into a fitness club management project. The website and database are developed locally through XAMPP, while Android Studio is used to build the mobile application. Further testing of the network connection and deployment configuration is required to ensure that the Android application can access the website from a physical phone.
