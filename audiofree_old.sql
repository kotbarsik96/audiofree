-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: audiofree
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cart_product`
--

DROP TABLE IF EXISTS `cart_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `purchased` tinyint(1) NOT NULL,
  `quantity` int NOT NULL,
  `variations` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_oneclick` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_product_cart_id_foreign` (`cart_id`),
  KEY `cart_product_product_id_foreign` (`product_id`),
  KEY `cart_product_order_id_foreign` (`order_id`),
  CONSTRAINT `cart_product_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_product_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `cart_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_product`
--

/*!40000 ALTER TABLE `cart_product` DISABLE KEYS */;
INSERT INTO `cart_product` VALUES (1,1,10,NULL,0,1,'Цвет_=_Белый',0,'2023-11-24 08:16:39','2023-11-24 08:16:39');
/*!40000 ALTER TABLE `cart_product` ENABLE KEYS */;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (1,1,'2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;

--
-- Table structure for table `delivery_types`
--

DROP TABLE IF EXISTS `delivery_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `delivery_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `delivery_types`
--

/*!40000 ALTER TABLE `delivery_types` DISABLE KEYS */;
INSERT INTO `delivery_types` VALUES (1,'inside_location','Доставка в пределах города','2023-11-22 13:44:03','2023-11-22 13:44:03'),(2,'outside_location','Доставка за пределы города','2023-11-22 13:44:03','2023-11-22 13:44:03'),(3,'pickup','Самовывоз','2023-11-22 13:44:03','2023-11-22 13:44:03'),(4,'express_delivery','Экспресс-доставка','2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `delivery_types` ENABLE KEYS */;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorites_user_id_foreign` (`user_id`),
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,1,'2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;

--
-- Table structure for table `favorites_product`
--

DROP TABLE IF EXISTS `favorites_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `favorites_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorites_product_favorites_id_foreign` (`favorites_id`),
  KEY `favorites_product_product_id_foreign` (`product_id`),
  CONSTRAINT `favorites_product_favorites_id_foreign` FOREIGN KEY (`favorites_id`) REFERENCES `favorites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites_product`
--

/*!40000 ALTER TABLE `favorites_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites_product` ENABLE KEYS */;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `size_kb` int NOT NULL,
  `width` int NOT NULL,
  `height` int NOT NULL,
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `images_user_id_foreign` (`user_id`),
  CONSTRAINT `images_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

