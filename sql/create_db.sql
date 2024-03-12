DROP SCHEMA IF EXISTS waterlogged;
CREATE SCHEMA waterlogged;
USE waterlogged;

-- locations
CREATE TABLE `locations` (
  `id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `temperature` int DEFAULT NULL,
  `climate` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- plants
CREATE TABLE `plants` (
  `id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `traits` varchar(45) DEFAULT NULL,
  `sun` varchar(45) DEFAULT NULL,
  `water_freq_weekly` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `location_id` FOREIGN KEY (`id`) REFERENCES `locations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- users
CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
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
  `id` int NOT NULL,
  `user_plant_id` int DEFAULT NULL,
  `date_watered` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_plant_id_idx` (`user_plant_id`),
  CONSTRAINT `user_plant_id` FOREIGN KEY (`user_plant_id`) REFERENCES `user_plants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;