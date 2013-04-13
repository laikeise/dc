INSERT INTO user_profile VALUES (NULL , 'terence', 'f56b2700382c1c9513a881d2c2af9f1b', 'Terence', CURRENT_TIMESTAMP , '1', '1', 'ALL') ;
INSERT INTO usite_cont VALUES (NULL , '24', '4', 'GRP02');

DELETE FROM usite_cont WHERE NOT EXISTS (SELECT 1 FROM user_profile B WHERE usite_cont.userp_uid=B.userp_uid);

SELECT A.userp_login, B.grp_id
FROM user_profile A, usite_cont B
WHERE A.userp_uid=B.userp_uid AND B.site_sid=4 ;

INSERT INTO `past_players` (name, country, year, player_type)
SELECT fullname, country_name, '2011', player_type
FROM `cust_tb` ;

TRUNCATE TABLE cust_act_tb ;
TRUNCATE TABLE cust_add_tb ;
TRUNCATE TABLE cust_handi_tb ;
TRUNCATE TABLE cust_holder ;
TRUNCATE TABLE cust_tb ;
TRUNCATE TABLE delete_log ;
TRUNCATE TABLE del_cust_act_tb ;
TRUNCATE TABLE del_cust_add_tb ;
TRUNCATE TABLE del_cust_handi_tb ;
TRUNCATE TABLE del_cust_tb ;
TRUNCATE TABLE sql_log ;

ALTER TABLE `cust_tb` ADD `arrival_port` VARCHAR( 10 ) NOT NULL AFTER `arrival_flight` ;
ALTER TABLE `cust_tb` ADD `depart_port` VARCHAR( 10 ) NOT NULL AFTER `depart_flight` ;
ALTER TABLE `cust_tb` ADD `transport2` VARCHAR( 10 ) NOT NULL AFTER `transport` ;
