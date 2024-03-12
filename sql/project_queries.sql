-- 10 - A general SELECT query with an ORDER BY clause
SELECT * FROM locations ORDER BY name;

-- 11 - A SELECT query that includes a WHERE clause
SELECT * FROM locations WHERE temperature > 70;

-- 12 - A Delete Query
DELETE FROM locations WHERE id = 1;

-- 13 - An Update Query
UPDATE locations SET temperature = 75 WHERE id = 2;

-- 14 - An Insert Query
INSERT INTO locations (id, name, temperature, climate) VALUES (1, 'Living Room', 70, 'Tropical');

-- 15 - A query for inner join
SELECT * FROM locations INNER JOIN plants ON locations.id = plants.id;

-- 16 - A query for outer join (left or right)
SELECT * FROM locations LEFT JOIN plants ON locations.id = plants.id;

-- 17 - A query with an aggregate function(s) (Chapter 6)
SELECT COUNT(*) FROM locations;

-- 18 - A query that includes GROUP BY and HAVING clauses (Chapter 6)
SELECT COUNT(*), climate FROM locations GROUP BY climate HAVING COUNT(*) > 1;

-- 19 - A query with a subquery (Chapter 7)
SELECT * FROM locations WHERE id IN (SELECT id FROM plants WHERE sun = 'Full');

-- 20 - A query that includes a string function (Chapter 9)
SELECT UPPER(name) FROM locations;

-- 21 - A query that includes a numeric function (Chapter 9)
SELECT ROUND(temperature) FROM locations;

-- 22 - A query that includes a date function (Chapter 9)
SELECT DATE_ADD(date_created, INTERVAL 1 DAY) FROM users;