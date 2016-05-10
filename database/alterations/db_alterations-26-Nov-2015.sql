
/* 
	Yesterday we added these 2 columns with a data-type of JSON.
	Laravel's database wrappers are choking on that "unknown" data-type.
	So, i've converted them to text columns.
 */
alter table batches change printed_PCR_results printed_PCR_results varchar(510) default null;
alter table batches change printed_SCD_results printed_SCD_results varchar(510) default null;
