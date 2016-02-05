-- vloženie užívateľov, prví dvaja majú heslo je 'medved', ja som Pavel a som administrátor
INSERT INTO users (name, email, password, is_admin) VALUES
  ('Pavel Valuška', 'bengov@gmail.com', '0589ab337a9f0d7315924538f05c146197467d3add3235c32baf61b8663bd08d4f5fc17f1617bdfe13509a429b9cf8e64c41f91c1af7615fc321b96e4c5585ab', TRUE),
  ('Test Testovací', 'test@test.com', '0589ab337a9f0d7315924538f05c146197467d3add3235c32baf61b8663bd08d4f5fc17f1617bdfe13509a429b9cf8e64c41f91c1af7615fc321b96e4c5585ab', FALSE);


-- vloženie testovacích článkov, 3. a 5. užívateľ nemajú články
INSERT INTO posts (user_id, title, text) VALUES
  (1, 'Článok 1', 'Učenie hrou'),
  (1, 'Článok 2', 'Udalosť týždňa'),
  (1, 'Článok 3', 'Udalosť č. 2'),
  (2, 'Článok 4', 'Test webu'),
  (2, 'PHP+APACHE+POSTGRESQL', 'Učenie je základ bytia');


-- vloženie testovacích tagov
INSERT INTO tags (tag) VALUES
  ('Triky'),
  ('Tipy'),
  ('Šach'),
  ('Zdravie'),
  ('Autá');