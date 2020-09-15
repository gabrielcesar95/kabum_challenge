-- --------------------------------------------------------
-- Servidor:                     localhost
-- Versão do servidor:           10.4.8-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              10.3.0.5771
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para kabum_challenge
CREATE DATABASE IF NOT EXISTS `kabum_challenge` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `kabum_challenge`;

-- Copiando estrutura para tabela kabum_challenge.addresses
CREATE TABLE IF NOT EXISTS `addresses` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`zip_code` varchar(9) NOT NULL,
	`address` varchar(255) NOT NULL,
	`state` varchar(2) NOT NULL,
	`city` varchar(128) NOT NULL,
	`area` varchar(128) NOT NULL,
	`number` varchar(255) DEFAULT NULL,
	`details` varchar(255) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
	`updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela kabum_challenge.addresses: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` (`id`, `zip_code`, `address`, `state`, `city`, `area`, `number`, `details`, `created_at`, `updated_at`) VALUES
(1, '13400-560', 'Avenida Independência', 'SP', 'Piracicaba', 'Alemães', '200', '', '2020-02-19 10:51:19', '2020-02-20 13:29:12');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;

-- Copiando estrutura para tabela kabum_challenge.customers
CREATE TABLE IF NOT EXISTS `customers` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`birth_date` date DEFAULT NULL,
	`cpf` varchar(11) NOT NULL,
	`rg` varchar(30) DEFAULT NULL,
	`phone` varchar(11) NOT NULL,
	`created_at` datetime DEFAULT current_timestamp(),
	`updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (`id`),
	UNIQUE KEY `cpf` (`cpf`),
	FULLTEXT KEY `full_text` (`name`,`cpf`,`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela kabum_challenge.customers: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` (`id`, `name`, `birth_date`, `cpf`, `rg`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Gabriel Cesar Mello', '1995-03-02', '52234570050', '483532691', '19988889999', '2020-02-19 17:29:14', '2020-02-20 13:31:11');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;

-- Copiando estrutura para tabela kabum_challenge.customer_has_address
CREATE TABLE IF NOT EXISTS `customer_has_address` (
	`customer_id` int(10) unsigned NOT NULL,
	`address_id` int(10) unsigned NOT NULL,
	KEY `fk_customer_has_address_customers` (`customer_id`),
	KEY `fk_customer_has_address_addresses` (`address_id`),
	CONSTRAINT `fk_customer_has_address_addresses` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
	CONSTRAINT `fk_customer_has_address_customers` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela kabum_challenge.customer_has_address: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `customer_has_address` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_has_address` ENABLE KEYS */;

-- Copiando estrutura para tabela kabum_challenge.users
CREATE TABLE IF NOT EXISTS `users` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`address_id` int(11) unsigned DEFAULT NULL,
	`name` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	`birth_date` date DEFAULT NULL,
	`photo` varchar(255) DEFAULT NULL,
	`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
	`updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	PRIMARY KEY (`id`),
	UNIQUE KEY `email` (`email`),
	KEY `fk_users_addresses` (`address_id`),
	FULLTEXT KEY `full_text` (`name`,`email`),
	CONSTRAINT `fk_users_addresses` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Copiando dados para a tabela kabum_challenge.users: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `address_id`, `name`, `email`, `password`, `birth_date`, `photo`, `created_at`, `updated_at`) VALUES
(1, 1, 'Gabriel Cesar Mello 2', '95gabrielcesar@gmail.com', '$2y$10$yG39IEmpCRPR.qf/pg82F.uMbzS1UZjy.RUZl1Vp.IHwaCe0gmFtC', '1995-03-02', 'images/2020/02/gabriel-cesar-mello-2.jpg', '2020-02-19 10:50:21', '2020-02-20 13:25:06');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
