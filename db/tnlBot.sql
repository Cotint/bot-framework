CREATE TABLE `user_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(128),
  `last_name` varchar(128),
  `username` varchar(128),
  `chat_id` int(11) DEFAULT NULL,
  `last_state` varchar(50) DEFAULT 0,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;