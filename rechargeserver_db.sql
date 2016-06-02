SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'members', 'General User'),
(3, 'type1', 'I'),
(4, 'type2', 'II'),
(5, 'type3', 'III'),
(6, 'type4', 'IV'),
(7, 'superadmin', 'Super Administrator');

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `account_status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6;   
INSERT INTO `account_status` (`id`, `description`) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'Suspended'),
(4, 'Deactivated'),
(5, 'Blocked');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `max_user_no` int(11) unsigned DEFAULT 0,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `note`text DEFAULT '',
  `account_status_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_laccount_status1` FOREIGN KEY (`account_status_id`) REFERENCES `account_status` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `max_user_no`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `account_status_id`, `first_name`, `last_name`, `company`, `mobile`) VALUES
(2, '\0\0', 'superadmin', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'super@admin.com',  100000, '', NULL, NULL, NULL, 1268889823, 1373438882, 1, 'Super', 'admin', 'SUPER_ADMIN', '0'),
(1, '\0\0', 'administrator', '59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4', '9462e8eee0', 'admin@admin.com',  100000, '', NULL, NULL, NULL, 1268889823, 1373438882, 1, 'Admin', 'istrator', 'ADMIN', '0');


CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 7);

CREATE TABLE IF NOT EXISTS `relations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_user_id` int(11) unsigned NOT NULL,
  `child_user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uc_relations` (`parent_user_id`,`child_user_id`),
  KEY `fk_relations_users1_idx` (`parent_user_id`),
  KEY `fk_relations_users2_idx` (`child_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
