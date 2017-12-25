SET @uuid1 = UUID();
SET @uuid2 = UUID();
SET @uuid3 = UUID();
SET @uuid4 = UUID();

DELETE FROM {{%accounts_types}};
DELETE FROM {{%accounts_types_i18n}};

INSERT INTO {{%accounts_types}} (`uuid`, `sort`, `default`) VALUES
  (@uuid1, '100', '0'),
  (@uuid2, '100', '0'),
  (@uuid3, '100', '0'),
  (@uuid4, '100', '1');

INSERT INTO {{%accounts_types_i18n}} (`type_uuid`, `lang`, `title`) VALUES
  (@uuid1, 'en-US', 'Payer'),
  (@uuid2, 'en-US', 'Consignee'),
  (@uuid3, 'en-US', 'Customer'),
  (@uuid4, 'en-US', 'Partner'),
  (@uuid1, 'ru-RU', 'Плательщик'),
  (@uuid2, 'ru-RU', 'Грузополучатель'),
  (@uuid3, 'ru-RU', 'Заказчик'),
  (@uuid4, 'ru-RU', 'Партнёр');