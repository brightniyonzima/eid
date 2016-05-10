/* Appendix: Mother PMTCT ARV Antenatal Table */
DROP TABLE IF EXISTS eid_appendix_pmtctarvsmothersantenatal;
CREATE TABLE eid_appendix_pmtctarvsmothersantenatal (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  appendix varchar(200) NOT NULL,
  position mediumint unsigned NOT NULL,
  created datetime NOT NULL, /* As a principle, I always log the created datetime for every record in every table */
  createdby varchar(250) NOT NULL, /* As a principle, I always log the email of the user who created the record in every table */
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (appendix),
  KEY positionIndex (position) 
) ENGINE=InnoDB;

/* Appendix: Mother PMTCT ARV Delivery Table */
DROP TABLE IF EXISTS eid_appendix_pmtctarvsmothersdelivery;
CREATE TABLE eid_appendix_pmtctarvsmothersdelivery (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  appendix varchar(200) NOT NULL,
  position mediumint unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (appendix),
  KEY positionIndex (position) 
) ENGINE=InnoDB;

/* Appendix: Mother PMTCT ARV PostNatal Table */
DROP TABLE IF EXISTS eid_appendix_pmtctarvsmotherspostnatal;
CREATE TABLE eid_appendix_pmtctarvsmotherspostnatal (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  appendix varchar(200) NOT NULL,
  position mediumint unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (appendix),
  KEY positionIndex (position) 
) ENGINE=InnoDB;

/* Appendix: Infant PMTCT ARVs Table */
DROP TABLE IF EXISTS eid_appendix_pmtctarvsinfants;
CREATE TABLE eid_appendix_pmtctarvsinfants (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  appendix varchar(200) NOT NULL,
  position mediumint unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (appendix),
  KEY positionIndex (position) 
) ENGINE=InnoDB;

/* 
Appendix: Sample Rejection Reasons 
Relationships: 
	eid_appendix_samplerejectionreason.sampleTypeID == eid_appendix_sampletype.id
*/
DROP TABLE IF EXISTS eid_appendix_samplerejectionreason;
CREATE TABLE eid_appendix_samplerejectionreason (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  appendix varchar(200) NOT NULL,
  position mediumint unsigned NOT NULL,
  sampleTypeID mediumint unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (appendix,sampleTypeID),
  KEY positionIndex (position) 
) ENGINE=InnoDB;

/* 
Appendix: Sample Types 
Comment: sample types may be DBS, Plasma or Whole Blood.
*/
DROP TABLE IF EXISTS eid_appendix_sampletype;
CREATE TABLE eid_appendix_sampletype (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  appendix varchar(200) NOT NULL,
  position mediumint unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (appendix),
  KEY positionIndex (position) 
) ENGINE=InnoDB;

