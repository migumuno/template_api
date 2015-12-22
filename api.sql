CREATE TABLE `api_user` (
  `id_api_user` INT NOT NULL AUTO_INCREMENT,
  `user` VARCHAR(255) NOT NULL,
  `apikey` VARCHAR(100) NOT NULL,
  `enabled` BIT(1) NOT NULL DEFAULT 0,
  `fh_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_api_user`));
  
INSERT INTO `api_user` (`id_api_user`, `user`, `apikey`, `enabled`) VALUES ('1', 'App', 'A9EFB6F426CE7', 1);
  