DELETE FROM {{%auth_items}} WHERE `type` = 0;
INSERT INTO {{%auth_items}} (`name`, `type`, `created_at`, `updated_at`) VALUES
  ('administrator', 1, NOW(), NOW()),
  ('guest', 1, NOW(), NOW()),
  ('user', 1, NOW(), NOW());

DELETE FROM {{%auth_items_i18n}};
INSERT INTO {{%auth_items_i18n}} (`item_name`, `lang`, `description`) VALUES
  ('administrator', 'ru-RU', 'Администратор'),
  ('guest', 'ru-RU', 'Незарегистрированный пользователь'),
  ('user', 'ru-RU', 'Зарегистрированный пользователь'),
  ('administrator', 'en-US', 'Administrator'),
  ('guest', 'en-US', 'Guest user'),
  ('user', 'en-US', 'Registered user');