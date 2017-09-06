-- Database: `esiters_nh`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint(64) unsigned NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `is_active` varchar(5) NOT NULL,
  KEY `user_id` (`user_id`),  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
