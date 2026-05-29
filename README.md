# docker-php-mysql-template
# Template Docker PHP + Apache + MySQL

Template pédagogique pour les formations DWWM / CDA de l'ENI Ecole Informatique.

## Stack

- PHP 8.3
- Apache
- MySQL 8
- phpMyAdmin
- Docker Compose

---

# Prérequis

Installer :

- Docker Desktop

Vérifier :

```bash
docker --version
docker compose version
```
---

# Lancer le projet

```bash
docker compose up -d
```
---
# Accès
Service	URL
- Site PHP	 : http://localhost:8080
- phpMyAdmin : http://localhost:8081

---
# Informations MySQL

| Variable  |  Valeur |
|---|---|
| Port  | 3306  |
| Base  |  cours_docker |
| User  |  etudiant |
| Password  |  etudiant |

---
# Arrêter les conteneurs
```bash
docker compose down
```

---
# Voir les logs
```bash
docker compose logs -f
```
---
# Entrer dans le conteneur PHP
```bash
docker exec -it php_apache bash
```
---
# Structure du projet
- src/            -> fichiers PHP
- mysql/          -> scripts SQL
- php/            -> configuration PHP


---

# docker-compose.yml

```yaml
services:

  php:
    build: .
    container_name: php_apache
    ports:
      - "8080:80"

    volumes:
      - ./src:/var/www/html
      - ./php/php.ini:/usr/local/etc/php/php.ini

    depends_on:
      - mysql

  mysql:
    image: mysql:8.0
    container_name: mysql

    restart: always

    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: cours_docker
      MYSQL_USER: etudiant
      MYSQL_PASSWORD: etudiant

    ports:
      - "3306:3306"

    volumes:
      - mysql_data:/var/lib/mysql
      - ./mysql/init.sql:/docker-entrypoint-initdb.d/init.sql

  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    container_name: phpmyadmin

    restart: always

    depends_on:
      - mysql

    ports:
      - "8081:80"

    environment:
      PMA_HOST: mysql
      MYSQL_ROOT_PASSWORD: root

volumes:
  mysql_data:

```
---
# Dockerfile
```dockerfile
FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html
```

---
# php/php.ini
```ini
display_errors=On
display_startup_errors=On

error_reporting=E_ALL

memory_limit=512M

upload_max_filesize=64M
post_max_size=64M
```
---
# mysql/init.sql
```sql
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100)
);

INSERT INTO utilisateurs (nom)
VALUES
('Alice'),
('Bob'),
('Charlie');
```

---
# src/index.php
```php
<?php

try {

    $pdo = new PDO(
        "mysql:host=mysql;dbname=cours_docker;charset=utf8",
        "etudiant",
        "etudiant"
    );

    $sql = "SELECT * FROM utilisateurs";

    $stmt = $pdo->query($sql);

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Erreur : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Docker PHP MySQL</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">

        <h1>🚀 Docker fonctionne</h1>

        <h2>Liste des utilisateurs</h2>

        <ul>

            <?php foreach ($users as $user): ?>

                <li>
                    <?= htmlspecialchars($user['nom']) ?>
                </li>

            <?php endforeach; ?>

        </ul>

    </div>

</body>
</html>
```
# src/css/style.css
```css
body {

    font-family: Arial, sans-serif;

    background: #f5f5f5;

    margin: 0;
    padding: 40px;
}

.container {

    max-width: 800px;

    margin: auto;

    background: white;

    padding: 40px;

    border-radius: 10px;

    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h1 {

    color: #0d6efd;
}

li {

    margin-bottom: 10px;
}
```
---
# .gitignore
```.gitignore
/vendor/
/node_modules/

.env

.DS_Store

.idea/
.vscode/settings.json
```
---
# .vscode/extensions.json
```json
{
    "recommendations": [
        "bmewburn.vscode-intelephense-client",
        "xdebug.php-debug",
        "ms-azuretools.vscode-docker",
        "mikestead.dotenv",
        "eamodio.gitlens"
    ]
}
```