ALTER TABLE `relations`
  ADD CONSTRAINT `fk_relations_users1` FOREIGN KEY (`parent_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_relations_users2` FOREIGN KEY (`child_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;   
INSERT INTO `services` (`id`, `title`) VALUES
(1, 'bkash (CashIn)'),
(2, 'DBBL (CashIn)'),
(3, 'M-Cash (CashIn)'),
(4, 'U-Cash (CashIn)'),
(101, 'Topup GP'),
(102, 'Topup ROBI'),
(103, 'Topup BanglaLink'),
(104, 'Topup Airtel'),
(105, 'Topup TeleTalk'),
(1001, 'Send SMS');
CREATE TABLE IF NOT EXISTS `users_services` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `service_id` int(11) unsigned NOT NULL,
  `rate` double DEFAULT 1.0,
  `commission` double DEFAULT 0.0,
  `charge` double DEFAULT 0.0,
  `status` boolean DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_users_services_users1_idx` (`user_id`),
  KEY `fk_users_services_services1_idx` (`service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
ALTER TABLE `users_services`
  ADD CONSTRAINT `fk_users_services_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_users_services_services1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
INSERT INTO `users_services` (`id`, `user_id`, `service_id`, `rate`, `commission`, `charge`, `status`) VALUES
(1, 1, 1, 1000, 0, 0,  1),
(2, 1, 2, 1000, 0, 0,  1),
(3, 1, 3, 1000, 0, 0,  1),
(4, 1, 4, 1000, 0, 0,  1),
(5, 1, 101, 1000, 0, 0,  1),
(6, 1, 102, 1000, 0, 0,  1),
(7, 1, 103, 1000, 0, 0,  1),
(8, 1, 104, 1000, 0, 0,  1),
(9, 1, 105, 1000, 0, 0,  1),
(10, 1, 1001, 1.0, 0.0, 0.5,  1);

  
CREATE TABLE IF NOT EXISTS `user_transaction_statuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;   
INSERT INTO `user_transaction_statuses` (`id`, `title`) VALUES
(1, 'Pending'),
(2, 'Success'),
(3, 'Failed'),
(4, 'Cancelled');



 CREATE TABLE IF NOT EXISTS `operators` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;   
INSERT INTO `operators` (`id`, `title`) VALUES
(101, 'GP'),
(102, 'Robi'),
(103, 'BanglaLink'),
(104, 'Airtel'),
(105, 'TeleTalk');

  CREATE TABLE IF NOT EXISTS `operator_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;   
INSERT INTO `operator_types` (`id`, `title`) VALUES
(1, 'Prepaid'),
(2, 'Postpaid');

CREATE TABLE IF NOT EXISTS `sms_details` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `transaction_id` varchar(200),
  `sms` text,
  `length` int(11),
  `unit_price` double,
  `created_on` int(11) unsigned DEFAULT NULL,
  `modified_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sms_details_users1_idx` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
ALTER TABLE `sms_details`
  ADD CONSTRAINT `fk_sms_details_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
CREATE TABLE IF NOT EXISTS `user_sms_transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(200),
  `sender_cell_no` varchar(100),
  `cell_no` varchar(20),
  `status_id` int(11) unsigned NOT NULL,
  `created_on` int(11) unsigned DEFAULT NULL,
  `modified_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_sms_transactions_user_transaction_statuses1_idx` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; 
ALTER TABLE `user_sms_transactions`
  ADD CONSTRAINT `fk_user_sms_transactions_user_transaction_statuses1` FOREIGN KEY (`status_id`) REFERENCES `user_transaction_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `user_transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `transaction_id` varchar(200),
  `service_id` int(11) unsigned NOT NULL,
  `operator_id` int(11) unsigned   DEFAULT NULL,
  `operator_type_id` int(11) unsigned  DEFAULT NULL,
  `sender_cell_no` varchar(100),
  `cell_no` varchar(20),
  `description` varchar(200),
  `amount` double,
  `status_id` int(11) unsigned NOT NULL,
  `created_on` int(11) unsigned DEFAULT NULL,
  `modified_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_transactions_users1_idx` (`user_id`),
  KEY `fk_user_transactions_operator_idx` (`operator_id`),
  KEY `fk_user_transactions_operator_type_idx` (`operator_type_id`),
  KEY `fk_user_transactions_user_transaction_statuses1_idx` (`status_id`),
  KEY `fk_user_transactions_services1_idx` (`service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; 
ALTER TABLE `user_transactions`
  ADD CONSTRAINT `fk_user_transactions_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_transactions_operator_idx` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_transactions_operator_type_idx` FOREIGN KEY (`operator_type_id`) REFERENCES `operator_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_transactions_uts1` FOREIGN KEY (`status_id`) REFERENCES `user_transaction_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_transactions_services1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `user_payment_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;   
INSERT INTO `user_payment_types` (`id`, `title`) VALUES
(1, 'Send Credit'),
(2, 'Receive Credit'),
(3, 'Return Credit'),
(4, 'Return Receive Credit'),
(5, 'Use Service'),
(6, 'Load Balance');
CREATE TABLE IF NOT EXISTS `user_payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned NOT NULL,
  `transaction_id` varchar(200),
  `status_id` int(11) unsigned NOT NULL,
  `description` varchar(200),
  `balance_in` double,
  `balance_out` double,
  `type_id` int(11) unsigned NOT NULL,
  `created_on` int(11) unsigned DEFAULT NULL,
  `modified_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_payments_users1_idx` (`user_id`),
  KEY `fk_user_payments_users2_idx` (`reference_id`),
  KEY `fk_user_payments_user_payment_types1_idx` (`type_id`),
  KEY `fk_user_payments_user_transaction_statuses1_idx` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; 
ALTER TABLE `user_payments`
  ADD CONSTRAINT `fk_user_payments_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_payments_users2_idx` FOREIGN KEY (`reference_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_payments_upt1` FOREIGN KEY (`type_id`) REFERENCES `user_payment_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_payments_uts1` FOREIGN KEY (`status_id`) REFERENCES `user_transaction_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
  CREATE TABLE IF NOT EXISTS `user_profits` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `reference_id` int(11) unsigned NOT NULL,
  `transaction_id` varchar(200),
  `service_id` int(11) unsigned NOT NULL,
  `status_id` int(11) unsigned NOT NULL,
  `rate` double,
  `amount` double,
  `created_on` int(11) unsigned DEFAULT NULL,
  `modified_on` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_user_profites_users1_idx` (`user_id`),
  KEY `fk_user_profites_users2_idx` (`reference_id`),
  KEY `fk_user_profites_service_idx` (`service_id`),
  KEY `fk_user_profits_user_transaction_statuses1_idx` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1; 
ALTER TABLE `user_profits`
  ADD CONSTRAINT `fk_user_profits_uts1` FOREIGN KEY (`status_id`) REFERENCES `user_transaction_statuses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

  
  