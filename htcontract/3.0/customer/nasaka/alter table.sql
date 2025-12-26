alter table
add `active` tinyint(1) NOT NULL,
add `cruserid` bigint(20) NOT NULL,
add `crdatetime` datetime DEFAULT NULL,
add `dluserid` bigint(20) DEFAULT NULL,
add `dldatetime` datetime DEFAULT NULL