DELETE FROM {{%addresses_types}};
INSERT INTO {{%addresses_types}} (`uuid`, `title`, `title_ru-RU`, `sort`, `default`, `workflow_uuid`) VALUES
  (UUID(), 'Legal', 'Юридический', '100', 1, NULL),
  (UUID(), 'Office', 'Физический', '100', 0, NULL),
  (UUID(), 'Mailing', 'Почтовый', '100', 0, NULL);