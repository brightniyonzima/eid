/* step 1: undo */
update dbs_samples set ready_for_SCD_test = 'TEST_ALREADY_DONE' 
	where ready_for_SCD_test = 'YES' and SCD_test_result is null and id in (
711504, 713108, 713330, 714064, 714066, 714125, 717894, 717900, 718547, 718550, 718579, 718583, 718649, 718664, 719990, 719993, 720131, 720155, 720161, 720490, 720500, 720533, 720548, 720884, 722397, 724427, 724708, 724828, 724867, 725102, 726232, 726451, 727465, 727782, 727808, 727861, 727985, 727986, 727990, 727992, 728013, 728023, 728024, 728026, 728077, 728078, 728079, 728080, 728083, 728270, 728406, 728922, 729463, 729465, 729468, 729471, 729590, 729795, 729841, 729847, 729872, 730032, 730034, 730093, 730238, 730338, 730732, 730733, 730748, 730754, 730763, 731070, 731113, 731114, 731115, 731116, 731117, 731118, 731119, 731120, 731125, 731126, 731127, 731128, 731130, 731131, 731132, 731133, 731140, 731143, 731146, 731151, 731153, 731155, 731156, 731158, 731173, 731174, 731377, 731530, 731599, 731607, 731733, 732060, 732066, 732067, 732068, 732069, 732079, 732080, 732081, 732082, 732083, 732084, 732085, 732088, 732091, 732093, 732095, 732098, 732099, 732100, 732178, 732183, 732184, 732187, 732188, 732190, 732194, 732196, 732200, 732201, 732208, 732211, 732213, 732214, 732217, 732218, 732221, 732229, 732236, 732240, 732243, 732244, 732245, 732247, 732260, 732264, 732268, 732270, 732273, 732274, 732279, 732282, 732287, 732288, 732289, 732290, 732292, 732295, 732311, 732313, 732319, 732320, 732321, 732324, 732327, 732330, 732332, 732335, 732345, 732346, 732349, 732374, 732375, 732382, 732387, 732388, 732413, 732414, 732415, 732417, 732427, 732429, 732430, 732436, 732437, 732438, 732440, 732452, 732457, 732458, 732459, 732482, 732483, 732484, 732485, 732486, 732488, 732490, 732493, 732494, 732497, 732499, 732501, 732502, 732512, 732515, 732516, 732518, 732536, 732697, 732815, 732816, 732818, 732819, 732820, 732821, 732823, 732824, 732826, 732827, 732829, 732830, 732831, 732833, 732834, 732835, 732836, 732837, 732838, 732839, 732840, 732841, 732842, 732843, 732844, 732867, 732884, 732977, 732981, 732984, 732986, 732987, 732989, 732994, 732997, 732998, 732999, 733008, 733013, 733018, 733019, 733021, 733024, 733036, 733039, 733055, 733060, 733068, 733078, 733085, 733091, 733092, 733093, 733096, 733097, 733100, 733104, 733107, 733109, 733110, 733116, 733136, 733138, 733140, 733141, 733147, 733150, 733154, 733155, 733158, 733161, 733180, 733209, 733235, 733237, 733238, 733239, 733240, 733247, 733249, 733261, 733266, 733274, 733275, 733279, 733282, 733283, 733295, 733299, 733306, 733309, 733318, 733331, 733379, 733380, 733381, 733382, 733383, 733384, 733385, 733386, 733387, 733388, 733389, 733390, 733391, 733394, 733396, 733397, 733398, 733399, 733400, 733401, 733402, 733403, 733404, 733406, 733407, 733408, 733409, 733411, 733412, 733413, 733421, 733422, 733425, 733428, 733443, 733444, 733445, 733446, 733447, 733467, 733468, 733469, 733470, 733471, 733472, 733473, 733474, 733475, 733476, 733477, 733478, 733479, 733480, 733481, 733482, 733483, 733484, 733485, 733486, 733487, 733489, 733490, 733491, 733492, 733493, 733494, 733495, 733496, 733497, 733498, 733499, 733500, 733501, 733502, 733518, 733519, 733521, 733527, 733550, 733555, 733556, 733557, 733559, 733561, 733563, 733565, 733569, 733592, 733677, 733695, 733696, 733709, 733710, 733711, 733712, 733714, 733715, 733716, 733718, 733755, 733756, 733782, 733783, 733784, 733788, 733789, 733790, 733791, 733803, 733804, 733819, 733828, 733854, 733858, 733863, 733875, 733905, 733917, 733922, 733924, 733925, 733927, 733936, 733937, 733940, 733944, 733947, 733950, 733954, 733955, 733956, 733963, 733965, 733976, 733977, 733978, 733979, 733981, 733983, 733984, 733985, 733988, 733994, 733998, 734000, 734001, 734004, 734012, 734014, 734017, 734018, 734020, 734022, 734023, 734035, 734036, 734040, 734044, 734046, 734048, 734049, 734050, 734051, 734058, 734060, 734061, 734063, 734065, 734070, 734071, 734073, 734076, 734078, 734081, 734082, 734083, 734084, 734085, 734091, 734094, 734099, 734100, 734102, 734107, 734110, 734112, 734118, 734125, 734131, 734139, 734140, 734145, 734146, 734153, 734155, 734157, 734158, 734161, 734162, 734168, 734179, 734181, 734183, 734184, 734186, 734188, 734189, 734190, 734191, 734201, 734209, 734212, 734214, 734217, 734240, 734257, 734258, 734260, 734261, 734262, 734264, 734265, 734279, 734280, 734281, 734282, 734296, 734318, 734321, 734330, 734343, 734355, 734361, 734364, 734365, 734373, 734374, 734388, 734393, 734394, 734396, 734397, 734398, 734399, 734401, 734402, 734403, 734406, 734407, 734410, 734415, 734417, 734418, 734422, 734423, 734427, 734429, 734431, 734432, 734436, 734438, 734441, 734444, 734449, 734451, 734455, 734456, 734458, 734460, 734463, 734466, 734468, 734471, 734473, 734474, 734478, 734479, 734480, 734481, 734483, 734486, 734488, 734489, 734492, 734494, 734495, 734496, 734497, 734498, 734499, 734500, 734502, 734506, 734509, 734510, 734517, 734518, 734519, 734520, 734521, 734523, 734526, 734527, 734528, 734534, 734538, 734540, 734541, 734544, 734548, 734550, 734553, 734554, 734555, 734557, 734558, 734562, 734563, 734565, 734566, 734568, 734569, 734574, 734578, 734580, 734581, 734584, 734585, 734586, 734588, 734589, 734590, 734591, 734594, 734595, 734597, 734600, 734602, 734604, 734606, 734607, 734608, 734609, 734612, 734615, 734619, 734620, 734621, 734622, 734623, 734624, 734625, 734627, 734628, 734630, 734631, 734632, 734634, 734635, 734636, 734638, 734639, 734643, 734644, 734646, 734649, 734651, 734652, 734655, 734656, 734657, 734658, 734661, 734664, 734665, 734666, 734667, 734670, 734671, 734672, 734673, 734675, 734678, 734682, 734684, 734686, 734688, 734689, 734692, 734693, 734697, 734700, 734704, 734705, 734708, 734709, 734733, 734737, 734738, 734739, 734741, 734744, 734748, 734750, 734753, 734756, 734759, 734760, 734761, 734763, 734766, 734767, 734770, 734771, 734773, 734774, 734775, 734777, 734778, 734782, 734784, 734785, 734795, 734796, 734798, 734799, 734800, 734801, 734804, 734805, 734808, 734809, 734810, 734813, 734818, 734819, 734820, 734822, 734823, 734824, 734825, 734826, 734830, 734836, 734842, 734849, 734871, 734873, 734875, 734878, 734881, 734883, 734885, 734887, 734889, 734891, 734893, 734894, 734896, 734897, 734898, 734900, 734901, 734902, 734903, 734904, 734905, 734906, 734909, 734910, 734913, 734914, 734916, 734917, 734919, 734920, 734923, 734924, 734925, 734926, 734932, 734941, 734944, 734945, 734951, 734953, 734954, 734955, 734957, 734958, 734959, 734960, 734963, 734975, 734976, 734977, 734978, 734979, 734980, 734981, 734983, 734984, 734985, 734986, 734987, 734988, 734989, 734990, 734991, 734992, 734993, 734994, 734995, 734996, 734997, 734998, 734999, 735000, 735004, 735005, 735008, 735009, 735015, 735016, 735022, 735033, 735034, 735035, 735039, 735044, 735046, 735048, 735051, 735053, 735077, 735078, 735079, 735085, 735089, 735091, 735092, 735093, 735095, 735098, 735099, 735101, 735102, 735103, 735105, 735107, 735109, 735110, 735111, 735112, 735114, 735115, 735116, 735118, 735120, 735121, 735122, 735123, 735125, 735126, 735128, 735130, 735142, 735146, 735154, 735254, 735255, 735256, 735257, 735258
);


