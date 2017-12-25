DELETE FROM {{%i18n_languages}};
INSERT INTO {{%i18n_languages}} (`title`, `code`, `default`) VALUES
  ('Русский', 'ru-RU', 0),
  ('English (US)', 'en-US', 1);