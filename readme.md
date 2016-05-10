# OVERVIEW
The Early Infant Diagnosis (EID) Lab Information Management System (LIMS), also known as EIDLIMS, is a 
web-based business intelligence tool built for the Central Public Health Lab (CPHL) in Uganda. 

The system is hosted in an internal data center and can be accessed with a web browser within the
CPHL network. In the future it is expected that health facilities other stakeholders will be granted
remote access to the parts of the system that concern them directly. Some features to support this have already been built.

# PURPOSE
This system was built to organize and automate the workflow of a central testing lab into a series of steps which enable a lab testing hundreds or thousands of samples a day to process each of them and allocate results with very few (if any) errors.

For simplicity, the lab's method of work can be likened to a factory's assembly line: 

	There are several tasks to do and each person does one task and passes it to the next person. 
	Adam Smith calls this specialization (division of labor). 
	
	For example, one person enters the patient data, the next verifies that data was entered correctly, 
	and a third person does the actual blood test. Once the results are ready, someone else prints them and mails them to the patients.

Because this has to be done for several thousand patients a week and hundreds of hospitals across the whole country, having  a computerized system essentially "directs traffic" thereby making sure that when one person completes a task, it automatically goes onto the next person's to-do list.

This minimizes error and enables detailed step-by-step or task-by-task audit at any point in the future.


# TECHNOLOGIES USED
|---------|---------------|-------
|Component| Role          | Minimum Version
|---------|---------------| ------
| PHP | Programming Language | PHP 5.5
|[Laravel](https://laravel.com/)   | Web Framework | Laravel 5
|[MySQL](https://www.mysql.com)   | Database      | MySQL 5.7.8
|[Apache](https://httpd.apache.org/)    | Web Server | Apache 2


# INSTALLATION - PREREQUISITES

### Installation Option 1: For development (or if you use Windows or Mac)
This option involves installing a virtual machine. It will take an hour or two but its truly worth it! 
Simple instructions here => https://gist.github.com/JeffreyWay/af0ee7311abfde3e3b73
Skip step 2 and step 3 in the instructions. Instead simply cd to your desired directory in the Terminal, and run this command:
```
curl -L -o 'install.sh' http://bit.ly/1hBfq57 && curl -L -o 'Vagrantfile' http://bit.ly/1mE3Qt9 && vagrant up
```

### Installation Option 2: For Production (or if you use Linux)

Step 1: [Install a LAMP stack] 
(https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-14-04)

Step 2: [Install Laravel] (https://laravel.com/docs/master)


# INSTALLATION - EIDLIMS

Run the following commands in a suitable directory (e.g. Apache's docRoot)

```
git clone https://github.com/CHAIUganda/eidrevamp.git
cd eidrevamp
composer install
```


## Dependencies
Once that is working, you need to set up PDF printing.
Install wkhtmltopdf from [http://wkhtmltopdf.org/](http://wkhtmltopdf.org/)

It is used by [https://github.com/barryvdh/laravel-snappy](https://github.com/barryvdh/laravel-snappy) to enable high resolution printing of barcodes that the system depends on to identify patients' blood samples.

## Testing
If you wish to run the various tests available for this software, then you may need to install PHP unit...

```
https://phpunit.de/getting-started.html
```

... and upgrade to PHP 5.6 or newer.
```
http://www.dev-metal.com/install-setup-php-5-6-ubuntu-14-04-lts/
```

# CONTRIBUTORS
Geoffrey Mimano soyfactor AT gmail DOT com
Richard Obore oborerichard AT gmail DOT com
Paul Kitutu pkitutu AT clintonhealthaccess DOT org
