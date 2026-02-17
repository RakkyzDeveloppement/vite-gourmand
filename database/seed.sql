USE vite_gourmand;

INSERT INTO roles (name) VALUES ('admin'), ('employe'), ('utilisateur');

-- Admin par defaut (mot de passe: password)
INSERT INTO users (role_id, first_name, last_name, email, password_hash, phone, address_line1, postal_code, city)
VALUES (
  (SELECT id FROM roles WHERE name = 'admin'),
  'Jose', 'Admin', 'admin@vite-gourmand.fr',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9xO0xq2S4f32G.f31.rg9C',
  '0600000000', '25 rue des Gourmets', '33000', 'Bordeaux'
);

INSERT INTO themes (name) VALUES ('Noel'), ('Paques'), ('Classique'), ('Evenement');
INSERT INTO regimes (name) VALUES ('Classique'), ('Vegetarien'), ('Vegan');

INSERT INTO menus (title, description, theme_id, regime_id, min_people, base_price, conditions_text, stock)
VALUES
  ('Menu Classique', 'Un menu complet pour vos evenements familiaux.', (SELECT id FROM themes WHERE name='Classique'), (SELECT id FROM regimes WHERE name='Classique'), 10, 250.00, 'Commande 7 jours avant la prestation.', 5),
  ('Menu Vegan', 'Menu vegetal compose de produits locaux.', (SELECT id FROM themes WHERE name='Evenement'), (SELECT id FROM regimes WHERE name='Vegan'), 8, 220.00, 'Commande 5 jours avant la prestation.', 3),
  ('Menu Noel', 'Menu festif pour les celebrations de fin d''annee.', (SELECT id FROM themes WHERE name='Noel'), (SELECT id FROM regimes WHERE name='Classique'), 12, 300.00, 'Commande 14 jours avant la prestation.', 2);

INSERT INTO menu_images (menu_id, image_path, caption)
VALUES
  (1, '/images/filet%20de%20saumon.jpg', 'Filet de saumon'),
  (1, '/images/velout%C3%A9%20de%20saison.jpg', 'Veloute de saison'),
  (1, '/images/tarte%20fruits.jpg', 'Tarte aux fruits'),
  (2, '/images/quinoa%20salade.webp', 'Salade quinoa'),
  (2, '/images/curry%20de%20l%C3%A9gumes.jpeg', 'Curry de legumes'),
  (2, '/images/mousse%20chocolat.jpg', 'Mousse chocolat'),
  (3, '/images/tarte%20fruits.jpg', 'Tarte aux fruits'),
  (3, '/images/filet%20de%20saumon.jpg', 'Filet de saumon'),
  (3, '/images/velout%C3%A9%20de%20saison.jpg', 'Veloute de saison');

INSERT INTO dishes (name, type, description)
VALUES
  ('Veloute de saison', 'entree', 'Veloute de legumes du marche'),
  ('Filet de saumon', 'plat', 'Saumon sauce citron et herbes'),
  ('Tarte aux fruits', 'dessert', 'Fruits de saison'),
  ('Salade quinoa', 'entree', 'Quinoa, legumes croquants'),
  ('Curry de legumes', 'plat', 'Curry doux au lait de coco'),
  ('Mousse chocolat', 'dessert', 'Chocolat noir et amandes');

INSERT INTO menu_dishes (menu_id, dish_id)
VALUES
  (1, 1), (1, 2), (1, 3),
  (2, 4), (2, 5), (2, 6),
  (3, 1), (3, 2), (3, 3);

INSERT INTO allergens (name) VALUES ('Lait'), ('Gluten'), ('Arachide');

INSERT INTO dish_allergens (dish_id, allergen_id)
VALUES
  (2, 1),
  (3, 2),
  (6, 1);

INSERT INTO schedules (day_of_week, open_time, close_time, open_time2, close_time2, is_closed)
VALUES
  (1, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (2, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (3, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (4, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (5, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (6, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0),
  (7, '12:00:00', '14:00:00', '18:00:00', '23:00:00', 0);


