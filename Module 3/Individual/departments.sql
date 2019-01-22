+-------------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table       | Create Table                                                                                                                                                                                                                                                                                                 |
+-------------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| departments | CREATE TABLE `departments` (
  `school_code` enum('L','B','A','F','E','T','I','W','S','U','M') NOT NULL,
  `dept_id` int(255) unsigned NOT NULL,
  `abbreviation` varchar(9) DEFAULT NULL,
  `dept_name` varchar(200) NOT NULL,
  PRIMARY KEY (`school_code`,`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 |
+-------------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+