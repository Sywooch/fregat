INSERT INTO `matvid` VALUES (851,'ШКАФ'),(852,'СТОЛ'),(853,'ПРИНТЕР'),(854,'КАРТРИДЖ');
INSERT INTO `podraz` VALUES (40,'ТЕРАПЕВТИЧЕСКОЕ'),(41,'АУП');
INSERT INTO `schetuchet` VALUES (1,'101.34','НОВЫЙ СЧЕТ'),(2,'105.00','ДОПОЛНИТЕЛЬНЫЙ СЧЕТ');
INSERT INTO `izmer` VALUES (1,'шт', '796');
INSERT INTO `build` VALUES (8,'ПОЛИКЛИНИКА 1'),(9,'ПОЛИКЛИНИКА 2');
INSERT INTO `dolzh` VALUES (165,'ТЕРАПЕВТ'),(166,'ПРОГРАММИСТ'),(167,'НЕВРОЛОГ');
INSERT INTO `auth_user` VALUES (1196,'ИВАНОВ ИВАН ИВАНОВИЧ','IvanovII','$2y$13$H.bwEoPlfWDVZUCSn0vOju8Ejp0lgw78UG7KvgOoKfZki3m/GLM5S','test'),(1197,'ПЕТРОВ ПЕТР ПЕТРОВИЧ','PetrovPP','$2y$13$7Tzlr290.eomuM7XeG8utuzDSsiFnGAbhWXJ.WFiW07yrR23Lw6uK','test'),(1198,'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ','FedotovFF','$2y$13$wj1bw.JqvF45QxsMYtHSbu3QaRWMlOuzL1P.WMw/uBkeHxCYULwTa','test'),(1199,'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ','SidorovEA','$2y$13$XN0D.IjamZeTLdCGMqSkvegKi.Fhz1oQkMXATsYKEo8BnNElBScxW','test');
INSERT INTO `employee` VALUES (1175,165,40,8,1196,'admin','2016-11-17 13:33:17',NULL,NULL,1),(1176,166,41,8,1197,'admin','2016-11-22 08:37:08',NULL,NULL,1),(1177,165,40,NULL,1198,'admin','2016-11-22 09:24:59',NULL,NULL,1),(1178,167,40,9,1199,'admin','2016-11-22 13:31:59',NULL,NULL,1);
INSERT INTO `material` VALUES (34,'Шкаф для одежды','Шкаф для одежды',NULL,'1000001','ABCD123','2005-01-01',1.000,1200.15,1,0,851,1,'admin','2016-11-21 12:57:39',1,2,NULL),(35,'Кухонный стол','Кухонный стол',NULL,'1000002','','2010-05-01',1.000,15000.00,1,0,852,1,'admin','2016-11-21 12:59:37',1,1,NULL),(36,'HP LJ 1022','HP LJ 1022',NULL,'1000003','',NULL,1.000,3500.00,1,0,853,1,'admin','2016-12-15 05:01:23',1,1,NULL);
INSERT INTO `mattraffic` VALUES (1,'2016-11-22',1.000,34,1176,'admin','2016-11-22 08:57:48',1,NULL),(2,'2016-11-22',1.000,35,1176,'admin','2016-11-22 08:59:45',1,NULL),(3,'2016-12-15',1.000,36,1178,'admin','2016-12-15 15:01:23',1,NULL),(5,'2016-12-15',1.000,35,1176,'admin','2016-12-15 16:05:43',3,NULL),(6,'2016-12-15',1.000,34,1175,'admin','2016-12-15 16:05:58',3,NULL),(7,'2016-12-15',1.000,36,1178,'admin','2016-12-15 16:06:37',3,NULL);
INSERT INTO `installakt` VALUES (1,'2016-12-15',1176),(2,'2016-12-15',1176);
INSERT INTO `tr_osnov` VALUES (1,'101',1,5),(2,'102',1,6),(3,'103',2,7);