/* 
Entry Clinic/Facility Table 
Relationships: 
	eid_facilities.districtID == eid_districts.id
	eid_facilities.hubID == eid_hubs.id
  eid_facilities.facilityLevelID == eid_facility_levels.id
*/
DROP TABLE IF EXISTS eid_facilities;
CREATE TABLE eid_facilities (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  facilityCode varchar(250) NOT NULL,
  facility varchar(250) NOT NULL,
  facilityLevelID smallint(2) NOT NULL,
  districtID mediumint unsigned NOT NULL,
  hubID mediumint unsigned NOT NULL,
  phone varchar(20) NOT NULL,
  email varchar(50) NOT NULL,
  contactPerson varchar(250) NOT NULL,
  physicalAddress text NOT NULL,
  returnAddress text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (facility,districtID),
  KEY districtIDIndex (districtID), 
  KEY hubIDIndex (hubID),
  KEY facilityLevelIDIndex (facilityLevelID)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS eid_facility_levels;
CREATE TABLE eid_facility_levels (
  id smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  facility_level varchar(100) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (facility_level)
) ENGINE=InnoDB;
/* 
Districts Table 
Relationships: 
	eid_districts.regionID == eid_regions.id
*/
DROP TABLE IF EXISTS eid_districts;
CREATE TABLE eid_districts (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  district_nr mediumint unsigned NOT NULL,
  district varchar(100) NOT NULL,
  regionID tinyint unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (district),
  KEY regionIDIndex (regionID) 
) ENGINE=InnoDB;

/* Regions Table */
DROP TABLE IF EXISTS eid_regions;
CREATE TABLE eid_regions (
  id tinyint unsigned NOT NULL AUTO_INCREMENT,
  region varchar(100) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (region)
) ENGINE=InnoDB;

/* Implementing Partners Table*/
DROP TABLE IF EXISTS eid_ips;
CREATE TABLE eid_ips (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  ip varchar(250) NOT NULL,
  classification varchar(5) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (ip)
) ENGINE=InnoDB;

/*table eid_ips_facilities To handle many to many r/ship between IPs and Facilities*/
DROP TABLE IF EXISTS eid_ips_facilities;
CREATE TABLE eid_ips_facilities (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  ipID mediumint unsigned NOT NULL,
  facilityID mediumint unsigned NOT NULL,
  start_date date NOT NULL,
  stopped smallint(1) unsigned NOT NULL,
  stop_date date,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

/* Hubs Table */
DROP TABLE IF EXISTS eid_hubs;
CREATE TABLE eid_hubs (
  id mediumint unsigned NOT NULL AUTO_INCREMENT,
  hub varchar(250) NOT NULL,
  email varchar(250) NOT NULL,
  ipID varchar(250) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (hub,email),
  KEY emailIndex (email), 
  KEY ipIDIndex (ipID)
) ENGINE=InnoDB;

/* 
Infants Table 
Relationship: eid_infants.facilityID == eid_facilities.id
*/
DROP TABLE IF EXISTS eid_infants;
CREATE TABLE eid_infants (
  id double unsigned NOT NULL AUTO_INCREMENT,
  names varchar(250) NOT NULL,
  infantUniqueID varchar(250) NOT NULL, /* The unique identifier for each infant, matches the EXP Number in the Dried Blood Spot Dispatch Form */
  facilityID mediumint unsigned NOT NULL, /* Matches the Entry Point Clinic in the Dried Blood Spot Dispatch Form */
  gender char(1) NOT NULL, /* M or F */
  dateOfBirth date NOT NULL, /* dateOfBirth is preferable as the current age can be computed based on datediff(dateOfBirth,now()) */
  created datetime NOT NULL, 
  createdby varchar(250) NOT NULL, 
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (facilityID,infantUniqueID),
  KEY infantUniqueIDIndex (infantUniqueID), 
  KEY facilityIDIndex (facilityID) 
) ENGINE=InnoDB;

/*
Caregivers Table
Relationship: eid_infants_caregivers.infantID == eid_infants.id
Assumptions justifying the UNIQUE constraint:
1.	There could be multiple phone numbes provided for an infant's caregiver over time
*/
DROP TABLE IF EXISTS eid_infants_caregivers;
CREATE TABLE eid_infants_caregivers (
  id double unsigned NOT NULL AUTO_INCREMENT,
  infantID double unsigned NOT NULL,
  caregiverPhone varchar(20) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (infantID,caregiverPhone),
  KEY infantIDIndex (infantID) 
) ENGINE=InnoDB;

/*
Infant HIV Status Table
Relationship: eid_infants_hivstatus.infantID == eid_infants.id
Assumptions justifying the UNIQUE constraint:
1.	An infant's HIV Status could be negative today, positive tomorrow
*/
DROP TABLE IF EXISTS eid_infants_hivstatus;
CREATE TABLE eid_infants_hivstatus (
  id double unsigned NOT NULL AUTO_INCREMENT,
  infantID double unsigned NOT NULL,
  hivStatus varchar(20) NOT NULL, /* Negative or Positive */
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (hivStatus,created),
  KEY infantIDIndex (infantID), 
  KEY createdIndex (created) 
) ENGINE=InnoDB;

/* Batches Table */
DROP TABLE IF EXISTS eid_batches;
CREATE TABLE eid_batches (
  id double unsigned NOT NULL AUTO_INCREMENT,
  batchNumber varchar(250) NOT NULL,
  batchComments text NOT NULL,
  senderName varchar(250) NOT NULL,
  senderPhone varchar(250) NOT NULL,
  created datetime NOT NULL, 
  createdby varchar(250) NOT NULL, 
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (batchNumber),
  KEY batchNumberIndex (batchNumber) 
) ENGINE=InnoDB;

/*
Samples Table
Relationships: 
	eid_samples.infantID == eid_infants.id
	eid_samples.motherPMTCTARVsAnteNatalID == eid_appendix_pmtctarvsmothersantenatal.id
	eid_samples.motherPMTCTARVsDeliveryID == eid_appendix_pmtctarvsmothersdelivery.id
	eid_samples.motherPMTCTARVsPostNatalID == eid_appendix_pmtctarvsmotherspostnatal.id
	eid_samples.infantPMTCTARVsID == eid_appendix_pmtctarvsinfants.id
	eid_samples.batchID == eid_batches.id
Assumptions justifying the UNIQUE constraint:
1.	A caregiver could have more than one phone number, 
2.	An infant could have more than one caregiver
*/
DROP TABLE IF EXISTS eid_samples;
CREATE TABLE eid_samples (
  id double unsigned NOT NULL AUTO_INCREMENT,
  infantID double unsigned NOT NULL,
  batchID double unsigned NOT NULL,
  envelopeNumber varchar(50) NOT NULL,
  testingLabNumber varchar(100) NOT NULL, /* Assigned to Sample by the System, this will feed into the worksheet barcode */
  collectionDate date NOT NULL,
  pcr int(1) unsigned NOT NULL,
  nonRoutinePCR varchar(5) NOT NULL,
  breastFeeding char(1) NOT NULL,
  motherPMTCTARVsAnteNatalID mediumint unsigned NOT NULL,
  motherPMTCTARVsDeliveryID mediumint unsigned NOT NULL,
  motherPMTCTARVsPostNatalID mediumint unsigned NOT NULL,
  infantPMTCTARVsID mediumint unsigned NOT NULL,
  batchNumber varchar(100) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (testingLabNumber),
  KEY infantIDIndex (infantID), 
  KEY testingLabNumberIndex (testingLabNumber) 
) ENGINE=InnoDB;

/*
Sample Verification Table
Comment: 
	Table for the approval of samples prior to their inclusion within a worksheet, 
	or their rejection due to a predefined set of reasons for failure
Relationship: 
	eid_samples_verify.sampleID == eid_samples.id
	eid_samples_verify.outcomeRejectionReasonsID == eid_appendix_samplerejectionreason.id
*/
DROP TABLE IF EXISTS eid_samples_verify;
CREATE TABLE eid_samples_verify (
  id double unsigned NOT NULL AUTO_INCREMENT,
  sampleID double unsigned NOT NULL,
  outcome varchar(20) NOT NULL,
  outcomeRejectionReasonsID mediumint unsigned NOT NULL,
  comments text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (sampleID),
  KEY sampleIDIndex (sampleID)
) ENGINE=InnoDB;

/*
Worksheet Credentials Table
Comment: 
	This table contains the names and attributes of the worksheet
	Many of the fields in this table may be removed/changed based on discussions with the EIDLIMS Lab Team
*/
DROP TABLE IF EXISTS eid_samples_worksheetcredentials;
CREATE TABLE eid_samples_worksheetcredentials (
  id double unsigned NOT NULL AUTO_INCREMENT,
  worksheetName varchar(250) NOT NULL,
  worksheetReferenceNumber varchar(250) NOT NULL,
  worksheetType varchar(10) NOT NULL,
  samplePrep varchar(100) NOT NULL,
  samplePrepExpiryDate date NOT NULL,
  bulkLysisBuffer varchar(100) NOT NULL,
  bulkLysisBufferExpiryDate date NOT NULL,
  control varchar(100) NOT NULL,
  controlExpiryDate date NOT NULL,
  calibrator varchar(100) NOT NULL,
  calibratorExpiryDate date NOT NULL,
  amplificationKit varchar(100) NOT NULL,
  amplificationKitExpiryDate date NOT NULL,
  assayDate date NOT NULL,
  includeCalibrators int(1) unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (worksheetName,worksheetReferenceNumber) 
) ENGINE=InnoDB;

/*
Worksheet Samples Table
Comment: 
	Table linking a worksheet to its samples
Relationship: 
	eid_samples_worksheet.sampleID == eid_samples.id
	eid_samples_worksheet.worksheetID == eid_samples_worksheetcredentials.id
*/
DROP TABLE IF EXISTS eid_samples_worksheet;
CREATE TABLE eid_samples_worksheet (
  id double unsigned NOT NULL AUTO_INCREMENT,
  worksheetID double unsigned NOT NULL,
  sampleID double unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (worksheetID,sampleID), 
  KEY worksheetIDIndex (worksheetID),
  KEY sampleIDIndex (sampleID)
) ENGINE=InnoDB;

/*
Results Table
Comment: 
	Table containing results from the Roche system
	Unique Constraints are not enforced because the nature of this table is lab teams tend to
	upload results files into this table more than once
Relationship: 
	eid_results_roche.worksheetID == eid_samples_worksheetcredentials.id
	eid_results_roche.testingLabNumber == eid_samples.testingLabNumber
*/
DROP TABLE IF EXISTS eid_results_roche;
CREATE TABLE eid_results_roche (
  id double unsigned NOT NULL AUTO_INCREMENT,
  worksheetID double unsigned NOT NULL,
  PatientName varchar(100) NOT NULL,
  PatientID varchar(100) NOT NULL,
  OrderNumber varchar(100) NOT NULL,
  OrderDateTime varchar(100) NOT NULL,
  testingLabNumber varchar(100) NOT NULL,
  SampleType varchar(100) NOT NULL,
  BatchID varchar(100) NOT NULL,
  Test varchar(100) NOT NULL,
  Result varchar(100) NOT NULL,
  Unit varchar(100) NOT NULL,
  Flags varchar(100) NOT NULL,
  AcceptedOp varchar(100) NOT NULL,
  AcceptedDateTime varchar(100) NOT NULL,
  Comment varchar(100) NOT NULL,
  GeneralLotNumber varchar(100) NOT NULL,
  GeneralLotExpirationDate varchar(100) NOT NULL,
  SamplePrepKitLotNumber varchar(100) NOT NULL,
  SamplePrepKitLotExpirationDate varchar(100) NOT NULL,
  PCRKitLotNumber varchar(100) NOT NULL,
  PCRKitExpirationDate varchar(100) NOT NULL,
  LPCLowLimit varchar(100) NOT NULL,
  LPCHighLimit varchar(100) NOT NULL,
  MPCLowLimit varchar(100) NOT NULL,
  MPCHighLimit varchar(100) NOT NULL,
  HPCLowLimit varchar(100) NOT NULL,
  HPCHighLimit varchar(100) NOT NULL,
  PreparationInstrumentID varchar(100) NOT NULL,
  PreparationStartDateTime varchar(100) NOT NULL,
  PreparationEndDateTime varchar(100) NOT NULL,
  PreparationRackPos varchar(100) NOT NULL,
  PreparationRackID varchar(100) NOT NULL,
  PreparationRackType varchar(100) NOT NULL,
  PreparationTubeID varchar(100) NOT NULL,
  PreparationTubeType varchar(100) NOT NULL,
  PreparationTubePos varchar(100) NOT NULL,
  PreparationBatchID varchar(100) NOT NULL,
  AmplificationInstrumentID varchar(100) NOT NULL,
  AmplificationStartDateTime varchar(100) NOT NULL,
  AmplificationEndDateTime varchar(100) NOT NULL,
  AmplificationTCID varchar(100) NOT NULL,
  AmplificationRackID varchar(100) NOT NULL,
  AmplificationRackType varchar(100) NOT NULL,
  AmplificationTubeID varchar(100) NOT NULL,
  AmplificationTubeType varchar(100) NOT NULL,
  AmplificationTubePos varchar(100) NOT NULL,
  AmplificationBatchID varchar(100) NOT NULL,
  DetectionInstrumentID varchar(100) NOT NULL,
  DetectionStartDateTime varchar(100) NOT NULL,
  DetectionEndDateTime varchar(100) NOT NULL,
  DetectionRackPos varchar(100) NOT NULL,
  DetectionRackID varchar(100) NOT NULL,
  DetectionRackType varchar(100) NOT NULL,
  DetectionTubeID varchar(100) NOT NULL,
  DetectionTubeType varchar(100) NOT NULL,
  DetectionTubePos varchar(100) NOT NULL,
  DetectionBatchID varchar(100) NOT NULL,
  IngredientCH1 varchar(100) NOT NULL,
  IngredientCH2 varchar(100) NOT NULL,
  IngredientCH3 varchar(100) NOT NULL,
  IngredientCH4 varchar(100) NOT NULL,
  CTMElbowCH1 varchar(100) NOT NULL,
  CTMElbowCH2 varchar(100) NOT NULL,
  CTMElbowCH3 varchar(100) NOT NULL,
  CTMElbowCH4 varchar(100) NOT NULL,
  CTMRFICH1 varchar(100) NOT NULL,
  CTMRFICH2 varchar(100) NOT NULL,
  CTMRFICH3 varchar(100) NOT NULL,
  CTMRFICH4 varchar(100) NOT NULL,
  CTMAFICH1 varchar(100) NOT NULL,
  CTMAFICH2 varchar(100) NOT NULL,
  CTMAFICH3 varchar(100) NOT NULL,
  CTMAFICH4 varchar(100) NOT NULL,
  CTMCalibCoeffa varchar(100) NOT NULL,
  CTMCalibCoeffb varchar(100) NOT NULL,
  CTMCalibCoeffc varchar(100) NOT NULL,
  CTMCalibCoeffd varchar(100) NOT NULL,
  CASampleValue varchar(100) NOT NULL,
  QSCopy varchar(100) NOT NULL,
  CATarget1 varchar(100) NOT NULL,
  CATarget2 varchar(100) NOT NULL,
  CATarget3 varchar(100) NOT NULL,
  CATarget4 varchar(100) NOT NULL,
  CATarget5 varchar(100) NOT NULL,
  CATarget6 varchar(100) NOT NULL,
  CAQS1 varchar(100) NOT NULL,
  CAQS2 varchar(100) NOT NULL,
  CAQS3 varchar(100) NOT NULL,
  CAQS4 varchar(100) NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  KEY testingLabNumberIndex (testingLabNumber),
  KEY worksheetIDIndex (worksheetID)
) ENGINE=InnoDB;

/*
Infants Followup Table
Relationship: 
	eid_infants_followup.facilityID == eid_facilities.id
	eid_infants_followup.infantID == eid_samples.id
*/
DROP TABLE IF EXISTS eid_infants_followup;
CREATE TABLE eid_infants_followup (
  id double unsigned NOT NULL AUTO_INCREMENT,
  facilityID mediumint unsigned NOT NULL, 
  infantID double unsigned NOT NULL,
  testResultsReceived varchar(5) NOT NULL,
  careTakerReturned varchar(5) NOT NULL,
  careTakerComment text NOT NULL,
  careTakerDate date NOT NULL,
  childEnrolledOnART varchar(5) NOT NULL,
  childNotEnrolledOnARTComment text NOT NULL,
  childEnrolledOnARTDate date NOT NULL,
  artNumber varchar(100) NOT NULL,
  childReferred varchar(5) NOT NULL,
  childReferredNewFacility varchar(250) NOT NULL,
  followupAction text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (facilityID,infantID), 
  KEY facilityIDIndex (facilityID),
  KEY infantIDIndex (infantID)
) ENGINE=InnoDB;

/*
Dispatched Results Log Table
Relationship: 
	eid_logs_dispatchedresults.sampleID == eid_samples.id
	eid_logs_dispatchedresults.worksheetID == eid_samples_worksheetcredentials.id
	eid_logs_dispatchedresults.facilityID == eid_facilities.id
*/
DROP TABLE IF EXISTS eid_logs_dispatchedresults;
CREATE TABLE eid_logs_dispatchedresults (
  id double unsigned NOT NULL AUTO_INCREMENT,
  sampleID double unsigned NOT NULL,
  worksheetID double unsigned NOT NULL,
  facilityID mediumint unsigned NOT NULL,
  dateDispatched date NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (facilityID,sampleID,worksheetID), 
  KEY sampleIDIndex (sampleID),
  KEY worksheetIDIndex (worksheetID),
  KEY facilityIDIndex (facilityID)
) ENGINE=InnoDB;

/* 
Page Hits Log Table 
Comment: 
	Table containing the logs for all pagehits.
	This table is relevant for online systems and helps track hits from different countries, users from 
	different countries etc, but may not be entirely necessary for an internal system such as the EIDLIMS
*/
DROP TABLE IF EXISTS eid_logs_pagehits;
CREATE TABLE eid_logs_pagehits (
  id double unsigned NOT NULL AUTO_INCREMENT,
  url text NOT NULL,
  postVariables text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

/* 
Log Table for prints of rejected results
Comment: 
	Table containing the logs for everytime a rejected result was printed (rendered to PDF)
	This table is different from eid_logs_printedresults because rejected samples are logged
	within a different table i.e eid_samples_verify
	There are no unique constraints on sampleID because (from experience) it may be 
	helpful to log each print, even when the sample has already been printed.
Relationship: 
	eid_logs_printedrejectedresults.sampleID == eid_samples.id
*/
DROP TABLE IF EXISTS eid_logs_printedrejectedresults;
CREATE TABLE eid_logs_printedrejectedresults (
  id double unsigned NOT NULL AUTO_INCREMENT,
  sampleID double unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  KEY sampleIDIndex (sampleID)
) ENGINE=InnoDB;

/* 
Log Table for prints of results
Comment: 
	Table containing the logs for everytime a result was printed (rendered to PDF)
	There are no unique constraints on sampleID because (from experience) it may be 
	helpful to log each print, even when the sample has already been printed.
Relationship: 
	eid_logs_printedresults.sampleID == eid_samples.id
	eid_logs_printedresults.worksheetID == eid_samples_worksheetcredentials.id
*/
DROP TABLE IF EXISTS eid_logs_printedresults;
CREATE TABLE eid_logs_printedresults (
  id double unsigned NOT NULL AUTO_INCREMENT,
  sampleID double unsigned NOT NULL,
  worksheetID double unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  KEY sampleIDIndex (sampleID),
  KEY worksheetIDIndex (worksheetID)
) ENGINE=InnoDB;

/* 
Log Table for removal of information
Comment: 
	Table for the logging of data removals (queries such as "delete from eid_samples where id=****)
	Logging of removals is vital for audit purposes
*/
DROP TABLE IF EXISTS eid_logs_removals;
CREATE TABLE eid_logs_removals (
  id double unsigned NOT NULL AUTO_INCREMENT,
  sqlQuery varchar(100) NOT NULL,
  removedData text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

/* 
Log Table for repeats of samples
Comment: 
	Table logging repeats of samples tests.
	This may apply to positive tests where the sample is mandatorily repeated to confirm
	whether the infant is positive.
Relationship: 
	eid_logs_samplerepeats.sampleID == eid_samples.id
	eid_logs_samplerepeats.oldWorksheetID == eid_samples_worksheetcredentials.id
	eid_logs_samplerepeats.withWorksheetID == eid_samples_worksheetcredentials.id
*/
DROP TABLE IF EXISTS eid_logs_samplerepeats;
CREATE TABLE eid_logs_samplerepeats (
  id double unsigned NOT NULL AUTO_INCREMENT,
  sampleID double unsigned NOT NULL,
  oldWorksheetID double unsigned NOT NULL,
  repeatedOn datetime NOT NULL,
  withWorksheetID double unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  KEY sampleIDIndex (sampleID),
  KEY oldWorksheetIDIndex (oldWorksheetID)
) ENGINE=InnoDB;

/* 
Log Table for searches conducted on the system
Comment: 
	Table logging for all searches conducted on the system
	This table will likely change based on how the search is designed
*/
DROP TABLE IF EXISTS eid_logs_searches;
CREATE TABLE eid_logs_searches (
  id double unsigned NOT NULL AUTO_INCREMENT,
  searchQuery text NOT NULL,
  url text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;


/* 
Log Table for data changes across the system
Comment: 
	Table logging all modifications of data within the system
*/
DROP TABLE IF EXISTS eid_logs_tables;
CREATE TABLE eid_logs_tables (
  id double unsigned NOT NULL AUTO_INCREMENT,
  tableName varchar(100) NOT NULL,
  fieldName varchar(100) NOT NULL,
  fieldID double unsigned NOT NULL,
  fieldValueOld text NOT NULL,
  fieldValueNew text NOT NULL,
  url text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

/* 
Log Table for Prints of Worksheets
Comment: 
	Table logging prints of worksheets
Relationship: 
	eid_logs_worksheetsamplesprinted.sampleID == eid_samples.id
	eid_logs_worksheetsamplesprinted.worksheetID == eid_samples_worksheetcredentials.id
*/
DROP TABLE IF EXISTS eid_logs_worksheetsamplesprinted;
CREATE TABLE eid_logs_worksheetsamplesprinted (
  id double unsigned NOT NULL AUTO_INCREMENT,
  worksheetID double unsigned NOT NULL,
  sampleID double unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  KEY worksheetIDIndex (worksheetID),
  KEY sampleIDIndex (sampleID)
) ENGINE=InnoDB;

/* 
Log Table for Views of Worksheets
Comment: 
	Table logging views (previews) of worksheets
Relationship: 
	eid_logs_worksheetsamplesviewed.sampleID == eid_samples.id
	eid_logs_worksheetsamplesviewed.worksheetID == eid_samples_worksheetcredentials.id
*/
DROP TABLE IF EXISTS eid_logs_worksheetsamplesviewed;
CREATE TABLE eid_logs_worksheetsamplesviewed (
  id double unsigned NOT NULL AUTO_INCREMENT,
  worksheetID double unsigned NOT NULL,
  sampleID double unsigned NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  KEY worksheetIDIndex (worksheetID),
  KEY sampleIDIndex (sampleID)
) ENGINE=InnoDB;

/* 
Administrative User Table
Comment: 
	Table facilitating administrator access to the /admin component of the system
*/
DROP TABLE IF EXISTS eid_admins;
CREATE TABLE eid_admins (
  id tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  username varchar(250) NOT NULL,
  password text NOT NULL,
  email varchar(250) NOT NULL,
  phone varchar(255) NOT NULL,
  lastLogin datetime NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (username) 
) ENGINE=InnoDB;

/* 
Users Login Table
Comment: 
	Table facilitating user access to the system
*/
DROP TABLE IF EXISTS eid_users;
CREATE TABLE eid_users (
  id double unsigned NOT NULL AUTO_INCREMENT,
  names varchar(250) NOT NULL,
  phone varchar(250) NOT NULL,
  email varchar(250) NOT NULL,
  password text NOT NULL,
  lastLogin datetime NOT NULL,
  active int(1) unsigned NOT NULL DEFAULT '1',
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (email),
  KEY emailIndex (email) 
) ENGINE=InnoDB;

/* 
Users Login Table
Comment: 
	Table through which, new passwords are verified i.e your password must not
	be similar to any of your last 5 passwords
Relationships: 
	eid_users_history.userID == eid_users.id
*/
DROP TABLE IF EXISTS eid_users_history;
CREATE TABLE eid_users_history (
  id double unsigned NOT NULL AUTO_INCREMENT,
  userID double unsigned NOT NULL,
  historicalPassword text NOT NULL,
  created datetime NOT NULL,
  createdby varchar(250) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniqueIndex (userID),
  KEY userIDIndex (userID) 
) ENGINE=InnoDB;