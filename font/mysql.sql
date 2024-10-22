-- 创建数据库
CREATE DATABASE IF NOT EXISTS php_test;

-- 选择数据库
USE php_test;

-- 创建 admin 表
CREATE TABLE IF NOT EXISTS admin (
    user VARCHAR(50) NOT NULL PRIMARY KEY,
    password VARCHAR(50) NOT NULL
    );

-- 插入用户
INSERT INTO admin (user, password) VALUES ('root', 'root');
INSERT INTO admin (user, password) VALUES ('admin', 'admin');