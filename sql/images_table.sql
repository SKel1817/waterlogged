CREATE TABLE images (
    plant_id INT NULL,
    location_id INT NULL,
    path VARCHAR(255) NOT NULL,
    CONSTRAINT fk_plant FOREIGN KEY (plant_id) REFERENCES waterlogged.plants(id),
    CONSTRAINT fk_location FOREIGN KEY (location_id) REFERENCES waterlogged.locations(id),
    CONSTRAINT chk_images CHECK ((plant_id IS NOT NULL AND location_id IS NULL) OR (plant_id IS NULL AND location_id IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO images (plant_id, location_id, path) VALUES
(NULL, 1, 'imgs/locations/tropical_rainforest.jpg'),
(NULL, 2, 'imgs/locations/temperate_forest.jpg'),
(NULL, 3, 'imgs/locations/boreal_forest.jpg'),
(NULL, 4, 'imgs/locations/tundra.jpg'),
(NULL, 5, 'imgs/locations/shrubland.jpg'),
(NULL, 6, 'imgs/locations/desert.jpg'),
(NULL, 7, 'imgs/locations/grassland.jpg'),
(NULL, 8, 'imgs/locations/lentic.jpg'),
(NULL, 9, 'imgs/locations/littoral.jpg'),
(NULL, 10, 'imgs/locations/lotic.jpg'),
(NULL, 11, 'imgs/locations/coral_reef.jpg'),
(NULL, 12, 'imgs/locations/oceanic.jpg');

-- get plant info from plants_data.sql
INSERT INTO images (plant_id, location_id, path) VALUES
(1, NULL, 'imgs/plants/philodendron_bipennifolium.jpg'),
(2, NULL, 'imgs/plants/phalaenopis_spp.jpg'),
(3, NULL, 'imgs/plants/epipremnum_aureum.jpg'),
(4, NULL, 'imgs/plants/peperomia_obtusifolia.jpg');