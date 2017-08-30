

CREATE DATABASE IF NOT EXISTS `wechat_menu`;
USE `wechat_menu`;


CREATE TABLE IF NOT EXISTS `yy_sys_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  `role_id` int(11) NOT NULL COMMENT '权限ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=utf8 COMMENT='权限和菜单对应表';


INSERT INTO `yy_sys_auth` (`id`, `menu_id`, `role_id`) VALUES
	(264, 31, 1),
	(280, 16, 1),
	(281, 19, 1),
	(282, 29, 1),
	(283, 28, 1),
	(284, 27, 1),
	(285, 26, 1),
	(286, 18, 1),
	(287, 25, 1),
	(288, 24, 1),
	(289, 23, 1),
	(290, 17, 1),
	(291, 22, 1),
	(292, 21, 1),
	(293, 20, 1);


CREATE TABLE IF NOT EXISTS `yy_sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '父菜单的ID',
  `name` varchar(64) NOT NULL COMMENT '菜单名称',
  `mod` varchar(64) NOT NULL COMMENT '菜单的模块名',
  `act` varchar(64) NOT NULL COMMENT '菜单的操作名',
  `display` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '菜单的层级',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '菜单的排序',
  `is_sys` int(11) NOT NULL DEFAULT '0' COMMENT '是否是系统菜单',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COMMENT='系统菜单表';

INSERT INTO `yy_sys_menu` (`id`, `pid`, `name`, `mod`, `act`, `display`, `level`, `sort`, `is_sys`, `createtime`, `updatetime`) VALUES
	(16, 0, '系统设置', '', '', 1, 1, 1, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(17, 16, '菜单管理', 'SysMenu', 'index', 1, 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(18, 16, '管理员管理', 'SysAdmin', 'index', 1, 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(19, 16, '权限组管理', 'SysRole', 'index', 1, 2, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(20, 17, '添加菜单', 'SysMenu', 'addMenu', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(21, 17, '修改菜单', 'SysMenu', 'editMenu', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(22, 17, '删除菜单', 'SysMenu', 'delMenu', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(23, 18, '添加管理员', 'SysAdmin', 'addUser', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(24, 18, '修改管理员', 'SysAdmin', 'editUser', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(25, 18, '删除管理员', 'SysAdmin', 'delUser', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(26, 19, '添加权限组', 'SysRole', 'addRole', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(27, 19, '编辑权限组', 'SysRole', 'editRole', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(28, 19, '删除权限组', 'SysRole', 'delRole', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(29, 19, '绑定菜单', 'SysRole', 'bindMenu', 1, 3, 0, 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(31, 0, '首页', 'Index', 'index', 1, 1, 999, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');


CREATE TABLE IF NOT EXISTS `yy_sys_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT '权限组名称',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='系统权限表';


INSERT INTO `yy_sys_role` (`id`, `name`, `createtime`, `updatetime`) VALUES
	(1, '管理员组', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

	
CREATE TABLE IF NOT EXISTS `yy_sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(128) NOT NULL COMMENT '用户名',
  `pwd` varchar(128) NOT NULL COMMENT '密码',
  `role_id` int(11) NOT NULL COMMENT '权限组ID',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  `updatetime` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='系统管理员表';


INSERT INTO `yy_sys_user` (`id`, `uname`, `pwd`, `role_id`, `createtime`, `updatetime`) VALUES
	(6, 'admin', 'd93a5def7511da3d0f2d171d9c344e91', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');


CREATE TABLE IF NOT EXISTS `yy_wechat_keys` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(50) NOT NULL,
  `secretkey` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信密钥表';


CREATE TABLE IF NOT EXISTS `yy_key_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(32) CHARACTER SET gbk NOT NULL,
  `value` text CHARACTER SET gbk,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信图文消息表(用key绑定指定的菜单)';