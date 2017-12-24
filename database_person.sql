create database crud;
use crud;
CREATE TABLE `persons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- example data persons
INSERT INTO `persons` (`id`, `firstName`, `lastName`, `gender`, `address`, `dob`)
VALUES
    (1, 'Airi', 'Satou', 'female', 'Tokyo', '1964-03-04'),
    (2, 'Garrett', 'Winters', 'male', 'Tokyo', '1988-09-02'),
    (3, 'John', 'Doe', 'male', 'Kansas', '1972-11-02'),
    (4, 'Tatyana', 'Fitzpatrick', 'male', 'London', '1989-01-01'),
    (5, 'Quinn', 'Flynn', 'male', 'Edinburgh', '1977-03-24');