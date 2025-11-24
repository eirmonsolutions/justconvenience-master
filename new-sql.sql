ALTER TABLE `oauth_clients`
ADD COLUMN `device_platform` TINYINT NULL AFTER `api_token`,
ADD COLUMN `device_token` VARCHAR(255) NULL AFTER `device_platform`;


ALTER TABLE `orders`
ADD COLUMN `amount` DECIMAL(10,2) NOT NULL AFTER `store_id`,
ADD COLUMN `customerName` VARCHAR(255) NOT NULL AFTER `amount`,
ADD COLUMN `customerEmail` VARCHAR(255) NOT NULL AFTER `customerName`,
ADD COLUMN `customerAddress` VARCHAR(255) NOT NULL AFTER `customerEmail`,
ADD COLUMN `orderRef` VARCHAR(100) NOT NULL AFTER `customerAddress`;
