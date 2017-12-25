DELETE FROM {{%accounts}};
INSERT INTO {{%accounts}} (`uuid`, `title`, `description`, `details`) VALUES
  (UUID(), 'Big Haul Integrity', '', ''),
  (UUID(), 'Perspective Company', '', ''),
  (UUID(), 'Wool-and-Link', '', ''),
  (UUID(), 'Strategic Insurance', '', ''),
  (UUID(), 'FastLane Builders', '', ''),
  (UUID(), 'Doopler Ventures', '', ''),
  (UUID(), 'Blue Oak Construction', '', '');