/*!40000 ALTER TABLE `images` DISABLE KEYS */;
INSERT INTO `images` VALUES (91,'images/gallery/1/','tape-slider','865fa006_full-size','png',430,596,596,'full-size.png',1,'2023-11-24 08:25:07','2023-11-24 08:25:29'),(92,'images/gallery/1/','tape-slider','86c4ee6d_headset','png',470,662,662,'headset.png',1,'2023-11-24 08:25:26','2023-11-24 08:25:29'),(93,'images/gallery/1/','tape-slider','86c4ee6d_on-ear-green','png',470,596,596,'on-ear-green.png',1,'2023-11-24 08:25:27','2023-11-24 08:25:29'),(94,'images/gallery/1/','tape-slider','e1d54d96_on-ear-red','png',415,596,596,'on-ear-red.png',1,'2023-11-24 08:25:27','2023-11-24 08:25:29'),(95,'images/gallery/1/','tape-slider','e1d54d96_true-wireless','png',446,662,662,'true-wireless.png',1,'2023-11-24 08:25:27','2023-11-24 08:25:29'),(96,'images/gallery/1/','cta-5-bests-test','3b7c317b_5-models-mobile','png',86,366,325,'5-models-mobile.png',1,'2023-11-24 08:25:46','2023-11-24 08:25:47'),(97,'images/gallery/1/','cta-5-bests-test','3b7c317b_5-models-test','png',195,651,554,'5-models-test.png',1,'2023-11-24 08:25:47','2023-11-24 08:25:47'),(98,'images/gallery/1/','wireless-advantages','fd4f3bcf_headphones','png',89,348,247,'headphones.png',1,'2023-11-24 08:25:55','2023-11-24 08:25:55'),(99,'images/gallery/1/','personal-consult','b4ecafd6_consult','png',235,518,571,'consult.png',1,'2023-11-24 08:26:02','2023-11-24 08:26:05'),(100,'images/gallery/1/','personal-consult','182a106b_consult-mob','png',99,294,326,'consult-mob.png',1,'2023-11-24 08:26:03','2023-11-24 08:26:05'),(101,'images/gallery/1/','personal-consult','182a106b_consult-tablet','png',184,400,441,'consult-tablet.png',1,'2023-11-24 08:26:03','2023-11-24 08:26:05');
/*!40000 ALTER TABLE `images` ENABLE KEYS */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'00_create_delivery_types_table',1),(2,'00_create_payment_types_table',1),(3,'00_create_taxonomies_types_table',1),(4,'01_create_roles_table',1),(5,'02_create_users_table',1),(6,'03_create_images_table',1),(7,'03_create_order_statuses_table',1),(8,'04_create_orders_table',1),(9,'05_create_taxonomies_table',1),(10,'06_create_product_statuses_table',1),(11,'07_create_products_table',1),(12,'08_create_product_info_table',1),(13,'10_create_variations_table',1),(14,'11_create_variation_values_table',1),(15,'12_create_carts_table',1),(16,'12_create_favorites_table',1),(17,'13_create_cart_product_table',1),(18,'13_create_favorites_product_table',1),(19,'14_create_ratings_table',1),(20,'15_create_product_images_table',1),(21,'18_create_verify_email_table',1),(22,'19_create_product_statistics_table',1),(23,'2019_12_14_000001_create_personal_access_tokens_table',1),(24,'20_create_taxonomies_meta_table',1),(25,'21_create_password_reset_tokens_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

--
-- Table structure for table `order_statuses`
--

DROP TABLE IF EXISTS `order_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_statuses_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_statuses`
--

/*!40000 ALTER TABLE `order_statuses` DISABLE KEYS */;
INSERT INTO `order_statuses` VALUES (1,'waiting_userdata','Оформляется','2023-11-22 13:44:03','2023-11-22 13:44:03'),(2,'waiting_payment','Ожидает оплаты','2023-11-22 13:44:03','2023-11-22 13:44:03'),(3,'in_delivery_paid','Оплачен, в доставке','2023-11-22 13:44:03','2023-11-22 13:44:03'),(4,'in_delivery_not_paid','Не оплачен, в доставке','2023-11-22 13:44:03','2023-11-22 13:44:03'),(5,'recieved','Получен','2023-11-22 13:44:03','2023-11-22 13:44:03'),(6,'paid','Оплачен','2023-11-22 13:44:03','2023-11-22 13:44:03'),(7,'rejected','Отклонен','2023-11-22 13:44:03','2023-11-22 13:44:03'),(8,'waiting_return','Ожидает возврат','2023-11-22 13:44:03','2023-11-22 13:44:03'),(9,'returned','Возвращен','2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `order_statuses` ENABLE KEYS */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `quantity` int unsigned NOT NULL,
  `total_price` int unsigned NOT NULL,
  `is_oneclick` tinyint(1) NOT NULL,
  `paid` int NOT NULL,
  `applied_coupon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_rows` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_type` bigint unsigned DEFAULT NULL,
  `payment_type` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_delivery_type_foreign` (`delivery_type`),
  KEY `orders_payment_type_foreign` (`payment_type`),
  KEY `orders_status_foreign` (`status`),
  CONSTRAINT `orders_delivery_type_foreign` FOREIGN KEY (`delivery_type`) REFERENCES `delivery_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_payment_type_foreign` FOREIGN KEY (`payment_type`) REFERENCES `payment_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_status_foreign` FOREIGN KEY (`status`) REFERENCES `order_statuses` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

--
-- Table structure for table `payment_types`
--

DROP TABLE IF EXISTS `payment_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_types`
--

/*!40000 ALTER TABLE `payment_types` DISABLE KEYS */;
INSERT INTO `payment_types` VALUES (1,'cash','Наличными','2023-11-22 13:44:03','2023-11-22 13:44:03'),(2,'bank_card','Банковской картой','2023-11-22 13:44:03','2023-11-22 13:44:03'),(3,'bank_card_after_delivery','Банковской картой при получении','2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `payment_types` ENABLE KEYS */;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_foreign` (`product_id`),
  KEY `product_images_image_id_foreign` (`image_id`),
  CONSTRAINT `product_images_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;

--
-- Table structure for table `product_info`
--

DROP TABLE IF EXISTS `product_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_info` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_info_product_id_foreign` (`product_id`),
  CONSTRAINT `product_info_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_info`
--

/*!40000 ALTER TABLE `product_info` DISABLE KEYS */;
INSERT INTO `product_info` VALUES (1,1,'Наличие микрофона','да','2023-11-22 18:58:07','2023-11-22 18:58:07'),(2,1,'Конструкция наушников','вкладыши','2023-11-22 18:58:07','2023-11-22 18:58:07'),(3,1,'Шумоподавление','активное','2023-11-22 18:58:07','2023-11-22 18:58:07'),(4,1,'Время работы в режиме разговора, ч','4','2023-11-22 18:58:07','2023-11-22 18:58:07'),(5,1,'Мин. частота, Гц','20','2023-11-22 18:58:07','2023-11-22 18:58:07'),(6,1,'Макс. частота, Гц','20000','2023-11-22 18:58:07','2023-11-22 18:58:07'),(7,1,'Импеданс, Ом','32','2023-11-22 18:58:07','2023-11-22 18:58:07'),(8,1,'Управление наушниками','кнопочное','2023-11-22 18:58:07','2023-11-22 18:58:07'),(9,1,'Защита от влаги','да','2023-11-22 18:58:07','2023-11-22 18:58:07'),(10,2,'Наличие микрофона','да','2023-11-22 19:02:35','2023-11-22 19:02:35'),(11,2,'Конструкция наушников','вкладыши','2023-11-22 19:02:35','2023-11-22 19:02:35'),(12,2,'Время работы в режиме разговора, ч','4','2023-11-22 19:02:35','2023-11-22 19:02:35'),(13,2,'Мин. частота, Гц','20','2023-11-22 19:02:35','2023-11-22 19:02:35'),(14,2,'Макс. частота, Гц','20000','2023-11-22 19:02:35','2023-11-22 19:02:35'),(15,2,'Импеданс, Ом','16','2023-11-22 19:02:35','2023-11-22 19:02:35'),(16,2,'Управление наушниками','сенсорное','2023-11-22 19:02:35','2023-11-22 19:02:35'),(17,2,'Защита от влаги','есть','2023-11-22 19:02:35','2023-11-22 19:02:35'),(18,3,'Наличие микрофона','есть','2023-11-22 19:05:58','2023-11-22 19:05:58'),(19,3,'Конструкция наушников','накладные','2023-11-22 19:05:58','2023-11-22 19:05:58'),(20,3,'Шумоподавление','активное','2023-11-22 19:05:58','2023-11-22 19:05:58'),(21,3,'Мин. частота, Гц','8','2023-11-22 19:05:58','2023-11-22 19:05:58'),(22,3,'Макс. частота, Гц','20000','2023-11-22 19:05:58','2023-11-22 19:05:58'),(23,3,'Импеданс, Ом','32','2023-11-22 19:05:58','2023-11-22 19:05:58'),(24,3,'Управление наушниками','кнопочное','2023-11-22 19:05:58','2023-11-22 19:05:58'),(25,3,'Защита от влаги','есть','2023-11-22 19:05:58','2023-11-22 19:05:58'),(26,4,'Наличие микрофона','есть','2023-11-22 19:09:34','2023-11-22 19:09:34'),(27,4,'Конструкция наушников','вкладыши','2023-11-22 19:09:34','2023-11-22 19:09:34'),(28,4,'Шумоподавление','активное','2023-11-22 19:09:34','2023-11-22 19:09:34'),(29,4,'Время работы в режиме разговора, ч','4.5','2023-11-22 19:09:34','2023-11-22 19:09:34'),(30,4,'Мин. частота, Гц','50','2023-11-22 19:09:34','2023-11-22 19:09:34'),(31,4,'Макс. частота, Гц','20000','2023-11-22 19:09:34','2023-11-22 19:09:34'),(32,4,'Импеданс, Ом','16','2023-11-22 19:09:34','2023-11-22 19:09:34'),(33,4,'Управление наушниками','сенсорное','2023-11-22 19:09:34','2023-11-22 19:09:34'),(34,4,'Защита от влаги','есть','2023-11-22 19:09:34','2023-11-22 19:09:34'),(35,5,'Наличие микрофона','есть','2023-11-22 19:12:01','2023-11-22 19:12:01'),(36,5,'Конструкция наушников','внутриканальные','2023-11-22 19:12:01','2023-11-22 19:12:01'),(37,5,'Шумоподавление','пассивное','2023-11-22 19:12:01','2023-11-22 19:12:01'),(38,5,'Время работы в режиме разговора, ч','5','2023-11-22 19:12:01','2023-11-22 19:12:01'),(39,5,'Мин. частота, Гц','20','2023-11-22 19:12:01','2023-11-22 19:12:01'),(40,5,'Макс. частота, Гц','20000','2023-11-22 19:12:01','2023-11-22 19:12:01'),(41,5,'Управление наушниками','сенсорное','2023-11-22 19:12:01','2023-11-22 19:12:01'),(42,6,'Наличие микрофона','есть','2023-11-22 19:15:53','2023-11-22 19:15:53'),(43,6,'Конструкция наушников','внутриканальные','2023-11-22 19:15:53','2023-11-22 19:15:53'),(44,6,'Шумоподавление','активное','2023-11-22 19:15:53','2023-11-22 19:15:53'),(45,6,'Время работы в режиме разговора, ч','20','2023-11-22 19:15:53','2023-11-22 19:15:53'),(46,6,'Мин. частота, Гц','20','2023-11-22 19:15:53','2023-11-22 19:15:53'),(47,6,'Макс. частота, Гц','20000','2023-11-22 19:15:53','2023-11-22 19:15:53'),(48,6,'Импеданс, Ом','32','2023-11-22 19:15:53','2023-11-22 19:15:53'),(49,7,'Совместимость','универсальный','2023-11-22 19:18:59','2023-11-22 19:18:59'),(50,7,'Время автономной работы, ч','7','2023-11-22 19:18:59','2023-11-22 19:18:59'),(51,7,'Материал корпуса','пластик','2023-11-22 19:18:59','2023-11-22 19:18:59'),(52,7,'Вес, г','8','2023-11-22 19:18:59','2023-11-22 19:18:59'),(53,8,'Наличие микрофона','есть','2023-11-22 20:50:22','2023-11-22 20:50:22'),(54,8,'Конструкция наушников','внутриканальные','2023-11-22 20:50:22','2023-11-22 20:50:22'),(55,8,'Шумоподавление','пассивное','2023-11-22 20:50:22','2023-11-22 20:50:22'),(56,8,'Время работы в режиме разговора, ч','11','2023-11-22 20:50:22','2023-11-22 20:50:22'),(57,8,'Мин. частота, Гц','20','2023-11-22 20:50:22','2023-11-22 20:50:22'),(58,8,'Макс. частота, Гц','20000','2023-11-22 20:50:22','2023-11-22 20:50:22'),(59,8,'Импеданс, Ом','32','2023-11-22 20:50:22','2023-11-22 20:50:22'),(60,9,'Наличие микрофона','есть','2023-11-22 21:12:29','2023-11-22 21:12:29'),(61,9,'Конструкция наушников','внутриканальные','2023-11-22 21:12:29','2023-11-22 21:12:29'),(62,9,'Шумоподавление','Пассивное','2023-11-22 21:12:29','2023-11-22 21:12:29'),(63,9,'Время работы в режиме разговора, ч','11','2023-11-22 21:12:29','2023-11-22 21:12:29'),(64,9,'Мин. частота, Гц','20','2023-11-22 21:12:29','2023-11-22 21:12:29'),(65,9,'Макс. частота, Гц','20000','2023-11-22 21:12:29','2023-11-22 21:12:29'),(66,9,'Импеданс, Ом','32','2023-11-22 21:12:29','2023-11-22 21:12:29'),(67,10,'Наличие микрофона','есть','2023-11-22 21:15:04','2023-11-22 21:15:04'),(68,10,'Конструкция наушников','внутриканальные','2023-11-22 21:15:04','2023-11-22 21:15:04'),(69,10,'Шумоподавление','Пассивное','2023-11-22 21:15:04','2023-11-22 21:15:04'),(70,10,'Время работы в режиме разговора, ч','11','2023-11-22 21:15:04','2023-11-22 21:15:04'),(71,10,'Мин. частота, Гц','20','2023-11-22 21:15:04','2023-11-22 21:15:04'),(72,10,'Макс. частота, Гц','20000','2023-11-22 21:15:04','2023-11-22 21:15:04'),(73,10,'Импеданс, Ом','32','2023-11-22 21:15:04','2023-11-22 21:15:04'),(74,11,'Наличие микрофона','есть','2023-11-22 21:19:24','2023-11-22 21:19:24'),(75,11,'Конструкция наушников','внутриканальные','2023-11-22 21:19:24','2023-11-22 21:19:24'),(76,11,'Шумоподавление','Пассивное','2023-11-22 21:19:24','2023-11-22 21:19:24'),(77,11,'Время работы в режиме разговора, ч','11','2023-11-22 21:19:24','2023-11-22 21:19:24'),(78,11,'Мин. частота, Гц','20','2023-11-22 21:19:24','2023-11-22 21:19:24'),(79,11,'Макс. частота, Гц','20000','2023-11-22 21:19:24','2023-11-22 21:19:24'),(80,11,'Импеданс, Ом','32','2023-11-22 21:19:24','2023-11-22 21:19:24'),(81,12,'Наличие микрофона','есть','2023-11-22 21:21:54','2023-11-22 21:21:54'),(82,12,'Конструкция наушников','внутриканальные','2023-11-22 21:21:54','2023-11-22 21:21:54'),(83,12,'Шумоподавление','Пассивное','2023-11-22 21:21:54','2023-11-22 21:21:54'),(84,12,'Время работы в режиме разговора, ч','11','2023-11-22 21:21:54','2023-11-22 21:21:54'),(85,12,'Мин. частота, Гц','20','2023-11-22 21:21:54','2023-11-22 21:21:54'),(86,12,'Макс. частота, Гц','20000','2023-11-22 21:21:54','2023-11-22 21:21:54'),(87,12,'Импеданс, Ом','32','2023-11-22 21:21:54','2023-11-22 21:21:54'),(88,13,'Наличие микрофона','есть','2023-11-22 21:23:55','2023-11-22 21:23:55'),(89,13,'Конструкция наушников','внутриканальные','2023-11-22 21:23:55','2023-11-22 21:23:55'),(90,13,'Шумоподавление','Пассивное','2023-11-22 21:23:55','2023-11-22 21:23:55'),(91,13,'Время работы в режиме разговора, ч','11','2023-11-22 21:23:55','2023-11-22 21:23:55'),(92,13,'Мин. частота, Гц','20','2023-11-22 21:23:55','2023-11-22 21:23:55'),(93,13,'Макс. частота, Гц','20000','2023-11-22 21:23:55','2023-11-22 21:23:55'),(94,13,'Импеданс, Ом','32','2023-11-22 21:23:55','2023-11-22 21:23:55'),(95,14,'Наличие микрофона','есть','2023-11-22 21:26:09','2023-11-22 21:26:09'),(96,14,'Конструкция наушников','внутриканальные','2023-11-22 21:26:09','2023-11-22 21:26:09'),(97,14,'Шумоподавление','Пассивное','2023-11-22 21:26:09','2023-11-22 21:26:09'),(98,14,'Время работы в режиме разговора, ч','11','2023-11-22 21:26:09','2023-11-22 21:26:09'),(99,14,'Мин. частота, Гц','20','2023-11-22 21:26:09','2023-11-22 21:26:09'),(100,14,'Макс. частота, Гц','20000','2023-11-22 21:26:09','2023-11-22 21:26:09'),(101,14,'Импеданс, Ом','32','2023-11-22 21:26:09','2023-11-22 21:26:09'),(102,15,'Наличие микрофона','есть','2023-11-22 21:27:44','2023-11-22 21:27:44'),(103,15,'Конструкция наушников','внутриканальные','2023-11-22 21:27:44','2023-11-22 21:27:44'),(104,15,'Шумоподавление','Пассивное','2023-11-22 21:27:44','2023-11-22 21:27:44'),(105,15,'Время работы в режиме разговора, ч','11','2023-11-22 21:27:44','2023-11-22 21:27:44'),(106,15,'Мин. частота, Гц','20','2023-11-22 21:27:44','2023-11-22 21:27:44'),(107,15,'Макс. частота, Гц','20000','2023-11-22 21:27:44','2023-11-22 21:27:44'),(108,15,'Импеданс, Ом','32','2023-11-22 21:27:44','2023-11-22 21:27:44');
/*!40000 ALTER TABLE `product_info` ENABLE KEYS */;

--
-- Table structure for table `product_statistics`
--

DROP TABLE IF EXISTS `product_statistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_statistics` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `sold` bigint unsigned DEFAULT NULL,
  `in_favorites` bigint unsigned DEFAULT NULL,
  `income` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_statistics_product_id_foreign` (`product_id`),
  CONSTRAINT `product_statistics_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_statistics`
--

/*!40000 ALTER TABLE `product_statistics` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_statistics` ENABLE KEYS */;

--
-- Table structure for table `product_statuses`
--

DROP TABLE IF EXISTS `product_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_statuses`
--

/*!40000 ALTER TABLE `product_statuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_statuses` ENABLE KEYS */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int unsigned NOT NULL,
  `discount_price` int unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` int unsigned DEFAULT NULL,
  `product_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_image_id_foreign` (`image_id`),
  KEY `products_product_status_foreign` (`product_status`),
  KEY `products_brand_foreign` (`brand`),
  KEY `products_category_foreign` (`category`),
  KEY `products_type_foreign` (`type`),
  CONSTRAINT `products_brand_foreign` FOREIGN KEY (`brand`) REFERENCES `taxonomies` (`name`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_category_foreign` FOREIGN KEY (`category`) REFERENCES `taxonomies` (`name`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_image_id_foreign` FOREIGN KEY (`image_id`) REFERENCES `images` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_product_status_foreign` FOREIGN KEY (`product_status`) REFERENCES `taxonomies` (`name`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_type_foreign` FOREIGN KEY (`type`) REFERENCES `taxonomies` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Airdots',2000,1500,'{\"time\":1700665085648,\"blocks\":[{\"id\":\"F4xEhxr6lt\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Беспроводные наушники Xiaomi</b>\"}},{\"id\":\"XPWRhoIifK\",\"type\":\"paragraph\",\"data\":{\"text\":\"Беспроводные наушники нового поколения усовершенствованной версии. Ключевой особенностью является чистый звук и четкий микрофон. Они достаточно легкие и компактные, надежно держатся в ушной раковине и не выпадают.\"}}],\"version\":\"2.28.2\"}',50,'Активен','Xiaomi','Наушники','Беспроводные',NULL,'2023-11-22 18:58:07','2023-11-22 18:58:07'),(2,'Airpods',17800,17000,'{\"time\":1700665353183,\"blocks\":[{\"id\":\"oPX9Bx7JEZ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Технология пространственного аудио с функцией динамического отслеживания движений головы создаёт по‑настоящему живую и объёмную картину звука\"}},{\"id\":\"t6rhYGC6d2\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Адаптивный эквалайзер автоматически подстраивает звучание персонально под вас.</b>\"}},{\"id\":\"iEjUFlka2c\",\"type\":\"paragraph\",\"data\":{\"text\":\"Датчик нажатия позволяет легко управлять воспроизведением, звонками — и не только. Защита от пота и воды до 6 часов в режиме прослушивания аудио без подзарядки; до 30 часов в режиме прослушивания аудио с подзарядкой в футляре MagSafe2. \"}},{\"id\":\"YGhzYNUWGL\",\"type\":\"paragraph\",\"data\":{\"text\":\"Быстрый вызов голосового ассистента фразой «Привет, Siri»4.Исключительно простая настройка, активация при надевании и мгновенное переключение с одного устройства на другое5.Звук с iPhone, iPad, iPod touch или Apple TV можно передавать сразу на две пары AirPods.<br>\"}}],\"version\":\"2.28.2\"}',41,'Активен','Apple','Наушники','Беспроводные',NULL,'2023-11-22 19:02:35','2023-11-22 19:02:35'),(3,'Airpods Max',56000,0,'{\"time\":1700665556593,\"blocks\":[{\"id\":\"HLWMYh0SkB\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Продуманно просто:&nbsp;</b>В AirPods Max реализованы те же технологии беспроводной работы и удобного управления, что и в других моделях AirPods. От первой настройки до быстрых команд Siri — звук будет с вами всегда и на всех устройствах.\"}},{\"id\":\"_KLQgAvDbt\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Чехол в помощь аккумулятору:&nbsp;</b>Если убрать наушники AirPods Max в мягкий и тонкий чехол Smart Case, они сразу перейдут в режим сверхнизкого энергопотребления, чтобы не тратить заряд аккумулятора впустую.\"}},{\"id\":\"SNGzixvRJI\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Эта музыка будет долгой:&nbsp;</b>20 часов музыки, фильмов, разговоров — при использовании активного шумоподавления и пространственного аудио. AirPods Max можно легко зарядить через разъём Lightning, причём быстрой 5 минутной подзарядки хватит ещё на 1,5 часа прослушивания.\"}}],\"version\":\"2.28.2\"}',24,'Активен','Apple','Наушники','Беспроводные',NULL,'2023-11-22 19:03:13','2023-11-22 19:05:58'),(4,'Airpods Pro',24000,0,'{\"time\":1700665772511,\"blocks\":[{\"id\":\"g2GBeAKJCC\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Вашим ушам это по душе:&nbsp;</b>Это наушники совершенно нового класса. AirPods Pro исключительно комфортны, в них реализована технология активного шумоподавления, и можно даже выбрать вкладыши своего размера. Вы будете лучше чувствовать музыку, а не наушники.\"}},{\"id\":\"FSvyMt0dds\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>В комплекте три пары мягких силиконовых вкладышей:&nbsp;</b>Внутри каждого вкладыша есть особая коническая полость, которая помогает ему лучше адаптироваться к геометрии уха и надёжнее держать наушник. А отверстия во вкладышах позволяют выровнять давление, поэтому наушники будут для вас совершенно незаметны.\"}},{\"id\":\"tAnDbcsr92\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Когда звук создаёт тишину:&nbsp;</b>AirPods Pro — уникальные наушники с технологией активного шумоподавления, которые непрерывно оптимизируют звучание с учётом формы вашего уха и положения вкладышей. Они отлично заглушают внешние шумы, поэтому вы можете полностью погрузиться в музыку.<br>\"}},{\"id\":\"Jd8uLtCDlt\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Хотите услышать, что вокруг?:&nbsp;</b>Удерживайте датчик нажатия на корпусе, чтобы переключиться с активного шумоподавления на Прозрачный режим: внешний мир будет звучать так же естественно, как если бы вы общались без наушников.\"}},{\"id\":\"5h-Icfe00v\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Вы только послушайте:&nbsp;</b>Адаптивный эквалайзер автоматически регулирует параметры звука с учётом формы уха, создавая превосходное, неизменно богатое звучание.\"}},{\"id\":\"sKqfHJNmaR\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Самые звонкие технологии:&nbsp;</b>Компактные и высокотехнологичные AirPods Pro созданы на основе архитектуры System in Package с уникальным чипом H1, который поддерживает работу всех функций AirPods Pro — от аудио до Siri.\"}}],\"version\":\"2.28.2\"}',46,'Активен','Apple','Наушники','Беспроводные',NULL,'2023-11-22 19:06:46','2023-11-22 19:09:34'),(5,'Galaxy Buds 2',1500,0,'{\"time\":1700665989651,\"blocks\":[{\"id\":\"jKU2EmF0nm\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>КАЧЕСТВО ЗВУКА HI-FI:</b>&nbsp;звук студийного качества предназначен не только для профессионалов; с Galaxy Buds2 Pro** вы почувствуете каждую ноту и получите новый уровень прослушивания, независимо от того, пролистываете ли вы плейлисты или следите за уведомлениями о подкастах. .Enhanced 360 Audio: усиливайте то, что вам нравится, сводите к минимуму то, что вам не нравится; Enhanced 360 Audio** раскрывает ваши любимые тона со всех сторон для персонализированного объемного звучания при каждом ударе\"}},{\"id\":\"l3xJxAp-N3\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>ИДЕАЛЬНАЯ ПОДГОНКА:</b>&nbsp;Втяните их и забудьте, что они там; Наушники Galaxy Buds2 Pro разработаны так, чтобы быть более удобными, чем когда-либо, для надежной посадки, независимо от того, что вы делаете; Удобны для всех ваших движений, независимо от того, работаете ли вы или упражнения\"}},{\"id\":\"wY5GHGrJzn\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Технология HD Voice:</b>&nbsp;превращает каждый звонок в чат высокой четкости; технология HD Voice изолирует ваш голос от окружающего шума, поэтому он остается четким даже в шуме и суете вокруг вас\"}},{\"id\":\"lziVKwfyV8\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Режим Smart Talk:</b> продолжайте разговор, не снимая наушников; при обнаружении вашего голоса режим Smart Talk отключает ANC, выключает громкость и переводит гарнитуру в режим окружающего звука***ВОДОНЕПРОНИЦАЕМОСТЬ: водонепроницаемость не помешает вашей тренировке; водонепроницаемые наушники Galaxy Buds2 Pro со степенью защиты IPX7 сохранят ритм даже под небольшим дождем; они защищены от погружения на глубину до 3 футов&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',61,'Активен','Samsung','Наушники','Беспроводные',NULL,'2023-11-22 19:12:01','2023-11-22 19:13:11'),(6,'Galaxy Buds Live',2000,1700,'{\"time\":1700666151277,\"blocks\":[{\"id\":\"TSEECNJcSK\",\"type\":\"paragraph\",\"data\":{\"text\":\"12-мм динамик и канал усиления басов\"}},{\"id\":\"HzTo_B5NOI\",\"type\":\"paragraph\",\"data\":{\"text\":\"Активное шумоподавление\"}},{\"id\":\"wz_-83y0WS\",\"type\":\"paragraph\",\"data\":{\"text\":\"До 8 часов автономной работы\"}},{\"id\":\"qpxlB2aN6i\",\"type\":\"paragraph\",\"data\":{\"text\":\"Зарядный футляр с 21 дополнительным часомAmbient Aware позволяет вернуть звук\"}},{\"id\":\"srclw2866y\",\"type\":\"paragraph\",\"data\":{\"text\":\"Регулируемые законцовки крыльев и эргономичная форма3 микрофона и технология формирования луча\"}},{\"id\":\"-obKTrKinZ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Беспроводные телефонные звонки без помощи рук\"}},{\"id\":\"9erkIgJznc\",\"type\":\"paragraph\",\"data\":{\"text\":\"Поддержка цифрового помощника Bixby.Водонепроницаемость по стандарту IPX2Наушники: 60 мАч\"}},{\"id\":\"mSV_28YwQk\",\"type\":\"paragraph\",\"data\":{\"text\":\"Зарядный чехол: 472 мАч\"}},{\"id\":\"-4zmkdpDVE\",\"type\":\"paragraph\",\"data\":{\"text\":\"Bluetooth 5.0Поддержка Android 5.0 или выше ОЗУ 1,5 ГБ и выше\"}},{\"id\":\"gCIUnrLeu4\",\"type\":\"paragraph\",\"data\":{\"text\":\"Акселерометр, ИК, Холл, сенсорный, рукоятка, VPU (передатчик голоса)&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',51,'Активен','Samsung','Наушники','Беспроводные',NULL,'2023-11-22 19:14:06','2023-11-22 19:15:53'),(7,'Gear Icon X',7000,5600,'{\"time\":1700666337704,\"blocks\":[{\"id\":\"ACTh9nA0zQ\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Безграничная музыка:&nbsp;</b>Беспроводные наушники Gear IconX (2018) работают как автономный музыкальный плеер, просто загрузите аудиофайлы в их память и наслаждайтесь любимыми композициями. Создайте плейлисты, как подскажет ваша фантазия: новинки, музыка для поездок или треки для спортзала.\"}},{\"id\":\"eIVf1QXawI\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Ответное чувство:&nbsp;</b>Gear IconX (2018) использует продвинутый кодек Samsung Scalable Codec, который обеспечивает выдающееся качество звучания как во время воспроизведения музыки, так и во время использования наушников в качестве беспроводной гарнитуры.\"}},{\"id\":\"3pIuydcaje\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Делай зарядку:&nbsp;</b>До семи часов воспроизведения в автономном режиме и до 5 часов в потоковом. Когда пришло время для зарядки Gear IconX (2018), просто положи наушники в футляр. Благодаря функции быстрой зарядки ты сможешь зарядить наушники для использования в течение часа всего за 10 минут.\"}}],\"version\":\"2.28.2\"}',8,'Активен','Samsung','Наушники','Беспроводные',NULL,'2023-11-22 19:18:01','2023-11-22 19:18:59'),(8,'AM 61',2700,2400,'{\"time\":1700671820617,\"blocks\":[{\"id\":\"bSC8BdEtvg\",\"type\":\"paragraph\",\"data\":{\"text\":\"<b>Bluetooth стереогарнитура HUAWEI Sport Lite AM61 обеспечивает высокое качество звука и исключительный комфорт</b>. \"}},{\"id\":\"RQSnFYmOO_\",\"type\":\"paragraph\",\"data\":{\"text\":\"Эргономичная форма наушников в сочетании с гибкими ушными креплениями и мягкими вкладышами позволяет удобно прослушивать музыку и общаться без дискомфорта. За формирование реалистичного звука без искажения во всем диапазоне громкости отвечают динамические 11-мм излучатели. \"}},{\"id\":\"vO1sHBMpZf\",\"type\":\"paragraph\",\"data\":{\"text\":\"Микрофон с функцией подавления шума гарантирует точную передачу голоса во время общения. \"}},{\"id\":\"STB-Mq-TUL\",\"type\":\"paragraph\",\"data\":{\"text\":\"HUAWEI Sport Lite AM61 подключается к устройствам по технологии Bluetooth. \"}},{\"id\":\"04u0Om3iH_\",\"type\":\"paragraph\",\"data\":{\"text\":\"Благодаря магнитам можно соединять наушники между собой, что повышает удобство в эксплуатации гарнитуры. Одного полного цикла зарядки аккумулятора достаточно для 11 часов автономной работы.&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',51,'Активен','Huawei','Наушники','Беспроводные',NULL,'2023-11-22 20:50:22','2023-11-22 20:50:22'),(9,'Freebuds',2900,2600,'{\"time\":1700673147222,\"blocks\":[{\"id\":\"DnDJJUDKut\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники TWS HUAWEI FreeBuds с классическим белым цветом корпуса лишены проводов в своей конструкции, благодаря чему взыскательный меломан получает полную свободу действий в процессе прослушивания любимого плейлиста. \"}},{\"id\":\"GiE9-mLM6G\",\"type\":\"paragraph\",\"data\":{\"text\":\"Фирменное качество в сочетании с удобной посадкой позволит наслаждаться музыкальными композициями часами напролет, ни испытывая ни единого намека на дискомфорт. Модель получила закрытое акустическое оформление, благодаря которому звуки окружающей среды не будут вам мешать в процессе прослушивания музыки. 10-миллиметровые мембраны излучателей поддерживают стандартный диапазон воспроизведения частот 20-20000 Гц. \"}},{\"id\":\"lgq16OhwH4\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники TWS HUAWEI FreeBuds могут использоваться в качестве гарнитуры благодаря наличию в корпусе микрофона. Подключившись к смартфону, вы сможете ответить на входящий вызов, не доставая мобильное устройство из кармана. Беспроводная технология Bluetooth 5.2 обеспечивает хороший радиус действия и отсутствие задержки при передаче аудиосигнала. Аккумуляторы наушников гарантируют до 6 часов непрерывной работы, а еще 24 часа им подарит компактный зарядный кейс.&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',61,'Активен','Huawei','Наушники','Беспроводные',NULL,'2023-11-22 21:12:29','2023-11-22 21:12:29'),(10,'Tune 225 TWS',2900,2700,'{\"time\":1700673302351,\"blocks\":[{\"id\":\"A2UjL-4nJK\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники TWS JBL TUNE 225TWS − удобное и функциональное устройство, разработанное для прослушивания музыки и общения. Это наушники-вкладыши, обеспечивающие изоляцию от внешних шумов благодаря надежной фиксации в ушных раковинах. \"}},{\"id\":\"jX1BoVb1uM\",\"type\":\"paragraph\",\"data\":{\"text\":\"Аккумулятор позволит наушникам непрерывно звучать до 5 ч. В автономном режиме время работы повышается до 25 ч. Для восполнения заряда наушников достаточно поместить их в зарядный кейс.\"}},{\"id\":\"BQ412HzNwx\",\"type\":\"paragraph\",\"data\":{\"text\":\"Подключение устройства осуществляется при помощи технологии Bluetooth 5.0, обеспечивающей стабильную и устойчивую связь в радиусе 10 м. TWS JBL TUNE 225TWS поддерживают звучание на частоте 20-20000 Гц, что наряду с технологией Pure Bass делает звучание композиций насыщенным, глубоким и разнообразным. \"}},{\"id\":\"IFOSf7r-KQ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Двойной микрофон, зафиксированный на корпусе наушников, превращает их в гарнитуру, поэтому вы сможете отвечать на звонки, не доставая смартфон, что очень удобно, когда вы находитесь за рулем или на пробежке. Этому способствует дизайн «True Wireless».&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',51,'Активен','JBL','Наушники','Беспроводные',NULL,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(11,'Reflect',3500,3050,'{\"time\":1700673562389,\"blocks\":[{\"id\":\"BtUy9s9DPj\",\"type\":\"paragraph\",\"data\":{\"text\":\"Внутриканальные наушники JBL Reflect Flow Pro с шумоподавлением предназначены для повышения эффективности и безопасно размещаются в ушах. Защита от пота, дождя и пыли по стандарту IP68 позволяет работать и заниматься спортом где угодно.\"}}],\"version\":\"2.28.2\"}',56,'Активен','JBL','Наушники','Беспроводные',NULL,'2023-11-22 21:19:24','2023-11-22 21:19:24'),(12,'T 500BT',6000,5500,'{\"time\":1700673713140,\"blocks\":[{\"id\":\"DRSn3DjnD_\",\"type\":\"paragraph\",\"data\":{\"text\":\"Bluetooth-гарнитура JBL Tune 500BT – это возможность наслаждаться разнообразной музыкой где угодно и когда угодно. Модель с классическим черным цветом комфортно сидит на голове благодаря мягким амбушюрам накладного типа и регулируемому оголовью. \"}},{\"id\":\"BnXMNYKrCJ\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники со стереоформатом звучания оснащены фирменными излучателями с 32-миллиметровыми мембранами для высокого качества звука независимо от жанра музыки. \"}},{\"id\":\"0oNdxnA8jx\",\"type\":\"paragraph\",\"data\":{\"text\":\"Поддержка технологии Pure Bass обогатит звучание мощным и сочным басом. Благодаря закрытому акустическому оформлению ничто не будет отвлекать вас от плейлиста.Bluetooth-гарнитура JBL Tune 500BT оснащена микрофоном, скрытым в чашке наушников. \"}},{\"id\":\"yQWu9mgXDa\",\"type\":\"paragraph\",\"data\":{\"text\":\"Она наделила модель возможностью автоматически переключаться на мобильное устройство, если на него поступает вызов. В основе автономной работы гарнитуры лежит аккумулятор емкостью 300 мА*ч, гарантирующий до 16 часов непрерывного мощного звучания. Благодаря складной конструкции наушники займут минимум места в вашей сумке в процессе переноски или хранения.&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',51,'Активен','JBL','Наушники','Беспроводные',NULL,'2023-11-22 21:21:54','2023-11-22 21:21:54'),(13,'Mi Earphones 2',1500,0,'{\"time\":1700673833732,\"blocks\":[{\"id\":\"Ryu_x1FOu8\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники TWS Xiaomi Mi True Wireless Earphones 2 Basic представлены в белом корпусе. Это наушники-вкладыши, которые вставляются в уши и не используют для этого специальное крепление. \"}},{\"id\":\"oVq7RJ7uP0\",\"type\":\"paragraph\",\"data\":{\"text\":\"Питание наушники получают от аккумулятора, который обеспечивает непрерывное звучание в течение 5 ч. Особенность устройства в наличии зарядного кейса, который обеспечивает автоматическую подзарядку наушников, когда они находятся в состоянии покоя и фиксируются в кейсе, увеличивая время работы до 20 ч.\"}},{\"id\":\"sDiSRvjgAL\",\"type\":\"paragraph\",\"data\":{\"text\":\"Для связи с устройствами TWS Xiaomi Mi True Wireless Earphones 2 Basic используют технологию Bluetooth 5.0, обеспечивающую мгновенное сопряжение при повторном подключении: первый раз при подключении необходимо вручную настраивать устройство. \"}},{\"id\":\"qMllo6so_M\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники оснащены двойным микрофоном с функцией шумоподавления, который гарантирует стабильно высокое качество передачи голоса. Особенность наушников в поддержке функции обнаружения, при которой воспроизведение останавливается при отсутствии наушника в ухе и, наоборот, возобновляется, когда он присутствует в ухе.&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',51,'Активен','Xiaomi','Наушники','Беспроводные',NULL,'2023-11-22 21:23:55','2023-11-22 21:23:55'),(14,'Mi In-Ear',800,0,'{\"time\":1700673967616,\"blocks\":[{\"id\":\"jP-uRHo0lO\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники Xiaomi Piston Fresh Bloom обладают черным цветом и не имеют крепления. Модель относится к формату звуковой схемы 2.0 и создает закрытое акустическое оформление благодаря вставным амбушюрам с диаметром мембраны динамических излучателей 8 мм.\"}},{\"id\":\"SQUnzkHzvC\",\"type\":\"paragraph\",\"data\":{\"text\":\"Диапазон воспроизводимых частот варьируется от 20 до 20000 Гц. Устройство обладает встроенным микрофоном с фиксированным креплением. Длина кабеля наушников составляет 1.25 м. Xiaomi Piston Fresh Bloom весят 14 г и обладают 3 парами сменных амбушюр.&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',51,'Активен','Xiaomi','Наушники','Проводные',NULL,'2023-11-22 21:26:09','2023-11-22 21:26:09'),(15,'Redmi Buds',6000,5300,'{\"time\":1700806135779,\"blocks\":[{\"id\":\"rEYfplhFRd\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники TWS Xiaomi Redmi Buds 4 Pro – отличный выбор для поклонников активного образа жизни. Устройство, соответствующее концепции True Wireless Stereo, лишено проводов полностью: амбушюры не связаны между собой физически. \"}},{\"id\":\"H_VFC5xL_M\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники используют для подключения к смартфонам, планшетам и другой технике интерфейс Bluetooth. Радиус беспроводной связи стандартен – 10 м. В реальных условиях расстояние подключения может быть меньше из-за наличия препятствий, например – стен и крупной мебели. Наушники можно подключать к двум устройствам одновременно. Модель имеет сертификацию Hi-Res audio. Качество звучания наушников понравится наиболее взыскательным меломанам.\"}},{\"id\":\"jkD47CNzU0\",\"type\":\"paragraph\",\"data\":{\"text\":\"Наушники TWS Xiaomi Redmi Buds 4 Pro имеют закрытое акустическое оформление. Амбушюры надежно защищают пользователя от внешних шумов. Даже находясь на оживленной улице, вы сможете полностью сосредоточиться на прослушивании любимых композиций. Наушники также подходят для телефонных разговоров: эту возможность обеспечивает встроенный микрофон с шумоподавлением. Особенность комплектации устройства – наличие двух пар сменных амбушюров.&nbsp;&nbsp;\"}}],\"version\":\"2.28.2\"}',56,'Активен','Xiaomi','Наушники','Беспроводные',NULL,'2023-11-22 21:27:44','2023-11-24 06:08:55');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `value` tinyint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ratings_product_id_foreign` (`product_id`),
  KEY `ratings_user_id_foreign` (`user_id`),
  CONSTRAINT `ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'SUPER_ADMINISTRATOR',1,'2023-11-22 13:44:03','2023-11-22 13:44:03'),(2,'ADMINISTRATOR',2,'2023-11-22 13:44:03','2023-11-22 13:44:03'),(3,'USER',3,'2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

--
-- Table structure for table `taxonomies`
--

DROP TABLE IF EXISTS `taxonomies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxonomies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `taxonomy_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `taxonomies_taxonomy_type_foreign` (`taxonomy_type`),
  KEY `taxonomies_name_index` (`name`),
  CONSTRAINT `taxonomies_taxonomy_type_foreign` FOREIGN KEY (`taxonomy_type`) REFERENCES `taxonomies_types` (`type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxonomies`
--

/*!40000 ALTER TABLE `taxonomies` DISABLE KEYS */;
INSERT INTO `taxonomies` VALUES (1,'brand','Apple','2023-11-22 13:44:03','2023-11-22 13:44:03'),(2,'brand','Samsung','2023-11-22 13:44:03','2023-11-22 13:44:03'),(3,'brand','Huawei','2023-11-22 13:44:03','2023-11-22 13:44:03'),(4,'brand','Xiaomi','2023-11-22 13:44:03','2023-11-22 13:44:03'),(5,'brand','JBL','2023-11-22 13:44:03','2023-11-22 13:44:03'),(6,'category','Наушники','2023-11-22 13:44:03','2023-11-22 13:44:03'),(7,'type','Проводные','2023-11-22 13:44:03','2023-11-22 13:44:03'),(8,'type','Беспроводные','2023-11-22 13:44:03','2023-11-22 13:44:03'),(9,'product_status','Неактивен','2023-11-22 13:44:03','2023-11-22 13:44:03'),(10,'product_status','Активен','2023-11-22 13:44:03','2023-11-22 13:44:03'),(11,'product_status','На модерации','2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `taxonomies` ENABLE KEYS */;

--
-- Table structure for table `taxonomies_meta`
--

DROP TABLE IF EXISTS `taxonomies_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxonomies_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `taxonomy_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxonomy_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `taxonomies_meta_taxonomy_type_foreign` (`taxonomy_type`),
  KEY `taxonomies_meta_taxonomy_name_foreign` (`taxonomy_name`),
  CONSTRAINT `taxonomies_meta_taxonomy_name_foreign` FOREIGN KEY (`taxonomy_name`) REFERENCES `taxonomies` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `taxonomies_meta_taxonomy_type_foreign` FOREIGN KEY (`taxonomy_type`) REFERENCES `taxonomies_types` (`type`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxonomies_meta`
--

/*!40000 ALTER TABLE `taxonomies_meta` DISABLE KEYS */;
INSERT INTO `taxonomies_meta` VALUES (1,'brand','Apple','icon','<?xml version=\"1.0\" encoding=\"UTF-8\"?> <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"18\" height=\"22\" viewBox=\"0 0 18 22\" fill=\"none\">   <path d=\"M6.17311 22C3.09415 21.9811 0.547302 15.2972 0.547302 11.8933C0.547302 6.33297 4.46969 5.11566 5.98134 5.11566C6.66258 5.11566 7.39001 5.40016 8.03161 5.65191C8.48028 5.82752 8.94429 6.00881 9.20237 6.00881C9.35684 6.00881 9.72085 5.85459 10.0422 5.71929C10.7276 5.42912 11.5805 5.06848 12.5737 5.06848C12.5755 5.06848 12.5779 5.06848 12.5796 5.06848C13.3212 5.06848 15.5698 5.24156 16.9216 7.40048L17.2383 7.90652L16.7826 8.27223C16.1315 8.79465 14.9436 9.74761 14.9436 11.6353C14.9436 13.871 16.2889 14.7308 16.9352 15.1443C17.2206 15.3268 17.5159 15.515 17.5159 15.9267C17.5159 16.1955 15.4987 21.9666 12.5695 21.9666C11.8528 21.9666 11.3461 21.7375 10.8992 21.5355C10.4471 21.3309 10.057 21.1547 9.41243 21.1547C9.08571 21.1547 8.67257 21.319 8.23517 21.4933C7.63745 21.7306 6.9609 22 6.19323 22H6.17311Z\" fill=\"currentColor\"></path>   <path d=\"M12.884 0C12.9603 2.92514 10.9931 4.95447 9.02829 4.8272C8.70452 2.49283 10.9929 0 12.884 0Z\" fill=\"currentColor\"></path> </svg>','2023-11-22 13:44:03','2023-11-22 13:44:03'),(2,'brand','Samsung','icon','<?xml version=\"1.0\" encoding=\"UTF-8\"?> <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"39\" height=\"42\" viewBox=\"0 0 39 42\" fill=\"none\">   <g clip-path=\"url(#clip0_0_258)\">     <path d=\"M38.9761 17.3266C38.1717 12.3305 18.7648 13.1135 6.56314 18.6413L5.59347 19.0994C1.91079 20.9179 -0.253865 22.938 0.0238576 24.6731C0.833184 29.7043 20.4159 28.8571 32.5464 23.3084L33.9929 22.6017C37.3173 20.8619 39.2391 18.9679 38.9761 17.3266Z\" fill=\"#C4C4C4\"></path>     <path d=\"M2.02733 24.673C1.74961 22.9379 3.91426 20.9177 7.59694 19.0993L8.56661 18.6413C14.6287 15.8949 23.0727 14.1589 29.8509 14.0003C22.6081 13.8209 13.1024 15.6788 6.56314 18.6413L5.59347 19.0993C1.91079 20.9178 -0.253865 22.9379 0.0238576 24.673C0.390244 26.9507 4.86831 28.1465 11.1473 27.9994C5.9367 27.8701 2.35342 26.7002 2.02733 24.673Z\" fill=\"#C4C4C4\"></path>     <path d=\"M32.6455 20.8213V21.247C32.6455 21.3168 32.6981 21.3734 32.7629 21.3734H32.9881C33.0313 21.3734 33.0664 21.4112 33.0664 21.4578V22.0181C33.0684 22.0758 33.0652 22.1378 33.0565 22.1874C33.0391 22.3079 32.934 22.5117 32.6352 22.5117C32.3377 22.5117 32.2345 22.3079 32.2159 22.1874C32.2084 22.1378 32.2048 22.0759 32.2048 22.0181V19.983C32.2048 19.9104 32.2096 19.8322 32.2238 19.7733C32.2441 19.6647 32.333 19.4502 32.6329 19.4502C32.9475 19.4502 33.0257 19.6754 33.0431 19.7733C33.0554 19.8369 33.0566 19.9446 33.0566 19.9446V20.0769C33.0566 20.14 33.1041 20.1911 33.1627 20.1911H33.9591C34.0324 20.1911 34.0918 20.1271 34.0918 20.0482V20.045C34.0918 20.045 34.0961 19.8925 34.0839 19.7502C34.0065 18.9149 33.3709 18.6517 32.6413 18.6517C31.9112 18.6517 31.2887 18.9179 31.1983 19.7502C31.1903 19.8271 31.1776 19.9642 31.1776 20.045V21.9177C31.1776 21.9984 31.1796 22.0617 31.1935 22.2112C31.2581 22.9884 31.8576 23.2849 32.5464 23.3085L33.993 22.6018C34.0406 22.4881 34.0724 22.3582 34.0859 22.2112C34.097 22.0617 34.0981 21.9984 34.1009 21.9177V20.8264C34.1009 20.7698 34.0584 20.7239 34.0058 20.7239H32.7361C32.686 20.7238 32.6455 20.7675 32.6455 20.8213Z\" fill=\"currentColor\"></path>     <path d=\"M8.2767 21.6514C8.1041 20.7161 6.54372 20.4401 6.42664 19.9184C6.4076 19.8291 6.41194 19.7343 6.42268 19.6851C6.45162 19.5429 6.54014 19.3847 6.79844 19.3847C7.04005 19.3847 7.18173 19.5471 7.18173 19.7898V19.9677C7.18173 20.0214 7.22218 20.065 7.27215 20.065H8.07782C8.14934 20.065 8.20731 20.0026 8.20731 19.9256V19.7514C8.20731 18.7841 7.40111 18.6294 6.81908 18.6294C6.7314 18.6294 6.64571 18.6328 6.56314 18.6414L5.59347 19.0994C5.48752 19.237 5.41454 19.4071 5.38278 19.6186C5.35262 19.8185 5.34782 19.9937 5.39108 20.2189C5.56879 21.1286 7.03084 21.393 7.24351 21.9681C7.28319 22.0759 7.27047 22.2126 7.25105 22.2968C7.2173 22.4472 7.12445 22.5976 6.84955 22.5976C6.59323 22.5976 6.4389 22.436 6.4389 22.1929L6.43761 21.8532C6.43746 21.8041 6.40044 21.7644 6.35481 21.7644H5.44532C5.38408 21.7644 5.33441 21.8179 5.33441 21.8838V22.1049C5.33441 23.1 6.05614 23.3995 6.8286 23.3995C7.57211 23.3995 8.18271 23.1256 8.28188 22.3804C8.33147 21.9967 8.29415 21.7454 8.2767 21.6514Z\" fill=\"currentColor\"></path>     <path d=\"M11.2077 23.1311L10.6888 19.3141C10.6839 19.2778 10.6354 19.2777 10.6303 19.314L10.0969 23.144C10.0906 23.1897 10.0541 23.2234 10.0112 23.2234H9.05755C9.01482 23.2234 8.98252 23.1816 8.99021 23.1363L9.71369 18.9075C9.72725 18.8284 9.79139 18.7709 9.86611 18.7709H11.4567C11.5193 18.7709 11.573 18.819 11.5844 18.8854L12.308 23.1326C12.316 23.1798 12.2823 23.2235 12.2376 23.2235H11.3066C11.257 23.2233 11.2149 23.1841 11.2077 23.1311Z\" fill=\"currentColor\"></path>     <path d=\"M16.6844 23.0783L16.6587 19.3116C16.6585 19.2873 16.6258 19.2842 16.6218 19.3081L15.9656 23.1317C15.9564 23.1848 15.9135 23.2233 15.8634 23.2233H15.0359C14.9611 23.2233 14.897 23.1658 14.8833 23.0866L14.2394 19.33C14.2349 19.3036 14.1988 19.3071 14.1986 19.3339L14.1727 23.1351C14.1723 23.1839 14.1355 23.2233 14.0901 23.2233H13.2528C13.1932 23.2233 13.1451 23.1705 13.1464 23.1063L13.2311 18.9016C13.2325 18.8289 13.2877 18.7708 13.3553 18.7708H14.7958C14.8621 18.7708 14.9185 18.8226 14.929 18.8931L15.3894 21.9808C15.3968 22.0302 15.4627 22.0302 15.4701 21.9808L15.9319 18.8834C15.9416 18.8185 15.9935 18.7708 16.0546 18.7708H17.5353C17.5845 18.7708 17.6247 18.8132 17.6257 18.8662L17.713 23.0701C17.7148 23.1542 17.652 23.2234 17.5739 23.2234H16.8202C16.7456 23.2233 16.685 23.1586 16.6844 23.0783Z\" fill=\"currentColor\"></path>     <path d=\"M21.6377 22.3676C21.5405 23.1047 20.9338 23.3751 20.1986 23.3751C19.4321 23.3751 18.7199 23.079 18.7199 22.095V21.8479C18.7199 21.7966 18.7586 21.7549 18.8063 21.7549H19.7097C19.7661 21.7549 19.8118 21.8041 19.8118 21.8649V22.1822C19.8118 22.4223 19.9662 22.5817 20.2217 22.5817C20.4951 22.5817 20.5867 22.4334 20.6181 22.2856C20.6372 22.2035 20.6474 22.066 20.6106 21.9583C20.4011 21.3895 18.9538 21.1281 18.7776 20.2278C18.7319 20.0052 18.7387 19.8305 18.7673 19.6343C18.8764 18.9135 19.4675 18.6545 20.1912 18.6545C20.7673 18.6545 21.5612 18.8049 21.5612 19.765V19.9511C21.5612 20.0189 21.5102 20.0738 21.4472 20.0738H20.6524C20.5943 20.0738 20.5472 20.0232 20.5472 19.9606V19.8022C20.5472 19.5625 20.408 19.4018 20.1707 19.4018C19.916 19.4018 19.8275 19.5573 19.7973 19.6996C19.7878 19.7479 19.7834 19.8415 19.8012 19.9304C19.9182 20.4448 21.4605 20.7225 21.6303 21.6463C21.6487 21.7391 21.6837 21.9869 21.6377 22.3676Z\" fill=\"currentColor\"></path>     <path d=\"M25.4953 22.2569C25.4223 23.0887 24.8176 23.3584 24.0602 23.3584C23.3036 23.3584 22.6985 23.0887 22.6271 22.2569C22.6224 22.2124 22.6168 22.0449 22.6179 21.963V18.9418C22.6179 18.8474 22.689 18.7709 22.7767 18.7709H23.5136C23.593 18.7709 23.6575 18.8403 23.6575 18.9258V22.0651C23.6555 22.1219 23.6575 22.1873 23.6677 22.2343C23.6876 22.3446 23.7764 22.5573 24.0601 22.5573C24.347 22.5573 24.433 22.3446 24.4549 22.2343C24.4641 22.1873 24.466 22.1219 24.4641 22.0651V18.9074C24.4641 18.832 24.5208 18.7709 24.5908 18.7709H25.362C25.4401 18.7709 25.5035 18.8391 25.5035 18.9233V21.963C25.508 22.0449 25.5016 22.2124 25.4953 22.2569Z\" fill=\"currentColor\"></path>     <path d=\"M29.9685 23.1768H28.7299C28.6741 23.1768 28.6249 23.1375 28.6088 23.08L27.6642 19.7226C27.653 19.683 27.5987 19.6921 27.5994 19.7335L27.6518 23.0336C27.653 23.1123 27.5942 23.1768 27.5212 23.1768H26.7743C26.7028 23.1768 26.6448 23.1143 26.6448 23.0374V18.9189C26.6448 18.8371 26.7065 18.7708 26.7824 18.7708H28.0352C28.1155 18.7708 28.1862 18.8279 28.2086 18.9109L29.0935 22.1879C29.1006 22.2141 29.1365 22.2083 29.1361 22.1811L29.086 18.9223C29.0847 18.839 29.1471 18.7707 29.2244 18.7707H29.9949C30.0528 18.7707 30.0998 18.8213 30.0998 18.8836V23.0355C30.0995 23.1137 30.0409 23.1768 29.9685 23.1768Z\" fill=\"currentColor\"></path>   </g>   <defs>     <clipPath id=\"clip0_0_258\">       <rect width=\"39\" height=\"42\" fill=\"white\"></rect>     </clipPath>   </defs> </svg>','2023-11-22 13:44:03','2023-11-22 13:44:03'),(3,'brand','Huawei','icon','<?xml version=\"1.0\" encoding=\"UTF-8\"?> <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"28\" height=\"28\" viewBox=\"0 0 28 28\" fill=\"none\">   <path d=\"M4.36358 3.82202C4.32207 3.77056 4.24557 3.76706 4.19914 3.81415C3.76366 4.25586 1.8572 6.31588 2.01284 8.38C2.10406 10.0918 3.35919 11.1266 3.42914 11.1831C3.48383 11.2272 5.32679 13.1989 11.9384 16.4912C11.9555 16.4949 12.0178 16.5164 12.0542 16.4712C12.0542 16.4712 12.1055 16.4303 12.0787 16.3654C9.04034 9.72815 4.97028 4.57392 4.36358 3.82202Z\" fill=\"currentColor\"></path>   <path d=\"M11.2554 18.2477C11.2339 18.1741 11.144 18.1741 11.144 18.1741V18.1697L2.72032 18.4639C2.63599 18.4668 2.58623 18.5592 2.62932 18.6317C3.57213 20.2182 5.09638 21.4149 6.67898 21.0281C7.80997 20.7486 10.3702 18.9611 11.2149 18.3559H11.2127C11.2766 18.2944 11.2554 18.2477 11.2554 18.2477Z\" fill=\"currentColor\"></path>   <path d=\"M11.3534 17.3486C7.85422 14.9875 1.32033 11.4589 0.364012 10.9451C0.302707 10.9121 0.226035 10.9433 0.206403 11.01C-0.382964 13.0126 0.47716 14.6063 0.47716 14.6063C1.4104 16.5795 3.19179 17.1778 3.19179 17.1778C4.01757 17.5149 4.84061 17.5371 4.84061 17.5371C4.96864 17.5609 9.95853 17.5409 11.2964 17.5341C11.3534 17.5327 11.3828 17.4774 11.3828 17.4774C11.4262 17.4088 11.3534 17.3486 11.3534 17.3486Z\" fill=\"currentColor\"></path>   <path d=\"M10.5709 0.274017C8.28811 0.866555 7.0367 3.1818 7.76333 5.66062C7.76388 5.66242 7.76426 5.66406 7.7647 5.66592C8.52644 9.04112 12.2675 14.5848 13.0721 15.7478C13.1281 15.805 13.1738 15.7808 13.1738 15.7808C13.2599 15.7589 13.2532 15.6748 13.2532 15.6748L13.2551 15.6758C14.4023 4.20626 12.3094 0.543298 11.9944 0.0653851C11.9714 0.0304398 11.9307 0.0117367 11.8892 0.0169867C11.4746 0.0698694 10.5709 0.274017 10.5709 0.274017Z\" fill=\"currentColor\"></path>   <path d=\"M20.2504 2.93504C20.2498 2.9334 20.2493 2.93182 20.2488 2.93012C20.2279 2.85482 19.6438 0.822853 17.4033 0.27751C17.4033 0.27751 16.7882 0.12187 16.1169 0.0197141C15.8396 -0.0225046 13.5636 3.96251 14.7394 15.6853C14.7499 15.7621 14.8055 15.78 14.8055 15.78C14.8883 15.8133 14.9295 15.733 14.9295 15.733L14.9299 15.7342C15.7576 14.5394 19.4781 9.02208 20.2352 5.66318C20.2352 5.66312 20.6451 4.03743 20.2504 2.93504Z\" fill=\"currentColor\"></path>   <path d=\"M16.8425 18.1771C16.8425 18.1771 16.8369 18.1779 16.8283 18.1802C16.7639 18.197 16.726 18.2693 16.755 18.3292C16.7602 18.34 16.7677 18.3503 16.7781 18.3589V18.36C17.6013 18.9511 20.1002 20.6982 21.3035 21.0361C21.3035 21.0361 23.4558 21.7696 25.3643 18.6352C25.4083 18.5628 25.3585 18.4695 25.2738 18.4665L16.8432 18.1755L16.8425 18.1771Z\" fill=\"currentColor\"></path>   <path d=\"M27.7938 10.995C27.7735 10.9274 27.6973 10.8945 27.6352 10.928C26.6814 11.4427 20.1538 14.9827 16.654 17.3412V17.3427C16.5589 17.4064 16.6177 17.5327 16.6971 17.5327C18.0518 17.5357 23.1803 17.5424 23.3084 17.5202C23.3084 17.5202 23.9694 17.4904 24.789 17.1779C24.789 17.1779 26.6131 16.5981 27.5611 14.5262C27.5611 14.5262 28.3679 12.9119 27.7938 10.995Z\" fill=\"currentColor\"></path>   <path d=\"M25.9849 8.36872C26.1198 6.23433 24.2324 4.23966 23.7994 3.81173C23.7528 3.76569 23.6771 3.76973 23.636 3.8207C23.0338 4.56664 18.9729 9.70676 15.9361 16.3315H15.9376C15.889 16.436 15.9969 16.5275 16.0772 16.4812C22.4193 13.3249 24.2524 11.4437 24.5696 11.1833C24.6359 11.1289 25.9134 10.0588 25.9849 8.36872Z\" fill=\"currentColor\"></path>   <path d=\"M11.8361 15.8433C6.19166 12.8881 4.80046 11.3491 4.59358 11.1829C4.52364 11.1265 3.26851 10.0917 3.17729 8.37987C3.05993 6.82401 4.11414 5.27078 4.82693 4.41093C4.60993 4.12956 4.45081 3.93008 4.36358 3.82202C4.32207 3.77056 4.24557 3.76706 4.19914 3.81415C3.76366 4.25586 1.8572 6.31588 2.01284 8.38C2.10406 10.0918 3.35919 11.1266 3.42914 11.1831C3.48399 11.2274 5.32969 13.2004 11.9384 16.4912C11.9555 16.4949 12.0178 16.5164 12.0542 16.4712C12.0542 16.4712 12.1055 16.4303 12.0787 16.3654C11.9985 16.1902 11.9175 16.0164 11.8361 15.8433Z\" fill=\"currentColor\"></path>   <path d=\"M6.00496 17.5371C6.00496 17.5371 5.18192 17.5149 4.35614 17.1778C4.35614 17.1778 2.5748 16.5794 1.64156 14.6062C1.64156 14.6062 0.901096 13.2324 1.26635 11.4334C0.837714 11.2001 0.523207 11.0306 0.364012 10.9451C0.302707 10.9121 0.226035 10.9433 0.206403 11.01C-0.382964 13.0126 0.47716 14.6063 0.47716 14.6063C1.4104 16.5795 3.19179 17.1778 3.19179 17.1778C4.01757 17.5149 4.84061 17.5371 4.84061 17.5371C4.89596 17.5474 5.86108 17.5494 7.0467 17.548C6.44788 17.5469 6.0404 17.5436 6.00496 17.5371Z\" fill=\"currentColor\"></path>   <path d=\"M13.3675 14.4527C11.9592 12.2982 9.52683 8.31458 8.9291 5.66596C8.92866 5.6641 8.92828 5.66251 8.92773 5.66065C8.2011 3.18184 9.45251 0.866536 11.7353 0.273998C11.7353 0.273998 11.8784 0.241678 12.0766 0.199459C12.0427 0.14067 12.0148 0.0962834 11.9944 0.0653851C11.9714 0.0304398 11.9307 0.0117367 11.8892 0.0169867C11.4746 0.0698694 10.5709 0.274017 10.5709 0.274017C8.28811 0.866555 7.0367 3.1818 7.76333 5.66062C7.76388 5.66242 7.76426 5.66406 7.7647 5.66592C8.52644 9.04112 12.2675 14.5848 13.0721 15.7478C13.1281 15.805 13.1738 15.7808 13.1738 15.7808C13.2599 15.7589 13.2532 15.6748 13.2532 15.6748L13.2551 15.6758C13.2969 15.2574 13.334 14.8506 13.3675 14.4527Z\" fill=\"currentColor\"></path>   <path d=\"M17.0914 0.204374C16.8489 0.150397 16.4917 0.0766984 16.1169 0.0197141C15.8396 -0.0225046 13.5636 3.96251 14.7394 15.6853C14.7499 15.7621 14.8055 15.78 14.8055 15.78C14.8883 15.8133 14.9295 15.733 14.9295 15.733L14.9299 15.7342C15.1054 15.481 15.4111 15.033 15.7907 14.4531C15.0076 5.09436 16.5088 1.07133 17.0914 0.204374Z\" fill=\"currentColor\"></path>   <path d=\"M17.992 18.2152L16.8432 18.1755L16.8425 18.1771C16.8425 18.1771 16.8369 18.1779 16.8283 18.1802C16.7639 18.197 16.726 18.2693 16.755 18.3292C16.7602 18.34 16.7677 18.3503 16.7781 18.3589V18.36C17.6013 18.9511 20.1002 20.6982 21.3035 21.0361C21.3035 21.0361 21.8611 21.2258 22.66 20.9993C21.3667 20.5814 18.888 18.8567 17.992 18.2152Z\" fill=\"currentColor\"></path>   <path d=\"M27.9745 11.9147C27.9452 11.6202 27.8887 11.312 27.7938 10.995C27.7735 10.9274 27.6973 10.8945 27.6352 10.928C26.6814 11.4427 20.1538 14.9827 16.654 17.3412V17.3427C16.5589 17.4064 16.6177 17.5327 16.6971 17.5327C17.0537 17.5335 17.672 17.5345 18.3916 17.5352C21.2604 15.6616 25.5201 13.2672 27.9745 11.9147Z\" fill=\"currentColor\"></path>   <path d=\"M24.3418 4.40332C24.1064 4.12321 23.9075 3.91859 23.7994 3.81173C23.7528 3.76569 23.6771 3.76973 23.636 3.8207C23.0338 4.56664 18.9729 9.70676 15.9361 16.3315H15.9376C15.889 16.436 15.9969 16.5275 16.0772 16.4812C16.515 16.2633 16.9305 16.0518 17.3263 15.8462C19.8933 10.3949 23.0928 6.02179 24.3418 4.40332Z\" fill=\"currentColor\"></path>   <path d=\"M3.2559 25.2545H1.0794C1.04954 25.2545 1.02536 25.2303 1.02536 25.2005V23.4843C1.02536 23.4545 1.00119 23.4303 0.971333 23.4303H0.0540616C0.0242023 23.4303 3.0485e-05 23.4545 3.0485e-05 23.4843V27.8831C3.0485e-05 27.9129 0.0242023 27.9371 0.0540616 27.9371H0.971279C1.00114 27.9371 1.02531 27.9129 1.02531 27.8831V26.1104C1.02531 26.0806 1.04948 26.0564 1.07934 26.0564H3.25584C3.2857 26.0564 3.30988 26.0806 3.30988 26.1104V27.8831C3.30988 27.9129 3.33405 27.9371 3.36391 27.9371H4.28413C4.31399 27.9371 4.33816 27.9129 4.33816 27.8831V23.4843C4.33816 23.4545 4.31399 23.4303 4.28413 23.4303H3.36396C3.3341 23.4303 3.30993 23.4545 3.30993 23.4843V25.2005C3.30993 25.2303 3.28576 25.2545 3.2559 25.2545Z\" fill=\"currentColor\"></path>   <path d=\"M8.39227 26.1304C8.39227 26.9257 8.1488 27.2568 7.29923 27.2568C6.44994 27.2568 6.19892 26.9257 6.19892 26.1304V23.4837C6.19892 23.4539 6.17475 23.4297 6.14489 23.4297H5.23435C5.20449 23.4297 5.18032 23.4539 5.18032 23.4837V26.1535C5.18032 26.7251 5.23369 27.1217 5.45797 27.3636C5.85669 27.8062 6.44994 27.9843 7.29929 27.9843C8.14886 27.9843 8.73084 27.8062 9.13257 27.3636C9.35089 27.1269 9.41903 26.7297 9.41903 26.1535V23.4837C9.41903 23.4539 9.39486 23.4297 9.365 23.4297H8.44636C8.4165 23.4297 8.39233 23.4539 8.39233 23.4837L8.39227 26.1304Z\" fill=\"currentColor\"></path>   <path d=\"M11.6699 23.4608L9.65389 27.8596C9.63748 27.8954 9.66362 27.9361 9.703 27.9361H10.6689C10.691 27.9361 10.7108 27.9228 10.719 27.9024L11.1263 26.897C11.1346 26.8766 11.1544 26.8633 11.1764 26.8633H13.2224C13.2442 26.8633 13.2639 26.8763 13.2723 26.8965L13.6938 27.903C13.7022 27.9231 13.7218 27.9362 13.7436 27.9362H14.7659C14.8053 27.9362 14.8314 27.8953 14.8149 27.8595L12.7865 23.4608C12.7777 23.4417 12.7585 23.4294 12.7374 23.4294H11.7191C11.6979 23.4293 11.6787 23.4416 11.6699 23.4608ZM11.5036 25.9853L12.1379 24.4487C12.1562 24.4045 12.2187 24.404 12.2375 24.4481L12.8943 25.9847C12.9096 26.0203 12.8834 26.06 12.8446 26.06H11.5535C11.5151 26.06 11.4889 26.0209 11.5036 25.9853Z\" fill=\"currentColor\"></path>   <path d=\"M19.6454 26.7281L18.6169 23.4671C18.6098 23.4446 18.589 23.4293 18.5654 23.4293H17.436C17.4117 23.4293 17.3904 23.4456 17.3839 23.4691L16.492 26.7207C16.4778 26.7724 16.4051 26.7741 16.3885 26.7231L15.3273 23.4666C15.32 23.4444 15.2993 23.4293 15.2759 23.4293H14.2909C14.2535 23.4293 14.2274 23.4665 14.2401 23.5017L15.8264 27.9005C15.8341 27.9219 15.8545 27.9362 15.8773 27.9362H17.0475C17.072 27.9362 17.0933 27.9198 17.0997 27.8962L17.9497 24.7516C17.9638 24.6993 18.0375 24.698 18.0535 24.7497L19.0262 27.8981C19.0332 27.9207 19.0541 27.9362 19.0778 27.9362H20.2576C20.2808 27.9362 20.3015 27.9213 20.3088 27.8993L21.7787 23.5006C21.7904 23.4656 21.7644 23.4294 21.7274 23.4294H20.7917C20.7679 23.4294 20.7469 23.445 20.74 23.4678L19.7487 26.7276C19.7332 26.7783 19.6614 26.7787 19.6454 26.7281Z\" fill=\"currentColor\"></path>   <path d=\"M22.0234 25.7651C22.0234 26.5811 22.2677 27.1721 22.7703 27.5404C23.2455 27.8908 23.701 27.9361 24.2917 27.9361H25.9699C25.9997 27.9361 26.0239 27.912 26.0239 27.8821V27.2061C26.0239 27.1763 25.9997 27.1521 25.9699 27.1521H24.34C23.5516 27.1521 23.2017 26.8688 23.1795 26.1029C23.1786 26.0726 23.2034 26.0474 23.2337 26.0474H25.9699C25.9997 26.0474 26.0239 26.0232 26.0239 25.9933V25.3151C26.0239 25.2853 25.9997 25.2611 25.9699 25.2611H23.2438C23.2123 25.2611 23.1869 25.234 23.1897 25.2026C23.2488 24.5355 23.6197 24.2156 24.34 24.2156H25.9699C25.9997 24.2156 26.0239 24.1914 26.0239 24.1616V23.4834C26.0239 23.4535 25.9997 23.4294 25.9699 23.4294H24.3189C22.7499 23.4293 22.0234 24.1726 22.0234 25.7651Z\" fill=\"currentColor\"></path>   <path d=\"M26.9289 23.4834V27.8818C26.9289 27.9116 26.9531 27.9358 26.9829 27.9358H27.9459C27.9758 27.9358 28 27.9116 28 27.8818V23.4834C28 23.4535 27.9758 23.4293 27.9459 23.4293H26.9829C26.9531 23.4293 26.9289 23.4535 26.9289 23.4834Z\" fill=\"currentColor\"></path>   <path d=\"M3.90308 18.8092C3.90018 18.8047 3.85069 18.7395 3.80694 18.6534C3.80262 18.6461 3.79813 18.6389 3.79381 18.6317C3.78731 18.6208 3.78353 18.6094 3.78107 18.5979C3.75788 18.5431 3.74055 18.4839 3.73924 18.4283L2.72032 18.4639C2.63599 18.4668 2.58623 18.5592 2.62932 18.6317C3.56666 20.2089 5.0788 21.4003 6.65145 21.0341C5.57777 20.7908 4.60013 19.9088 3.90308 18.8092Z\" fill=\"currentColor\"></path> </svg>','2023-11-22 13:44:03','2023-11-22 13:44:03'),(4,'brand','Xiaomi','icon','<?xml version=\"1.0\" encoding=\"UTF-8\"?> <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"35\" height=\"35\" viewBox=\"0 0 35 35\" fill=\"none\">   <path d=\"M17.05 9H6V28H9.4V12.4545H17.05C18.4559 12.4545 19.6 13.617 19.6 15.0455V28H23V15.0455C23 11.7118 20.331 9 17.05 9Z\" fill=\"currentColor\"></path>   <path d=\"M16 16H13V28H16V16Z\" fill=\"currentColor\"></path>   <path d=\"M30 9H27V28H30V9Z\" fill=\"currentColor\"></path> </svg>','2023-11-22 13:44:03','2023-11-22 13:44:03'),(5,'brand','JBL','icon','<?xml version=\"1.0\" encoding=\"UTF-8\"?> <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" width=\"800px\" height=\"800px\" viewBox=\"0 0 24 24\" role=\"img\">   <path d=\"m0 5.264 2.022 4.59 2.022-4.59zm2.022 7.601c.698 0 1.266-.565 1.266-1.26 0-.699-.568-1.262-1.266-1.262a1.262 1.262 0 1 0 0 2.523zM.928 16.227c0 .957.862 2.509 3.315 2.509s3.315-1.188 3.315-2.51V5.266H5.369l.001 11.342c0 .62-.503 1.14-1.126 1.14a1.127 1.127 0 0 1-1.128-1.124l-.001-2.311H.928zm8.289 2.311V5.264h4.374c.845 0 2.187.693 2.187 2.163v2.261c0 .662-.58 1.833-1.44 1.833.86 0 1.44.742 1.44 1.305v3.979c0 .676-.546 1.733-2.187 1.733zm3.38-7.559c.796 0 .995-.134.995-2.214s-.2-2.246-.995-2.246h-1.195v4.457zm.995 3.811c0-2.081 0-2.69-.864-2.69h-1.326v5.348l1.326.003c.863 0 .863-.581.863-2.66zm3.779 3.748H24v-4.226h-2.189l.002 2.31a1.126 1.126 0 0 1-2.255 0V5.265H17.37z\"></path> </svg>','2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `taxonomies_meta` ENABLE KEYS */;

--
-- Table structure for table `taxonomies_types`
--

DROP TABLE IF EXISTS `taxonomies_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxonomies_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `taxonomies_types_type_index` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxonomies_types`
--

/*!40000 ALTER TABLE `taxonomies_types` DISABLE KEYS */;
INSERT INTO `taxonomies_types` VALUES (1,'brand','Бренд','2023-11-22 13:44:03','2023-11-22 13:44:03'),(2,'category','Категория','2023-11-22 13:44:03','2023-11-22 13:44:03'),(3,'type','Тип','2023-11-22 13:44:03','2023-11-22 13:44:03'),(4,'product_status','Статус товара','2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `taxonomies_types` ENABLE KEYS */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patronymic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `house` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint unsigned NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_phone_number_unique` (`phone_number`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'kotbarsik96@gmail.com','2023-11-22 13:44:03','$2y$10$BeKb50td8uP0up5haFDeyuW7pxHarmjaus4I.MU3G3ugpS0Bhl8ve','Алексей','Иванов','Петрович',NULL,'Москва','Тургенева','67',1,'AzHoWhwMntwqOKORwEID1iF6ftkETmL4keLIQ9UET53pwNR44YhbTUx9sq5b','2023-11-22 13:44:03','2023-11-22 13:44:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

--
-- Table structure for table `variation_values`
--

DROP TABLE IF EXISTS `variation_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `variation_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variation_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `variation_values_variation_id_foreign` (`variation_id`),
  CONSTRAINT `variation_values_variation_id_foreign` FOREIGN KEY (`variation_id`) REFERENCES `variations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variation_values`
--

/*!40000 ALTER TABLE `variation_values` DISABLE KEYS */;
INSERT INTO `variation_values` VALUES (1,'Черный',1,'2023-11-22 18:58:07','2023-11-22 18:58:07'),(2,'Есть',2,'2023-11-22 18:58:07','2023-11-22 18:58:07'),(3,'Нет',2,'2023-11-22 18:58:07','2023-11-22 18:58:07'),(4,'Белый',3,'2023-11-22 19:02:35','2023-11-22 19:02:35'),(5,'Есть',4,'2023-11-22 19:02:35','2023-11-22 19:02:35'),(6,'Нет',4,'2023-11-22 19:02:35','2023-11-22 19:02:35'),(7,'Зеленый',5,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(8,'Синий',5,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(9,'Розовый',5,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(10,'Серый',5,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(11,'Черный',5,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(12,'Есть',6,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(13,'Нет',6,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(14,'Белый',7,'2023-11-22 19:09:34','2023-11-22 19:09:34'),(15,'Есть',8,'2023-11-22 19:09:34','2023-11-22 19:09:34'),(16,'Нет',8,'2023-11-22 19:09:34','2023-11-22 19:09:34'),(17,'Белый',9,'2023-11-22 19:13:11','2023-11-22 19:13:11'),(18,'Черный',9,'2023-11-22 19:13:11','2023-11-22 19:13:11'),(19,'Серебристый',9,'2023-11-22 19:13:11','2023-11-22 19:13:11'),(20,'Розовый',9,'2023-11-22 19:13:11','2023-11-22 19:13:11'),(21,'Белый',10,'2023-11-22 19:15:53','2023-11-22 19:15:53'),(22,'Черный',10,'2023-11-22 19:15:53','2023-11-22 19:15:53'),(23,'Светло-коричневый',10,'2023-11-22 19:15:53','2023-11-22 19:15:53'),(24,'Черный',11,'2023-11-22 19:18:18','2023-11-22 19:18:18'),(25,'Есть',12,'2023-11-22 19:18:18','2023-11-22 19:18:18'),(26,'Нет',12,'2023-11-22 19:18:18','2023-11-22 19:18:18'),(27,'Серый',13,'2023-11-22 20:50:22','2023-11-22 20:50:22'),(28,'Черный',13,'2023-11-22 20:50:22','2023-11-22 20:50:22'),(29,'Красный',13,'2023-11-22 20:50:22','2023-11-22 20:50:22'),(30,'Белый',14,'2023-11-22 21:12:29','2023-11-22 21:12:29'),(31,'Голубой',14,'2023-11-22 21:12:29','2023-11-22 21:12:29'),(32,'Серый',14,'2023-11-22 21:12:29','2023-11-22 21:12:29'),(33,'Белый',15,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(34,'Голубой',15,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(35,'Розовый',15,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(36,'Серый',15,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(37,'Черный',15,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(38,'Оранжевый',15,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(39,'Бело-красный',16,'2023-11-22 21:19:24','2023-11-22 21:19:24'),(40,'Зеленый',16,'2023-11-22 21:19:24','2023-11-22 21:19:24'),(41,'Синий',16,'2023-11-22 21:19:24','2023-11-22 21:19:24'),(42,'Черный',16,'2023-11-22 21:19:24','2023-11-22 21:19:24'),(43,'Синий',17,'2023-11-22 21:21:54','2023-11-22 21:21:54'),(44,'Розовый',17,'2023-11-22 21:21:54','2023-11-22 21:21:54'),(45,'Белый',17,'2023-11-22 21:21:54','2023-11-22 21:21:54'),(46,'Черный',17,'2023-11-22 21:21:54','2023-11-22 21:21:54'),(47,'Черный',18,'2023-11-22 21:27:44','2023-11-22 21:27:44'),(48,'Серый',18,'2023-11-22 21:27:44','2023-11-22 21:27:44');
/*!40000 ALTER TABLE `variation_values` ENABLE KEYS */;

--
-- Table structure for table `variations`
--

DROP TABLE IF EXISTS `variations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `variations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `variations_product_id_foreign` (`product_id`),
  CONSTRAINT `variations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variations`
--

/*!40000 ALTER TABLE `variations` DISABLE KEYS */;
INSERT INTO `variations` VALUES (1,'Цвет',1,'2023-11-22 18:58:07','2023-11-22 18:58:07'),(2,'Наличие кейса',1,'2023-11-22 18:58:07','2023-11-22 18:58:07'),(3,'Цвет',2,'2023-11-22 19:02:35','2023-11-22 19:02:35'),(4,'Наличие кейса',2,'2023-11-22 19:02:35','2023-11-22 19:02:35'),(5,'Цвет',3,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(6,'Наличие чехла',3,'2023-11-22 19:04:02','2023-11-22 19:04:02'),(7,'Цвет',4,'2023-11-22 19:09:34','2023-11-22 19:09:34'),(8,'Наличие кейса',4,'2023-11-22 19:09:34','2023-11-22 19:09:34'),(9,'Цвет',5,'2023-11-22 19:13:11','2023-11-22 19:13:11'),(10,'Цвет',6,'2023-11-22 19:15:53','2023-11-22 19:15:53'),(11,'Цвет',7,'2023-11-22 19:18:18','2023-11-22 19:18:18'),(12,'Наличие кейса',7,'2023-11-22 19:18:18','2023-11-22 19:18:18'),(13,'Цвет',8,'2023-11-22 20:50:22','2023-11-22 20:50:22'),(14,'Цвет',9,'2023-11-22 21:12:29','2023-11-22 21:12:29'),(15,'Цвет',10,'2023-11-22 21:15:04','2023-11-22 21:15:04'),(16,'Цвет',11,'2023-11-22 21:19:24','2023-11-22 21:19:24'),(17,'Цвет',12,'2023-11-22 21:21:54','2023-11-22 21:21:54'),(18,'Цвет',15,'2023-11-22 21:27:44','2023-11-22 21:27:44');
/*!40000 ALTER TABLE `variations` ENABLE KEYS */;

--
-- Table structure for table `verify_email`
--

DROP TABLE IF EXISTS `verify_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `verify_email` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verify_email_user_id_foreign` (`user_id`),
  CONSTRAINT `verify_email_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verify_email`
--

/*!40000 ALTER TABLE `verify_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `verify_email` ENABLE KEYS */;

--
-- Dumping routines for database 'audiofree'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-03 17:39:07
