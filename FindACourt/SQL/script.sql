/*Create the table to hold all the supplemental court departments/offices*/
CREATE TABLE [Web_Supplemental].[dbo].[find_a_court](
    [ID] [int] IDENTITY(1,1) NOT NULL,
    [name] [varchar](255),
    [street_1] [varchar](255),
	[street_2] [varchar](255),
    [city] [varchar](50),
	[state] [varchar](20),
	[zip] [varchar](5),
    [county] [varchar] (50),
	[phone] [varchar](12),
	[ext] [varchar](10),
	[fax] [varchar](12),
	[district_ID] [int],
	[type_ID] [int],
	[email] [varchar](255),
	[website] [varchar](255),
	[payment_link] [varchar](255))

/*Insert data into table*/
INSERT INTO [Web_Supplemental].[dbo].[find_a_court]
	([name], [street_1], [street_2], [city], [state], [zip], [phone], [ext], [website], [payment_link], [email], [fax], [district_ID], [type_ID], [county])
VALUES
	('Argenta Township Justice Court', '50 State Route 305', NULL, 'Battle Mountain', 'Nevada', '89820', '775-635-5151', NULL, 'http://www.landercountynv.org/government/justice_court/index.php', NULL, NULL, '775-635-0604', 11,2, 'Lander County'),

	('Austin Township Justice Court', '122 Main Street', 'PO Box 100', 'Austin', 'Nevada', '89310', '775-964-2380', NULL, 'http://ncsepay.nvcourts.gov/eservicesncsltd/home.page.2;jsessionid=1CDA901D93DDE00573F2B6D6B71B33E7', NULL, NULL, '775-964-2327', 11, 2, 'Lander County'),

    ('Beatty Township Justice Court', '426 C Avenue South', 'PO Box 805', 'Beatty', 'Nevada', '89003', '775-553-2951', NULL, 'https://www.nyecounty.net/313/Beatty-Justice-Court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?Juris=252DA641-4AC8-4E18-B0DB-359BDFF49341', 'bjc@co.nye.nv.us', '775-553-2136', 5, 2, 'Nye County'),

    ('Beowawe Department of the Eureka Township Justice Court', '5041 Tenabo Avenue', NULL, 'Cresent Valley', 'Nevada', '89821', '775-237-5540', NULL, 'http://www.co.eureka.nv.us/court/justice.htm', 'https://client.pointandpay.net/web/EurekaCoJusticeCourtNV', 'justice@eurekanv.org', '775-237-6016', 7, 2, 'Eureka County'),

    ('Boulder City Municipal Court', '501 Avenue G', NULL, 'Boulder City', 'Nevada', '89005', '702-293-9278', NULL, 'http://www.bcnv.org/225/Municipal-Court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?juris=24AA96EF-E826-4BE5-BD08-FA955A775AF6', 'courts@bcnv.org', '702-293-9345', 8, 3, 'Clark County'),

    ('Boulder Township Justice Court', '501 Avenue G', NULL, 'Boulder City', 'Nevada', '89005', '702-455-8000', NULL, 'https://www.clarkcountynv.gov/government/departments/justice_courts/jurisdictions/boulder_city/index.php', 'https://cvpublicaccess.co.clark.nv.us/eservices/home.page.2', 'BoulderCityJCPR@ClarkCountyNV.gov', '702-455-8003', 8, 3, 'Clark County'),

    ('Bunkerville Township Justice Court', '190 East Virgin Street', 'PO Box 7185', 'Bunkerville', 'Nevada', '89007', '702-346-5711', NULL, 'https://www.clarkcountynv.gov/government/departments/justice_courts/jurisdictions/bunkerville/index.php', 'https://cvpublicaccess.co.clark.nv.us/eservices/home.page.2', 'BunkervilleJC@ClarkCountyNV.gov', '702-346-7212', 8, 2, 'Clark County'),

    ('Caliente Municipal Court', '100 Depot Avenue', 'PO Box 1006','Caliente', 'Nevada', '89008', '775-726-3131', NULL, 'http://www.cityofcaliente.com/city-government/court/', NULL, ' court@cityofcaliente.com', '775-726-3370', 7, 3, 'Lincoln County'),

    ('Canal Township Justice Court', '565 East Main Street', NULL, 'Fernley', 'Nevada', '89408', '775-575-3355', NULL, 'https://www.lyon-county.org/235/Fernley-Justice-Court', 'https://www.govpaynow.com/gps/user/cyg/welcome;jsessionid=6C350B0449C689B1C08B5FCB3C590C6E', NULL, '775-575-3359', 3, 2, 'Lyon County'),

    ('Carlin Municipal Court', ' 101 South Eight Street', 'PO Box 789', 'Carlin', 'Nevada', '89822', '775-754-6321', NULL, 'http://explorecarlinnv.com/departments/municipal_court/index.php', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?juris=765AC3BA-2806-4749-955E-A0CAD0D3A053', 'carlincourts@elkocountynv.net', '775-754-6893', 4, 3, 'Elko County'),

    ('Carlin Township Justice Court', '101 South 8th Street', 'PO Box 789', 'Carlin', 'Nevada', '89822', '775-754-6321', NULL, 'https://elkocountycourts.com/limited-jurisdiction-courts/carlin-justice-court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?juris=765AC3BA-2806-4749-955E-A0CAD0D3A053', 'carlincourts@elkocountynv.net', '775-754-6893', 4, 2, 'Elko County'),

    ('Carson City District Court', '885 East Musser Street', '3rd Floor', 'Carson City', 'Nevada', '89701', '775-887-2082', NULL, 'https://carson.org/government/departments-a-f/courts', 'http://www.carsonpayments.com/', 'districtcourtclerk@Carson.org', '775-887-2177', 1, 1, 'Carson City County'),

    ('Carson City Justice Court', '885 East Musser Street', 'Suite 2007', 'Carson City', 'Nevada', '89701','775-887-2121', NULL, 'https://carson.org/government/departments-a-f/courts/justice-municipal-court-clerk', 'https://www.carsonpayments.com/justice/selectpaytype.html', 'justicecourtclerk@carson.org', '775-887-2297', 1, 2, 'Carson City County'),

    ('Carson City Municipal Court', '885 East Musser Street', 'Suite 2007', 'Carson City', 'Nevada', '89701', '775-887-2121', NULL, 'http://www.carson.org/index.aspx?page=822', 'http://www.carsonpayments.com/', NULL, NULL, 1, 3, 'Carson City County'),

	('Carson City County Clerk''s Office', '885 East Musser Street', 'Suite 3031', 'Carson City', 'Nevada', '89701', '775-887-2082', NULL, 'https://carson.org/government/departments-a-f/courts/district-court-clerk', 'http://www.carsonpayments.com/', 'districtcourtclerk@Carson.org', NULL, 1, 1, 'Carson City County'),

	('Churchill County Clerk''s Office', '73 North Maine Street', 'Suite B ', 'Fallon', 'Nevada', '89406', '775-423-6088', NULL, 'http://www.churchillcounty.org/index.aspx?NID=150', NULL, 'Ssevon@churchillcourts.org', '775-423-8578', 10, 1, 'Churchill County'),

    ('Churchill County District Court', '73 North Maine Street', 'Suite B', 'Fallon', 'Nevada', '89406', '775-423-6088', NULL, 'http://www.churchillcounty.org/index.aspx?nid=150', NULL, 'Ssevon@churchillcourts.org', '775-423-8578', 10, 1, 'Churchill County'),
    
	('Clark County Clerk''s Office', '601 North Pecos Road', '1st Floor', 'Las Vegas', 'Nevada', '89101', '702-455-2590', NULL, 'http://www.clarkcountycourts.us/departments/clerk/', NULL, NULL, '702-474-2434', 8, 1, 'Clark County'),

	('Clark County District Court Civil/Criminal Division', '200 Lewis Avenue', NULL, 'Las Vegas', 'Nevada', '89155', '702-671-0530', NULL, 'http://www.clarkcountycourts.us/departments/judicial/civil-criminal-divison/', NULL, NULL, NULL, 8, 1, 'Clark County'),

	('Clark County District Court Family Division', '601 North Pecos Road', NULL, 'Las Vegas', 'Nevada', '89101', '702-455-2590', NULL, 'http://www.clarkcountycourts.us/departments/judicial/family-division/', NULL, NULL, NULL, 8, 1, 'Clark County'),

	('Dayton Township Justice Court', '235 Main Street', NULL, 'Dayton', 'Nevada', '89403', '775-246-6233', NULL, 'https://www.lyon-county.org/230/Dayton-Justice-Court', 'https://www.lyon-county.org/886/Payment-Options', 'djc@lyon-county.org', '775-246-6203', 3, 2, 'Lyon County'),
    
    ('Douglas County Clerk''s Office', '1038 Buckeye Road', 'PO Box 218', 'Minden', 'Nevada', '89423', '775-782-9820', NULL, 'https://douglasdistrictcourt.com/', 'https://douglasdistrictcourt.com/payments-fees/', NULL, '775-782-9954', 9, 1, 'Douglas County'),

    ('Douglas County District Court', '1038 Buckeye Road', 'PO Box 218', 'Minden', 'Nevada', '89423', '775-782-9820', NULL, 'https://douglasdistrictcourt.com/', 'https://douglasdistrictcourt.com/payments-fees/', NULL, '77-782-9954', 9, 1, 'Douglas County'),

    ('East Fork Township Justice Court', '1038 Buckeye Road', 'PO Box 218', 'Minden', 'Nevada', '89423', '775-782-9955', NULL, 'https://eastforkjusticecourt.com/', 'https://eastforkjusticecourt.com/payments/', NULL, '775-782-9947', 9, 2, 'Lyon County'),

    ('Eastline Justice Court', '1111 N. Gene L. Jones Way', 'PO Box 2300', 'West Wendover', 'Nevada', '89883', '775-664-2305', NULL, 'https://elkocountycourts.com/limited-jurisdiction-courts/wendover', 'http://ncsepay.nvcourts.gov/eservicesncsltd/home.page.2;jsessionid=50EEEB6774B18F1F49FE4112BBFF994B', 'EastlineJusticeCourt@ElkoCountyNV.net', '775-664-2979', 4, 2, 'Elko County'),

	('Elko County Clerk''s Office', '550 Court Street', '3rd Floor', 'Elko', 'Nevada', '89801', '775-753-4600', NULL,  'https://www.elkocountynv.net/departments/clerk/index.php', NULL, 'clerk@elkocountynv.net', '775-753-4610', 4, 1, 'Elko County'),

	('Elko County District Court Department I', '571 Idaho Street', NULL, 'Elko', 'Nevada', '89801', '775-753-4601', NULL, 'https://elkocountycourts.com/nevada-fourth-judicial-district-court/department-i', NULL, 'fourjdc1@elkocountynv.net', '775-753-4611', 4, 1, 'Elko County'),

	('Elko County District Court Department II', '571 Idaho Street', NULL, 'Elko', 'Nevada', '89801', '775-753-4602', NULL, 'https://elkocountycourts.com/nevada-fourth-judicial-district-court/department-ii', NULL, NULL, '775-753-3762', 4, 1, 'Elko County'),

	('Elko County District Court Department III', '571 Idaho Street', NULL, 'Elko', 'Nevada', '89801', '775-738-3980', NULL, 'https://elkocountycourts.com/nevada-fourth-judicial-district-court/department-iii', NULL, NULL, '775-738-4830', 4, 1, 'Elko County'),

	('Elko Juvenile and Family Court Services', '665 West Silver Street', NULL, 'Elko', 'Nevada', '89801', '775-738-1551', NULL, 'https://elkocountycourts.com/', NULL, NULL, '775-738-5060', 4, 1, 'Elko County'),

    ('Elko Municipal Court', '571 Idaho Street', NULL, 'Elko', 'Nevada', '89801', '775-738-8403', NULL, 'http://elkocountycourts.com/limited-jurisdiction-courts/elko-justice-court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?Juris=6A03BC4F-14BD-40EB-9B20-8FBE5AA61DDE', NULL, '775-738-8416', 4, 3, 'Elko County'),

    ('Elko Township Justice Court', '571 Idaho Street', NULL, 'Elko', 'Nevada', '89801', '775-738-8403', NULL, 'https://elkocountycourts.com/limited-jurisdiction-courts/elko-justice-court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?Juris=6A03BC4F-14BD-40EB-9B20-8FBE5AA61DDE', 'elkojusticecourt@elkocountynv.net', '775-738-8416', 4, 2, 'Elko County'),

    ('Ely Municipal Court', '1786 Great Basin Boulevard', NULL, 'Ely', 'Nevada', '89301','775-289-7840', NULL, NULL, NULL, NULL, '775-289-8225', 7, 2, 'White Pine County'),

    ('Ely Township Justice Court', '1786 Great Basin Blvd', 'Suite 6', 'Ely', 'Nevada', '89301', '775-293-6540', NULL, 'https://www.whitepinecounty.net/Directory.aspx?did=24', 'https://www.whitepinecounty.net/560/Pay', NULL, '775-289-3392', 7,2, 'White Pine County'),

	('Esmeralda County Clerk''s Office', 'PO Box 547', NULL, 'Goldfield', 'Nevada', '89013', '775-485-6309', NULL, 'https://www.accessesmeralda.com/county_offices/clerk_treasurer/index.php', NULL, 'celgan@esmeraldacountynv.org', '775-485-6376', 5, 1, 'Esmeralda County'),

    ('Esmeralda County District Court', '1520 East Basin Avenue', 'Suite #105', 'Pahrump', 'Nevada', '89060', '775-485-6309', NULL, 'http://www.accessesmeralda.com/county_offices/fifth_judicial_district_court.php', NULL, 'clerktreasurer@esmeraldacountynv.org', '775-485-6376', 5, 1, 'Esmeralda County'),

    ('Esmeralda Township Justice Court', '403 Crook Street', 'PO Box 370', 'Goldfield', 'Nevada', '89013', '775-485-6359', NULL, 'http://cms2.revize.com/revize/esmeraldanew/county_offices/justice_court/index.php', 'http://cms2.revize.com/revize/esmeraldanew/county_offices/justice_court/payments.php', 'jp@esmeraldacountynv.org', '775-485-3462', 5, 2, 'Esmeralda County'),

	('Eureka County Clerk''s Office', 'PO Box 540', NULL, 'Eureka', 'Nevada', '89316', '775-237-5263', NULL, 'http://www.co.eureka.nv.us/clerk/clerk01.htm', NULL, 'kbowling@eurekacountynv.gov', '775-237-5614', 7, 1, 'Eureka County'),

    ('Eureka County District Court', 'PO Box 694', NULL, 'Eureka', 'Nevada', '89316', '775-237-5263', NULL, 'http://www.co.eureka.nv.us/clerk/court.htm', NULL, NULL, '775-237-5614', 7, 1, 'Eureka County'),

    ('Eureka Township Justice Court', '701 South Main Street', 'PO Box 496', 'Eureka', 'Nevada', '89316', '775-237-5540', NULL, 'http://www.co.eureka.nv.us/court/eureka.htm', 'https://client.pointandpay.net/web/EurekaCoJusticeCourtNV', NULL, '775-237-6016', 7, 2, 'Eureka County'),

    ('Fallon Municipal Court', '55 West Williams Avenue', NULL, 'Fallon', 'Nevada', '89406', '775-423-6244', NULL, 'https://www.fallonnevada.gov/government/departments/', NULL, NULL, '775-867-2378', 10, 3, 'Churchill County'),

    ('Fernley Municipal Court', '595 Silverlace Boulevard', NULL, 'Fallon', 'Nevada', '89408', '775-784-9870', NULL, 'https://www.cityoffernley.org/Directory.aspx?did=12', NULL, NULL, '775-784-9999', 3, 3, 'Lyon County'),

    ('Goodsprings Township Justice Court', '23120 Las Vegas Blvd', 'PO Box 19155', ' Jean', 'Nevada', '89019', '702-874-1405', NULL, 'https://www.clarkcountynv.gov/government/departments/justice_courts/jurisdictions/goodsprings/index.php', 'http://cvpublicaccess.co.clark.nv.us/eservices/home.page.2', 'GoodspringsJCPR@ClarkCountyNV.gov', '702-874-1612', 8, 2, 'Clark County'),

    ('Hawthorne Township Justice Court', '166 East Street', 'PO Box 1660', 'Hawthorne', 'Nevada', '89415', '775-945-3859', NULL, 'http://www.mineralcountynv.us/government/hawthorne_justice_court.php', 'http://www.mineralcountynv.us/government/make_a_payment.php', NULL, '775-945-0700', 11, 2, 'Hawthorne County'),

    ('Henderson Municipal Court', '243 Water Street', NULL, 'Henderson', 'Nevada', '89015', '702-267-3300', NULL, 'https://www.cityofhenderson.com/government/departments/municipal-court', 'https://www.cityofhenderson.com/residents/online-payments', 'HMCCS@cityofhenderson.com', NULL, 8, 3, 'Clark County'),

    ('Henderson Township Justice Court', '243 Water Street', NULL, 'Henderson', 'Nevada', '89015', '702-455-7951', NULL, 'https://www.clarkcountynv.gov/government/departments/justice_courts/jurisdictions/henderson/index.php', 'http://www.clarkcountynv.gov/justicecourt/henderson/services/Pages/Payments.aspx', 'hendersonjc@ClarkCountyNV.gov', '702-445-7977', 8, 2, 'Clark County'),

	('Humboldt County Clerk''s Office', '50 West Fifth Street', '#207', 'Winnemucca', 'Nevada', '89445', '775-623-6343', NULL, 'http://sixthjudicialdistrict.com/', NULL, 'julia.dendary@hcdcnv.com', NULL, 6, 1, 'Humboldt County'),

    ('Humboldt County District Court', '50 West 5th Street', 'Room #207', 'Winnemucca', 'Nevada', '89445', '775-623-6371', NULL, 'http://sixthjudicialdistrict.com/', NULL, 'kbrumm@hcdcnv.com', NULL, 6, 1, 'Humboldt County'),

    ('Incline Village Township Justice Court', '865 Tahoe Boulevard', 'Suite 301', 'Incline Village', 'Nevada', '89451', '775-832-4100', NULL, 'http://www.ivcbcourt.com/', 'https://www.ivcbcourt.com/payments', 'IJCinfo@washoecounty.us', '775-832-4162', 2, 2, 'Washoe County'),

    ('Lake Township Justice Court', '400 Main Street', 'PO Box 8', 'Lovelock', 'Nevada', '89419', '775-273-2753', NULL, 'http://www.pershingcounty.net/government/justice_court/index.php', NULL,  NULL, '775-273-0416', 11, 2, 'Pershing County'),

	('Lander County Clerk''s Office', '400 Main Street', NULL, 'Lovelock', 'Nevada', '89419', '775-273-2410', '1322', 'http://eleventhjudicialdistrict.com/contact-us/kate-martin', NULL, 'kmartin@11thjudicialdistrictcourt.net', NULL, 11, 1, 'Lander County'),
    
    ('Lander County District Court', '50 State Rte', NULL, 'Battle Mountain', 'Nevada', '89820', '775-635-1332', NULL, 'https://eleventhjudicialdistrict.com/', NULL, NULL, '775-635-0394', 11, 1, 'Lander County'),

	('Lander County Youth and Family Services', '50 State Route 305', NULL, 'Battle Mountain', 'Nevada', '89820', '775-635-2117', NULL, 'https://eleventhjudicialdistrict.com/', NULL, NULL, '775-635-2146', 11, 1, 'Lander County'),

    ('Las Vegas Municipal Court', '100 East Clark Avenue', NULL, 'Las Vegas', 'Nevada', '89101', '702-382-6878', NULL, 'https://www.lasvegasnevada.gov/Government/Municipal-Court', 'https://municourt.lasvegasnevada.gov/Defendants/Search', 'https://www.lasvegasnevada.gov/Contact/Directory', NULL, 8, 3, 'Clark County'),

    ('Las Vegas Township Justice Court', '200 Lewis Avenue', NULL, 'Las Vegas', 'Nevada', '89101', '702-671-3116', NULL, 'http://www.lasvegasjusticecourt.us/', 'https://lvjcpa.clarkcountynv.gov/Anonymous/Search.aspx?ID=600', NULL, '702-671-2512', 8, 2, 'Clark County'),

    ('Laughlin Township Justice Court', '101 Civic Way', 'Suite 2', 'Laughlin', 'Nevada', '89029', '702-298-4622', NULL, 'https://www.clarkcountynv.gov/government/departments/justice_courts/jurisdictions/laughlin_township/index.php', 'http://cvpublicaccess.co.clark.nv.us/eservices/home.page.2', 'LaughlinJCPR@ClarkCountyNV.gov', '702-298-7508', 8, 2, 'Clark County'),

	('Lincoln County Clerk''s Office', '181 North Main Street', 'Suite 201 PO Box 90', 'Pioche', 'Nevada', '89043', '775-962-8000', NULL, 'https://lincolncountynv.org/departments/clerks-office/', NULL, NULL, '775-962-5180', 7, 1, 'Lincoln County'),

    ('Lincoln County District Court', '181 North Main Street', 'Suite 208', 'Pioche', 'Nevada', '89043', '775-962-8000', NULL, 'https://lincolncountynv.org/departments/courts/seventh-judicial-district-court/', NULL, NULL, '775-962-5180', 7, 1, 'Lincoln County'),

	('Lyon County Clerk''s Office', '911 Harvey Way', NULL, 'Yerington', 'Nevada', '89447', '775-463-6503', NULL, 'https://www.lyon-county.org/155/District-Court-Clerk', NULL, NULL, '775-463-5305', 3, 1, 'Lyon County'),

    ('Lyon County District Court', '911 Harvey Way', NULL, 'Yerington', 'Nevada', '89447', '775-463-6571', NULL, 'http://www.lyon-county.org/index.aspx?nid=675', NULL, NULL, '775-463-6575', 3, 1, 'Lyon County'),

    ('Meadow Valley Township Justice Court', '1 Main Street', 'PO Box 36', 'Pioche', 'Nevada', '89043', '775-962-8059', NULL, 'https://lincolncountynv.org/departments/courts/meadow-valley-justice-court/', NULL, 'mvjc@lincolnnv.com', '775-968-5559', 7, 2, 'Lincoln County'),

    ('Mesquite Municipal Court', '500 Hillside Drive', NULL, 'Mesquite', 'Nevada', '89027', '702-346-5291', NULL, 'https://www.mesquitenv.gov/departments/municipal-court', 'https://nvcourts.gov/supreme/how_do_i/pay_a_ticket', 'municipalcourt@mesquitenv.gov','702-346-6587', 8, 3, 'Clark County'),

    ('Mesquite Township Justice Court', '500 Hillside Drive', NULL, 'Mesquite', 'Nevada', '89027', '702-346-5298', NULL, 'https://www.clarkcountynv.gov/government/departments/justice_courts/jurisdictions/mesquite/index.php', 'https://cvpublicaccess.co.clark.nv.us/eservices/home.page.2', 'MesquiteJCPR@ClarkCountyNV.gov', '702-346-7319', 8, 3, 'Clark County'),

	('Mineral County Clerk''s Office', '400 Main Street', 'PO Box H', 'Lovelock', 'Nevada', '89419', '775-273-2410', '1322', 'http://eleventhjudicialdistrict.com/contact-us/kate-martin', NULL, 'kmartin@11thjudicialdistrictcourt.net', NULL, 11, 1, 'Mineral County'),

	('Mineral County Youth and Family Services', '525 West 9th Street', 'PO Box 1167', 'Hawthorne', 'Nevada', '89415', '775-945-3393', NULL, 'https://eleventhjudicialdistrict.com/', NULL, NULL, '775-945-0719', 11, 1, 'Mineral County'),

    ('Mineral County District Court', '105 S. A Street', 'PO Box 1450', 'Hawthorne', 'Nevada', '89415', '775-945-0738', NULL, 'https://eleventhjudicialdistrict.com/', NULL, NULL, NULL, 11, 1, 'Mineral County'),

    ('Moapa Township Justice Court', '1340 East Highway 168', NULL, 'Moapa', 'Nevada', '89025', '702-864-2333', NULL, 'http://www.clarkcountynv.gov/justicecourt/moapa/Pages/default.aspx', 'http://cvpublicaccess.clarkcountynv.gov/eservices/home.page', 'moapajusticecourt@ClarkCountyNV.gov', '702-864-2585', 8, 2, 'Clark County'),

    ('Moapa Valley Township Justice Court', '320 North Moapa Valley Blvd', NULL, 'Moapa Valley', 'Nevada', '89040', '702-397-2840', NULL, 'http://www.clarkcountynv.gov/justicecourt/moapa-valley/Pages/default.aspx', 'https://cvpublicaccess.co.clark.nv.us/eservices/home.page.2', 'MoapaValleyJCPR@ClarkCountyNV.gov', '702-397-2842', 8, 2, 'Clark County'),

    ('New River Township Justice Court', '71 North Maine Street', NULL, 'Fallon', 'Nevada', '89406', '775-423-2845', NULL, 'http://www.churchillcounty.org/index.aspx?NID=158', '', 'justicecourt@churchillcounty.org', '775-423-0472', 10, 2, 'Churchill County'),

    ('North Las Vegas Municipal Court', '2332 Las Vegas Boulevard', 'Suite 100', 'North Las Vegas', 'Nevada', '89030', '702-633-1130', NULL, 'http://www.cityofnorthlasvegas.com/departments/municipalcourt/municipalcourt.shtm', 'https://municourt.cityofnorthlasvegas.com/eservices/home.page.2', NULL, '702-399-6296', 8, 3, 'Clark County'),

	('Nye County Clerk''s Office - Tonopah Office', '101 Radar Road', 'Tonopah', NULL, 'Nevada', '89049', '775-482-8127', NULL, 'http://www.nyecounty.net/232/Clerk', NULL, 'smerlino@co.nye.nv.us', '775-482-8133', 5, 1, 'Nye County'),

	('Nye County Clerk''s Office - Pahrump Office', '1520 East Basin Avenue', NULL, 'Pahrump', 'Nevada', '89060', '775-751-7040', NULL, 'http://www.nyecounty.net/232/Clerk', NULL, 'smerlino@co.nye.nv.us', '775-751-7047', 5, 1, 'Nye County'),

	('Nye County District Court Department 1', '1520 East Basin Avenue', '#105', 'Pahrump', 'Nevada', '89060', '775-751-4210', NULL, 'http://www.nyecounty.net/295/District-Court', NULL, NULL, '775-751-4218', 5, 1, 'Nye County'),

	('Nye County District Court Department 2', '1520 East Basin Avenue', '#105','Pahrump', 'Nevada', '89060', '775-751-4213', NULL, 'http://www.nyecounty.net/295/District-Court', NULL, NULL, '775-751-4218', 5, 1, 'Nye County'),

    ('Pahranagat Valley Township Justice Court', '121 Joshua Tree Street', 'PO Box 449', 'Alamo', 'Nevada', '89001', '775-962-8082', NULL, 'https://lincolncountynv.org/departments/courts/pahranagat-valley-justice-court/', NULL, NULL, '775-725-3566', 7, 2, 'Lincoln County'),

    ('Pahrump Township Justice Court', '1520 East Basin Ave', 'Suite 104', 'Pahrump', 'Nevada', '89060', '775-751-7050', NULL, 'https://www.pahrumpjusticecourt.com/', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?Juris=3A07F842-5C1F-4E8E-B214-CA0345EA4CDD', 'PJC@PahrumpJusticeCourt.com', '775-751-7059', 5, 2, 'Nye County'),

	('Pershing County Clerk''s Office', '400 Main Street', 'PO Box H', 'Lovelock', 'Nevada', '89419', '775-273-2410', '1322', 'https://eleventhjudicialdistrict.com/contact-us/kate-martin', NULL, 'kmartin@11thjudicialdistrictcourt.net', NULL, 11, 1),

    ('Pershing County District Court', '400 Main Street', 'PO Box H', 'Lovelock', 'Nevada', '89419', '775-273-2410', NULL, 'https://eleventhjudicialdistrict.com/', NULL, NULL, '775-273-4921', 11, 1, 'Pershing County'),

	('Pershing County Youth and Family Services', '795 Western Avenue', 'PO Box 501', 'Lovelock', 'Nevada', '89419', '775-273-2769', NULL, 'https://eleventhjudicialdistrict.com/', NULL, NULL, '775-273-5113', 11, 1, 'Pershing County'),

    ('Reno Municipal Court', '1 South Sierra Street', NULL, 'Reno', 'Nevada', '89505', '775-334-2290', NULL, 'http://www.reno.gov/government/municipal-court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?Juris=80fa7c0e-8fc9-4238-97e8-6596160df39e', 'RMC@reno.gov', '775-334-3824', 2, 3, 'Washoe County'),

    ('Reno Township Justice Court', '1 South Sierra Street', NULL, 'Reno', 'Nevada', '89501', '775-325-6500', NULL, 'https://www.washoecounty.us/rjc/', 'https://www.washoecounty.us/rjc/washoe/pay-ticket-online.php', 'rjcweb@washoecounty.us', '775-325-6510', 2, 2, 'Washoe County'),

    ('Searchlight Township Justice Court', '1090 Cottonwood Cove Road', 'PO Box 815', 'Searchlight', 'Nevada', '89046', '702-297-1252', NULL, 'https://www.clarkcountynv.gov/government/departments/justice_courts/jurisdictions/searchlight/index.php', 'http://cvpublicaccess.co.clark.nv.us/eservices/home.page.2', 'SearchlightJCPR@ClarkCountyNV.gov', '702-297-1022', 8, 2, 'Clark County'),

    ('Sparks Municipal Court', '1450 C Street', NULL, 'Sparks', 'Nevada', '89431', '775-353-2286', NULL, 'https://www.cityofsparks.us/your_government/departments/municipal_court/index.php', 'https://govpaynow.com/gps/user/plc/8422', NULL, '775-353-2400', 2, 3, 'Washoe County'),

    ('Sparks Township Justice Court', '1675 East Prater Way', 'Suite 107', 'Sparks', 'Nevada', '89434', '775-353-7600', NULL, 'https://www.washoecounty.us/sjc/', 'https://www.washoecounty.gov/sjc/Payments%20Disclaimer.php', NULL, '775-352-3004', 2, 2, 'Washoe County'),

	('Storey County Clerk''s Office', '26 south B Street', 'Drawer D', 'Virginia City', 'Nevada', '89440', '775-847-0969', NULL, 'https://www.storeycounty.org/government/departments/clerk/index.php', 'http://www.storeycounty.org/426/Pay-For', 'clerk@storeycounty.org', '775-547-0921', 1, 1, 'Storey County'),

    ('Storey County District Court', '26 South B Street', 'Drawer D', 'Virginia City', 'Nevada', '89440', '775-847-0969', NULL, 'http://www.storeycounty.org/193/District-Court', 'http://www.storeycounty.org/426/Pay-For', 'clerk@storeycounty.org', '775-847-0921', 1, 1, 'Storey County'),

    ('Tahoe Township Justice Court', '175 Highway 50', 'PO Box 7169', 'Stateline', 'Nevada', '89449', '775-586-7200', NULL, 'https://tahoejusticecourt.com/', 'https://tahoejusticecourt.com/payments/', NULL, '775-586-7203', 9, 2, 'Douglas County'),

    ('Tonopah Township Justice Court', '101 Radar Road', 'PO Box 1151', 'Tonopah', 'Nevada', '89049', '775-482-8155', NULL, 'https://www.nyecounty.net/898/Tonopah-Justice-Court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?juris=AC5A4E0B-E9AA-4001-BAAB-0EED27DE6B4B', 'tjc@co.nye.nv.us', '775-482-7349', 5, 2, 'Nye County'),

    ('Union Township Justice Court', '50 West Fifth Street', 'PO Box 1218', 'Winnemucca', 'Nevada', '89445', '775-623-6377', NULL, 'https://www.hcnv.us/179/Justice-Court', 'https://www.ncourt.com/x-press/x-onlinepayments.aspx?Juris=E7518401-0DED-40B5-BCCA-F9171670E78C', 'Justice@hcnv.us', '775-623-6439', 6, 2, 'Humbolt County'),

    ('Virginia Township Justice Court', '800 South C Street', 'PO Box 674', 'Virginia City', 'Nevada', '89440', '775-847-0962', NULL, 'https://www.storeycounty.org/government/departments/justice_court___justice_of_the_peace/index.php', 'https://www.storeycounty.org/how_do_i/pay_for/index.php', 'jp@storeycounty.org', '775-847-0915', 1, 2, 'Storey County'),

    ('Wadsworth Township Justice Court', '390 West Main Street', 'PO Box 68', 'Wadsworth', 'Nevada', '89442', '775-575-4585', NULL, NULL, NULL, NULL, '775-575-0253', 2, 2, 'Washoe County'),

    ('Walker River Township Justice Court', '911 Harvey Way', 'Suite 2', 'Yerington', 'Nevada', '89447', '775-463-6639', NULL, 'https://www.lyon-county.org/240/Walker-River-Justice-Court', 'https://www.govpaynow.com/gps/user/cyg/plc/a0052f', 'wrjc@lyon-county.org', '775-463-6638', 3, 2, 'Lyon County'),

	('Washoe County District Court Family Jurisdiction', '1 South Sierra Street', NULL, 'Reno', 'Nevada', '89501', '775-328-3110', NULL, 'https://www.washoecourts.com/FamilyServices', NULL, NULL, NULL, 2, 1, 'Washoe County'),

	('Washoe County District Court General Jurisdiction', '75 Court Street', NULL, 'Reno', 'Nevada', '89501', '775-328-3110', NULL, 'http://www.washoecourts.com/', NULL, NULL, NULL, 2, 1, 'Washoe County'),

    ('Wells Municipal Court', '1510 Lake Avenue', 'PO Box 297', 'Wells', 'Nevada', '89835', '775-752-3726', NULL, 'https://elkocountycourts.com/limited-jurisdiction-courts/wells-justice-court', NULL, NULL, '775-752-3363', 4, 3, 'Elko County'),

    ('Wells Township Justice Court', '1510 Lake Avenue', NULL, 'Wells', 'Nevada', '89825', '775-752-3726', NULL, 'https://elkocountycourts.com/limited-jurisdiction-courts/wells-justice-court', NULL, 'jpwells@elkocountynv.net', '775-752-3363', 4, 2, 'Elko County'),

    ('West Wendover Municipal Court', '1111 North Gene L. Jones Way', 'PO Box 2300', 'West Wendover', 'Nevada', '89883', '775-664-2305', NULL, 'https://elkocountycourts.com/limited-jurisdiction-courts/wendover', 'https://nvcourts.gov/supreme/how_do_i/pay_a_ticket', 'wwmunicipalcourt@elkocountynv.net', '775-664-2979', 4, 3, 'Elko County'),

	('White Pine County Clerk''s Office', '801 Clark Street', 'Suite 4', 'Ely', 'Nevada', '89301', '775-293-6509', NULL, 'https://www.whitepinecounty.net/138/Clerk', NULL, 'clerksoffice@whitepinecountynv.gov', '775-289-2544', 7, 1, 'White Pine County'),

    ('White Pine County District Court', '801 Clark Street', 'Suite 4', 'Ely', 'Nevada', '89301', '775-293-6509', NULL, 'https://www.whitepinecounty.net/138/Clerk', NULL, 'clerksoffice@whitepinecountynv.gov', '775-289-2544', 7, 1, 'White Pine County'),

    ('Yerington Municipal Court', '14 East Goldfield Avenue', NULL, 'Yerington', 'Nevada', '89447','775-463-3511', NULL, 'https://www.yerington.net/municipal-court', NULL, 'courtclerk@yerington.net', '775-463-2284', 3, 3, 'Lyon County')
