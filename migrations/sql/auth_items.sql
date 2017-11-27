DELETE FROM {{%auth_items}} WHERE `type` = 0;
INSERT INTO {{%auth_items}} (`name`, `type`, `description`) VALUES
  ('administrator', 1, 'Администратор'),
  ('guest', 1, 'Незарегистрированный пользователь'),
  ('user', 1, 'Зарегистрированный пользователь');