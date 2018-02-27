DELETE FROM {{%sales_discounts}};
INSERT INTO {{%sales_discounts}} (`uuid`, `code`, `title`, `value`) VALUES
  (UUID(), 'hardware', 'Hardware discount', '0.1000'),
  (UUID(), 'software', 'Software discount', '0.1500'),
  (UUID(), 'license', 'License discount', '0.2000');