/* 	
	Step 2a)
	Make sure all missing sickle cell results are copied into dbs_samples from sc_worksheet_index 
	We can identify them later based on their `date_results_entered`
*/
UPDATE dbs_samples JOIN sc_worksheet_index ON sample_id = dbs_samples.id 
	SET SCD_test_result = tie_break_result, 
		date_results_entered = coalesce(date_results_entered, '1906-06-16'), 
		SCD_results_ReleasedBy = coalesce(SCD_results_ReleasedBy, 55) 
	WHERE  	SCD_test_result is null 
	AND 	tie_break_result not IN ('INVALID', 'SICKLER.TEST_AGAIN', 'LEFT_BLANK');

/* 
	Step 2b)
	This fixes Eron issue: if SCD_results_ReleasedBy is null, result wont print. 
*/
UPDATE dbs_samples 
        SET SCD_results_ReleasedBy = 55,  /* 55 = Steven Aeko, Sickle Cell Lab */
                date_results_entered = if(date_results_entered, date_results_entered, '2015-11-02' )
        WHERE   SCD_results_ReleasedBy is null 
        AND     SCD_test_result is not null; 

/* Step 3: redo */
update dbs_samples set ready_for_SCD_test ='YES' where ready_for_SCD_test = 'TEST_ALREADY_DONE' and SCD_test_result is null;


