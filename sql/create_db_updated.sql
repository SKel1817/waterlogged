DROP SCHEMA IF EXISTS waterlogged;
CREATE SCHEMA waterlogged;
USE waterlogged;

-- locations
CREATE TABLE `locations` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `temperature` int DEFAULT NULL,
  `climate` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- plants

CREATE TABLE `plants` (
  `id` int NOT NULL,
  `name` varchar(225) DEFAULT NULL,
  `traits` mediumtext DEFAULT NULL,
  `sun` varchar(255) DEFAULT NULL,
  `water_freq_weekly` varchar(255) DEFAULT NULL,
  `location_id` int, -- Adding a separate column for the foreign key
  PRIMARY KEY (`id`),
  CONSTRAINT `location_id_fk` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) -- Adjusting the foreign key constraint
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- users
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- user_plants
CREATE TABLE `user_plants` (
  `id` int NOT NULL,
  `last_watered` datetime DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `plant_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`),
  KEY `plant_id_idx` (`plant_id`),
  CONSTRAINT `plant_id` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- logs
CREATE TABLE `logs` (
  `id` int not null auto_increment,
  `user_plant_id` int DEFAULT NULL,
  `date_watered` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_plant_id_idx` (`user_plant_id`),
  CONSTRAINT `user_plant_id` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;