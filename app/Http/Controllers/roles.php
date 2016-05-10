<?php


    public function can($permission, $requireAll = false)
    {
        if (is_array($permission)) {

			return  checkManyPermissions($permission, $requireAll);
        } 

        elseif (is_string($permission)) {

        	return 	checkOnePermission($permission);
        }

        else return false;
    }


	public function checkManyPermissions($permission, $requireAll)
	{

        foreach ($permission as $this_permission) {

        	$has_this_permission = checkOnePermission($this_permission);

            if ($has_this_permission && !$requireAll) {
                return true;
            } 
            elseif (!$has_this_permission && $requireAll) {
                return false;
            }
        }

        // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
        // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
        // Return the value of $requireAll;
        return $requireAll;
	}

	public function checkOnePermission($permission)
	{

		if(array_search($permission, $this->permissions() ) === false)
			return false;// no permission
		else
			return true;
	}

/*
	1)	Create a migration for temporary_permissions.
			- 	It's a many-to-many relationship between users and permissions with 
				an extra field for expiry_date_time

	2)	Create a permissions() function on the User model that returns a list of all
				permissions the current user has at this very time.
			-	It does a SQL join/union on permissions, temporary_permissions and roles.
			- 	It takes care to exclude expired permissions


	3)	Assign middleware to each of your routes in routes.php
			- You can do 1 middleware for all routes [my preference] ... OR...
			- You can do a different middleware for each route

	4)	Create a middleware to authenticate all our routes before they are executed.
			- The steps below are done in the handle() method of your middleware.
			- The handle() method always has the current instance of the HTTP Request class (courtesy of Laravel)
				- step1: check the Http Request class to find out which route you are currently working on.
				- step2: Check permissions using $current_user->can() then grant/deny access accordingly
			


Usage tips:
	- Use composer to get Zizaco's Entrust package	and include it in your Laravel App
	- The code above should be added into the class with your User Model
	- Execute step 1 to 4 in a manner that makes sense for your situation

Good Background Reading Materials:
	-  Read the section called "Defining Middleware" in the Laravel 5 manual.
	-  Read the section called "Protecting Routes" in the Laravel 5 manual.
	-  Read the documentation for Zizaco's Entrust package at his GitHub page
			{					
				- Our finished product should work as described in his manual.
				- We need his module in our Middleware (it does the authentication)
				- The main difference between his and ours is that ours can 
					do temporary authentication (give someone certain permissions for a short time)
			}


Thoughts on self-registration:
====================================
Imagine the head of an IP wishes to create himself an account on the system. How would he go about it?
	- Lets discuss.
	- I think he'd need to register himself as usual, but 
		the account would not only have public access until we manually confirm the request.
	- How do we prevent bad guys from fradulently creating accounts?



*/