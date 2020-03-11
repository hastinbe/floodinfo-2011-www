-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 06, 2013 at 09:58 PM
-- Server version: 5.5.28
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `name` varchar(128) NOT NULL,
  `url` varchar(128) DEFAULT NULL,
  `twitter_url` varchar(128) DEFAULT NULL,
  `facebook_url` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `location_id`, `name`, `url`, `twitter_url`, `facebook_url`) VALUES
(1, 1, 'Bismarck Flood Info', 'http://bisflood.info/', 'http://twitter.com/bisfloodinfo', 'http://www.facebook.com/bisfloodinfo'),
(2, 1, 'Burleigh County', 'http://burleighco.com/departments/missouri/', 'http://twitter.com/burleighco', 'http://www.facebook.com/burleighco'),
(3, 1, 'City of Bismarck', 'http://bismarck.org/', 'http://twitter.com/BismarckNDGov', 'http://www.facebook.com/pages/City-of-Bismarck-North-Dakota/103403749697601?v=wall'),
(4, 1, 'City of Mandan', 'http://www.cityofmandan.com/', NULL, NULL),
(5, 1, 'MAP: Sandbag Sites, Road Closures, Alternate Routes', 'http://www.bismarck.org/DocumentView.aspx?DID=3856', NULL, NULL),
(6, 1, 'MAP: Bismarck Flood Plain', 'http://www.bismarck.org/DocumentView.aspx?DID=2101', NULL, NULL),
(7, 1, 'Morton County', 'http://www.co.morton.nd.us/', NULL, NULL),
(8, 1, 'ND Department of Emergency Services', 'http://www.nd.gov/des/', 'http://twitter.com/NDDES', 'http://www.facebook.com/pages/Bismarck-ND/NDDES/261304424911'),
(9, 1, 'ND Department of Emergency Services Interactive Mapping Tool', 'http://www.nd.gov/des/ndelevationlookup/', NULL, NULL),
(10, 1, 'ND GIS Flood Information', 'http://www.nd.gov/gis/floodinfo/', NULL, NULL),
(11, 1, 'NWS Ensemble Streamflow Hydrologic Outlook', 'http://www.crh.noaa.gov/mbrfc/?n=new_outlook', NULL, NULL),
(12, 1, 'NWS Missouri River at Bismarck', 'http://water.weather.gov/ahps2/hydrograph.php?wfo=bis&gage=biwn8', NULL, NULL),
(13, 1, 'NWS River Conditions', 'http://www.crh.noaa.gov/mbrfc/?n=quickbrief', NULL, NULL),
(14, 1, 'USGS Missouri River at Bismarck', 'http://nd.water.usgs.gov/floodtracking/charts/06342500_10130101.html', NULL, NULL),
(15, 2, 'Ward County', 'http://www.co.ward.nd.us/', NULL, 'https://www.facebook.com/home.php?sk=group_314841914556'),
(16, 2, 'NDSU Extension Office', 'https://www.facebook.com/WardCountyExtension', NULL, NULL),
(17, 2, 'City of Minot', 'http://www.minotnd.org/', NULL, 'https://www.facebook.com/minotnorthdakota'),
(18, 2, 'Ward County Emergency Management', 'http://www.co.ward.nd.us/emergency-management/', NULL, 'https://www.facebook.com/home.php?sk=group_314841914556'),
(19, 2, 'ND GIS Flood Information', 'http://www.nd.gov/gis/floodinfo/', NULL, NULL),
(20, 2, 'NWS Ensemble Streamflow Hydrologic Outlook', 'http://www.crh.noaa.gov/mbrfc/?n=new_outlook', NULL, NULL),
(21, 2, 'NWS River Conditions', 'http://www.crh.noaa.gov/mbrfc/?n=quickbrief', NULL, NULL),
(22, 2, 'NWS Souris River at Minot-Broadway Bridge', 'http://water.weather.gov/ahps2/hydrograph.php?wfo=bis&gage=mion8', NULL, NULL),
(23, 2, 'USGS Souris River at Minot-Broadway Bridge', 'http://nd.water.usgs.gov/floodtracking/charts/481417101174500.html', NULL, NULL),
(24, 2, 'Ward County Inundation Map 6-21-2011 ', 'http://www.co.ward.nd.us/UserFiles/File/highway/Mapping%20(pdf)/Inundation%20Map%20for%20Souris%20River%206-21-2011.pdf', NULL, NULL),
(25, 3, 'F-M Area Diversion', 'http://www.fmdiversion.com/', 'https://twitter.com/fmdiversion', NULL),
(26, 3, 'City of Fargo - Flood Information', 'http://www.cityoffargo.com/CityInfo/Departments/Engineering/FloodInformation/', NULL, NULL),
(27, 3, 'City of Fargo - Road Closures', 'http://www.cityoffargo.com/CityInfo/Departments/Engineering/FloodInformation/RoadClosures.aspx', NULL, NULL),
(28, 3, 'City of Fargo - Emergency Dike Locations', 'http://www.cityoffargo.com/CityInfo/Departments/Engineering/FloodInformation/EmergencyDikeLocations.aspx', NULL, NULL),
(29, 3, 'Fargo Interactive GIS Flood Mapping', 'http://gis.ci.fargo.nd.us/FargoFloodStages/FargoFloodApp.html', NULL, NULL),
(30, 3, 'Cass County Interactive GIS Flood Mapping', 'http://gisweb.casscountynd.gov/sandbag/sandbag.html', NULL, NULL),
(31, 3, 'Red River Basin''s Interactive Flood Planning Map', 'http://gis.rrbdin.org/ffviewer/', NULL, NULL),
(32, 3, 'Cass County - Elevation Flood Maps', 'http://www.casscountynd.gov/Flood/Pages/FloodMaps.aspx', NULL, NULL),
(33, 3, 'Cass County - Flood Related Press Releases', 'http://www.casscountynd.gov/Flood/Pages/FloodRelatedPressReleases.aspx', NULL, NULL),
(34, 3, 'Cass County', 'http://www.casscountynd.gov/', 'http://twitter.com/casscountyem', 'http://www.facebook.com/pages/Cass-County-Emergency-Management/183680848318834'),
(35, 3, 'Cass County - Flood Policies', 'http://www.casscountynd.gov/Flood/Pages/FloodPolicies.aspx', NULL, NULL),
(36, 3, 'City of Moorhead - Floodplain Information', 'http://www.ci.moorhead.mn.us/the_city/floodplain.asp', NULL, NULL),
(37, 3, 'Moorhead Interactive GIS Flood Mapping', 'http://gis.cityofmoorhead.com/FloodStages/index.html', NULL, NULL),
(38, 3, 'Red River Basin''s LiDAR Viewer', 'http://gis.rrbdin.org/lidarviewer/index.html', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
