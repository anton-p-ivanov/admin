DELETE FROM {{%workflow_statuses}};
INSERT INTO {{%workflow_statuses}} (`code`, `sort`) VALUES
  ('D', 100),
  ('R', 200),
  ('P', 300);
DELETE FROM {{%workflow_statuses_i18n}};
INSERT INTO {{%workflow_statuses_i18n}} (`code`, `lang`, `title`) VALUES
  ('D', 'en-US', 'Draft'),
  ('R', 'en-US', 'Ready'),
  ('P', 'en-US', 'Published'),
  ('D', 'ru-RU', 'Черновик'),
  ('R', 'ru-RU', 'Ожидает публикации'),
  ('P', 'ru-RU', 'Опубликован');