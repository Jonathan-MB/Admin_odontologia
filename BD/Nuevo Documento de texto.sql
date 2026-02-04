-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`Rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Rol` (
  `rol_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`rol_id`),
  UNIQUE INDEX `rol_id_UNIQUE` (`rol_id` ASC) VISIBLE);


-- -----------------------------------------------------
-- Table `mydb`.`Ususario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Ususario` (
  `usuario_id` INT NOT NULL AUTO_INCREMENT,
  `nombre_usuario` VARCHAR(32) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `Rol_rol_id` INT NOT NULL,
  `create_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`usuario_id`),
  UNIQUE INDEX `user_id_UNIQUE` (`usuario_id` ASC) VISIBLE,
  INDEX `fk_user_Rol_idx` (`Rol_rol_id` ASC) VISIBLE,
  CONSTRAINT `fk_user_Rol`
    FOREIGN KEY (`Rol_rol_id`)
    REFERENCES `mydb`.`Rol` (`rol_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `mydb`.`Tipo_documento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Tipo_documento` (
  `tipo_documento_id` INT NOT NULL AUTO_INCREMENT,
  `name_tipo_documento` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`tipo_documento_id`),
  UNIQUE INDEX `tipo_documento_id_UNIQUE` (`tipo_documento_id` ASC) VISIBLE);


-- -----------------------------------------------------
-- Table `mydb`.`Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Cliente` (
  `cliente_id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `primer_apellido` VARCHAR(255) NOT NULL,
  `segundo_apellido` VARCHAR(45) NOT NULL,
  `tipo_documento_tipo_documento_id` INT NOT NULL,
  `numero_documento` VARCHAR(45) NOT NULL,
  `direccion` VARCHAR(45) NOT NULL,
  `telefono` VARCHAR(45) NOT NULL,
  `eps` VARCHAR(45) NOT NULL,
  `fecha_nacimiento` DATE NOT NULL,
  PRIMARY KEY (`cliente_id`),
  UNIQUE INDEX `cliente_id_UNIQUE` (`cliente_id` ASC) VISIBLE,
  INDEX `fk_Cliente_Tipo_documento1_idx` (`tipo_documento_tipo_documento_id` ASC) VISIBLE,
  CONSTRAINT `fk_Cliente_Tipo_documento1`
    FOREIGN KEY (`tipo_documento_tipo_documento_id`)
    REFERENCES `mydb`.`Tipo_documento` (`tipo_documento_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `mydb`.`Dientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Dientes` (
  `diente_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`diente_id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE,
  UNIQUE INDEX `diente_id_UNIQUE` (`diente_id` ASC) VISIBLE);


-- -----------------------------------------------------
-- Table `mydb`.`Especialista`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Especialista` (
  `especialista_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`especialista_id`));


-- -----------------------------------------------------
-- Table `mydb`.`Historia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Historia` (
  `historia_id` INT NOT NULL,
  `Dientes_diente_id` INT NOT NULL,
  `Cliente_cliente_id` INT NOT NULL,
  `Especialista_especialista_id` INT NOT NULL,
  `Observacion` VARCHAR(5000) NOT NULL,
  PRIMARY KEY (`historia_id`),
  INDEX `fk_Historia_Dientes1_idx` (`Dientes_diente_id` ASC) VISIBLE,
  INDEX `fk_Historia_Cliente1_idx` (`Cliente_cliente_id` ASC) VISIBLE,
  INDEX `fk_Historia_Especialista1_idx` (`Especialista_especialista_id` ASC) VISIBLE,
  CONSTRAINT `fk_Historia_Dientes1`
    FOREIGN KEY (`Dientes_diente_id`)
    REFERENCES `mydb`.`Dientes` (`diente_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Historia_Cliente1`
    FOREIGN KEY (`Cliente_cliente_id`)
    REFERENCES `mydb`.`Cliente` (`cliente_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Historia_Especialista1`
    FOREIGN KEY (`Especialista_especialista_id`)
    REFERENCES `mydb`.`Especialista` (`especialista_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `mydb`.`Facturas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`Facturas` (
  `historia_id` INT NOT NULL,
  `Cliente_cliente_id` INT NOT NULL,
  `Observacion` VARCHAR(5000) NOT NULL,
  `Especialista_especialista_id` INT NOT NULL,
  `abono` INT NOT NULL DEFAULT 0,
  `cita` DATE NULL,
  `saldo` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`historia_id`),
  INDEX `fk_Historia_Cliente1_idx` (`Cliente_cliente_id` ASC) VISIBLE,
  INDEX `fk_Historia_Especialista1_idx` (`Especialista_especialista_id` ASC) VISIBLE,
  CONSTRAINT `fk_Historia_Cliente10`
    FOREIGN KEY (`Cliente_cliente_id`)
    REFERENCES `mydb`.`Cliente` (`cliente_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Historia_Especialista10`
    FOREIGN KEY (`Especialista_especialista_id`)
    REFERENCES `mydb`.`Especialista` (`especialista_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
