CREATE TABLE images (
    image_id INT PRIMARY KEY AUTO_INCREMENT,
    plant_id INT,
    location_id INT,
    path VARCHAR(255),
    FOREIGN KEY (plant_id) REFERENCES plants(plant_id),
    FOREIGN KEY (location_id) REFERENCES locations(location_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO images (plant_id, location_id, path) VALUES
(NULL, 1, 'images/1.jpg'),
(NULL, 2, 'images/2.jpg'),
(NULL, 3, 'images/3.jpg'),
(NULL, 4, 'images/4.jpg'),
(NULL, 5, 'images/5.jpg'),
(NULL, 6, 'images/6.jpg'),
(NULL, 7, 'images/7.jpg'),
(NULL, 8, 'images/8.jpg'),
(NULL, 9, 'images/9.jpg'),
(NULL, 10, 'images/10.jpg');