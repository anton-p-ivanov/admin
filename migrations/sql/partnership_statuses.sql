SET @uuid1 = UUID();
SET @uuid2 = UUID();
SET @uuid3 = UUID();
SET @uuid4 = UUID();
SET @uuid5 = UUID();
SET @uuid6 = UUID();
DELETE FROM {{%partnership_statuses}};
DELETE FROM {{%partnership_statuses_i18n}};
INSERT INTO {{%partnership_statuses}} (`uuid`, `code`) VALUES
  (@uuid1, 'distributor'),
  (@uuid2, 'silver_partner'),
  (@uuid3, 'gold_partner'),
  (@uuid4, 'platinum_partner'),
  (@uuid5, 'technology_partner'),
  (@uuid6, 'reseller');
INSERT INTO {{%partnership_statuses_i18n}} (`status_uuid`, `lang`, `title`) VALUES
  (@uuid1, 'en-US', 'Distributor'),
  (@uuid2, 'en-US', 'Silver partner'),
  (@uuid3, 'en-US', 'Gold partner'),
  (@uuid4, 'en-US', 'Platinum partner'),
  (@uuid5, 'en-US', 'Technology partner'),
  (@uuid6, 'en-US', 'Reseller'),
  (@uuid1, 'ru-RU', 'Дистрибьютор'),
  (@uuid2, 'ru-RU', 'Серебряный партнёр'),
  (@uuid3, 'ru-RU', 'Золотой партнёр'),
  (@uuid4, 'ru-RU', 'Платиновый партнёр'),
  (@uuid5, 'ru-RU', 'Технологический партнёр'),
  (@uuid6, 'ru-RU', 'Реселлер');