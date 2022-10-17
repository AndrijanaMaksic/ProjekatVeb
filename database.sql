CREATE DATABASE IF NOT EXISTS `jewelry`;
USE `jewelry`;

CREATE TABLE IF NOT EXISTS `user_type`(
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY(`id`)
)

CREATE TABLE IF NOT EXISTS `user` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(45) NOT NULL,
    `password` VARCHAR(45) NOT NULL,
    `type_id` INT NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_type`
      FOREIGN KEY (`type_id`)
      REFERENCES `user_type`(`id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `jewelry_brand` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `brand` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
)ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `jewelry_gender`(
    `id` INT NOT NULL AUTO_INCREMENT,
    `pol` VARCHAR(45) NOT NULL,
    PRIMARY KEY(`id`)
)ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `jewelry_color` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `color` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
)ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `jewelry_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`)
)ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `jewelry` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `model` VARCHAR(45) NOT NULL,
  `price` DECIMAL NOT NULL,
  `brand_id` INT NOT NULL,
  `gender_id` INT NOT NULL,
  `color_id` INT NOT NULL,
  `type_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_color`
    FOREIGN KEY (`color_id`)
    REFERENCES `jewelry_color`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_gender`
    FOREIGN KEY (`gender_id`)
    REFERENCES `jewelry_gender`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_brand`
    FOREIGN KEY (`brand_id`)
    REFERENCES `jewelry_brand`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_type1`
    FOREIGN KEY (`type_id`)
    REFERENCES `jewelry_type`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `order` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_id` INT NOT NULL,
  `jewelry_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `price` DOUBLE NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_customer1`
    FOREIGN KEY (`customer_id`)
    REFERENCES `user`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_jewelry`
    FOREIGN KEY (`jewelry_id`)
    REFERENCES `jewelry`(`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment` TEXT(1000) NOT NULL,
  `customer_id` INT NOT NULL,
  `created` DATE NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_comment`
    FOREIGN KEY (`customer_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
)ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `rating` (
  `customer_id` INT NOT NULL,
  `rating` INT NOT NULL,
  PRIMARY KEY (`customer_id`),
  CONSTRAINT `fk_rating`
    FOREIGN KEY (`customer_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

INSERT INTO `user_type`(`id`,`type`) VALUES
(1, 'admin'),
(2, 'customer'),
(3, 'employee');

INSERT INTO `user` (`id`, `email`, `password`, `type_id`) VALUES
(1, 'jovan@gmail.com', '123456', 1),
(2, 'stefan@gmail.com', '123456', 2),
(3, 'milica@gmail.com', '123456', 2),
(4, 'sanja@gmail.com', '123456', 2),
(5, 'milos@gmail.com', '123456', 3),
(6, 'milan@gmail.com', '123456', 3);

INSERT INTO `jewelry_brand` (`id`, `brand`) VALUES
(1, 'Police Jewellery'),
(2, 'ROSEFIELD Jewellery'),
(3, 'ESPIRIT Jewellery'),
(4, 'GUESS Jewellery'),
(5, 'BROSWAY');

INSERT INTO `jewelry_color` (`id`, `color`) VALUES
(1, 'Antique yellow gold'),
(2, 'Antique silver'),
(3, 'Black'),
(4, 'Grey'),
(5, 'Silver / brass'),
(6, 'Silver / stainless steel');

INSERT INTO `jewelry_gender` (`id`, `pol`) VALUES
(1, 'Unisex'),
(2, 'Male'),
(3, 'Female');

INSERT INTO `jewelry_image` (`id`, `src`) VALUES
(1, '1.jpg'),
(2, '2.jpg'),
(3, '3.jpg'),
(4, '4.jpg');

INSERT INTO `jewelry_type` (`id`, `type`) VALUES
(1, 'Bracelets'),
(2, 'Necklaces'),
(3, 'Earrings'),
(4, 'Rings'),
(5, ' Pendants'),
(6, 'Cuffs');

INSERT INTO `jewelry` (`id`, `model`, `price`, `brand_id`, `gender_id`, `color_id`, `image_id`, `type_id`) VALUES
(1, 'AYIA NAPA', '6200', 4, 2, 6, 1, 2),
(2, 'FINE HEART', '5500', 4, 3, 1, 2, 3),
(3, 'Rosefield', '3690', 2, 3, 2, 3, 1);

INSERT INTO `order` (`id`, `customer_id`, `jewelry_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 6200),
(2, 2, 3, 1, 3690),
(3, 3, 2, 1, 5500);

INSERT INTO `comment` (`id`, `comment`, `customer_id`, `created`) VALUES
(1, 'This is great.', 1, '2022-03-18'),
(2, 'Your collection is getting more beautiful every year.', 3, '2022-03-01');

INSERT INTO `rating` (`customer_id`, `rating`) VALUES
(1, 4),
(2, 3),
(3, 5);
 