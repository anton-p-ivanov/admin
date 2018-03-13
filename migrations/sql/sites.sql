DELETE FROM {{%sites}};
INSERT INTO {{%sites}} (`uuid`, `title`, `url`, `email`, `code`) VALUES
  ('6935908a-e559-4fe1-a666-bfe2bfc0a2d9', 'Control Panel', 'https://domain.com', 'noreply@domain.com', 'ADMIN'),
  (UUID(), 'Timber Industries', 'https://www.timber-industries.com', 'Timber Industries <noreply@timber-industries.com>', 'TIMBER_INDUSTRIES'),
  (UUID(), 'Jupiter Brews', 'https://www.jupiter-brews.com', 'Jupiter Brews <noreply@jupiter-brews.com>', 'JUPITER_BREWS'),
  (UUID(), 'Amazon Foods', 'https://www.amazon-foods.com', 'Amazon Foods <noreply@amazon-foods.com>', 'AMAZON_FOODS');