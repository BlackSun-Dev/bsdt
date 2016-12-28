CREATE TABLE IF NOT EXISTS `changesbasic` (
  `IndexNo` int(11) NOT NULL AUTO_INCREMENT,
  `years` mediumint(4) NOT NULL,
  `days` mediumint(4) NOT NULL,
  `hours` mediumint(4) NOT NULL,
  `minutes` mediumint(4) NOT NULL,
  `seconds` mediumint(4) NOT NULL,
  `scannedBy` varchar(50) NOT NULL,
  `system` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `typeName` varchar(50) NOT NULL,
  `entityID` mediumint(9) NOT NULL,
  `entityTypeName` varchar(50) NOT NULL,
  `hull` smallint(6) NOT NULL,
  `hullMax` smallint(6) NOT NULL,
  `shield` smallint(6) NOT NULL,
  `shieldMax` smallint(6) NOT NULL,
  `ionic` smallint(6) NOT NULL,
  `ionicMax` smallint(6) NOT NULL,
  `underConstruction` varchar(3) NOT NULL,
  `sharingSensors` varchar(3) NOT NULL,
  `x` tinyint(4) NOT NULL,
  `y` tinyint(4) NOT NULL,
  `travelDirection` varchar(5) NOT NULL,
  `travelDirDescription` varchar(5) NOT NULL,
  `ownerName` varchar(50) NOT NULL,
  `iffStatus` varchar(7) NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`IndexNo`),
  KEY `IndexNo` (`IndexNo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `changesfocus` (
  `years` mediumint(4) NOT NULL,
  `days` mediumint(4) NOT NULL,
  `hours` mediumint(4) NOT NULL,
  `minutes` mediumint(4) NOT NULL,
  `seconds` mediumint(4) NOT NULL,
  `scannedBy` varchar(50) NOT NULL,
  `entityID` mediumint(9) NOT NULL,
  `hyperspeed` varchar(10) NOT NULL,
  `hyperspeedMax` varchar(10) NOT NULL,
  `sublightspeed` varchar(10) NOT NULL,
  `sublightspeedMax` varchar(10) NOT NULL,
  `manoeuvrability` varchar(10) NOT NULL,
  `manoeuvrabilityMax` varchar(10) NOT NULL,
  `sensors` varchar(10) NOT NULL,
  `sensorsMax` varchar(10) NOT NULL,
  `sensorRange` varchar(10) NOT NULL,
  `sensorRangeMax` varchar(10) NOT NULL,
  `ECM` varchar(10) NOT NULL,
  `weapons` text NOT NULL,
  `passengers` mediumint(10) NOT NULL,
  `ships` mediumint(9) NOT NULL,
  `vehicles` mediumint(9) NOT NULL,
  `materials` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `groups` (
  `groupID` int(11) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(50) NOT NULL,
  `systems` text NOT NULL,
  PRIMARY KEY (`GroupID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `groups` (`GroupID`, `GroupName`, `Systems`) VALUES
(1, "No Group", "(Default Group)"),
(2, "Unknown", "Unknown");

CREATE TABLE IF NOT EXISTS `scansbasic` (
  `years` mediumint(4) NOT NULL,
  `days` mediumint(4) NOT NULL,
  `hours` mediumint(4) NOT NULL,
  `minutes` mediumint(4) NOT NULL,
  `seconds` mediumint(4) NOT NULL,
  `scannedBy` varchar(50) NOT NULL,
  `system` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `typeName` varchar(50) NOT NULL,
  `entityID` mediumint(9) NOT NULL,
  `entityTypeName` varchar(50) NOT NULL,
  `hull` smallint(6) NOT NULL,
  `hullMax` smallint(6) NOT NULL,
  `shield` smallint(6) NOT NULL,
  `shieldMax` smallint(6) NOT NULL,
  `ionic` smallint(6) NOT NULL,
  `ionicMax` smallint(6) NOT NULL,
  `underConstruction` varchar(3) NOT NULL,
  `sharingSensors` varchar(3) NOT NULL,
  `x` tinyint(4) NOT NULL,
  `y` tinyint(4) NOT NULL,
  `travelDirection` varchar(5) NOT NULL,
  `travelDirDescription` varchar(5) NOT NULL,
  `ownerName` varchar(50) NOT NULL,
  `iffStatus` varchar(7) NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `scansfocus` (
  `years` mediumint(4) NOT NULL,
  `days` mediumint(4) NOT NULL,
  `hours` mediumint(4) NOT NULL,
  `minutes` mediumint(4) NOT NULL,
  `seconds` mediumint(4) NOT NULL,
  `scannedBy` varchar(50) NOT NULL,
  `entityID` mediumint(9) NOT NULL,
  `hyperspeed` varchar(10) NOT NULL,
  `hyperspeedMax` varchar(10) NOT NULL,
  `sublightspeed` varchar(10) NOT NULL,
  `sublightspeedMax` varchar(10) NOT NULL,
  `manoeuvrability` varchar(10) NOT NULL,
  `manoeuvrabilityMax` varchar(10) NOT NULL,
  `sensors` varchar(10) NOT NULL,
  `sensorsMax` varchar(10) NOT NULL,
  `sensorRange` varchar(10) NOT NULL,
  `sensorRangeMax` varchar(10) NOT NULL,
  `ECM` varchar(10) NOT NULL,
  `weapons` text NOT NULL,
  `passengers` mediumint(10) NOT NULL,
  `ships` mediumint(9) NOT NULL,
  `vehicles` mediumint(9) NOT NULL,
  `materials` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `userHandle` varchar(25) NOT NULL,
  `userPass` text NOT NULL,
  `userGroup` int(2) DEFAULT NULL,
  `userLevel` int(2) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
