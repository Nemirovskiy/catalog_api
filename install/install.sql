DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`
(
    `id`     INT  NOT NULL AUTO_INCREMENT,
    `title`  TEXT NOT NULL,
    `parent_id` INT  NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`
(
    `id`                INT          NOT NULL AUTO_INCREMENT,
    `title`             VARCHAR(240) NOT NULL,
    `short_description` VARCHAR(240) NULL,
    `image_url`         VARCHAR(240) NULL,
    `amount`            INT          NULL,
    `price`             DOUBLE       NOT NULL,
    `producer`          VARCHAR(240) NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS `link`;
CREATE TABLE `link`
(
    `product_id`  INT NOT NULL,
    `category_id` INT NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
ALTER TABLE `link` ADD UNIQUE KEY `product_id` (`product_id`,`category_id`);