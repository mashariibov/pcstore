<?php


require_once __DIR__ . "/db/config.php";

$db->exec("CREATE TABLE IF NOT EXISTS users (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	username TEXT,
	password TEXT ,
	is_admin INTEGER DEFAULT 0
)");


//default admin user
$username = "admin";
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);
$db ->exec("INSERT INTO users (username, password, is_admin) VALUES ('$username', '$hash', 1)");


echo "Database tayyor";



$db->exec("CREATE TABLE IF NOT EXISTS products (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	name TEXT,
	desc TEXT,
	price REAL,
	image TEXT NULL

);");