/* 	
	Step 4)
	Without sickle_cell_release_code, the worksheet creation algorithm files them under location "unknown_loc" 
*/
UPDATE dbs_samples set sickle_cell_release_code = 'MANUAL'  
	WHERE 	sickle_cell_release_code is null 
	AND 	(SCD_test_requested = 'YES' and PCR_test_requested = 'NO') 
	AND 	ready_for_SCD_test = 'YES';



/*  --------------------- un-related to the above ----------------- */
/*	Make sure PCR_results_ReleasedBy != SCD_results_ReleasedBy since 
	that messes up printing of results with both tests 
		See .env.example for details					
*/

/* create the sys_rejector */
INSERT INTO `users` 
    (`id`, `username`, `password`, `type`, `is_admin`, `family_name`, `other_name`, `signature`, `email`, `telephone`, `telephone2`, `organization_id`, `facilityID`, `hubID`, `ipID`, `remember_token`, `deactivated`, `loggedon`, `created`, `createdby`) VALUES 
  VALUES
    (102,'sysrejector','$2y$10$gd.4hf5ZoR9TUUEXUuU7luUjUHhFF4vB.qwHTki/Jsa5BWyvZUqze','1',0,'System','Rejector','/uploads/signs/sysrejector.','see_usage_when_all_samples_rejected@cphl.ug','+256 791 213 437 ',NULL,NULL,NULL,NULL,NULL,NULL,0,0,'2015-12-22 09:04:04','admin');

/* use the sys_rejector */
update dbs_samples set SCD_results_ReleasedBy=102 where PCR_results_ReleasedBy = SCD_results_ReleasedBy;
