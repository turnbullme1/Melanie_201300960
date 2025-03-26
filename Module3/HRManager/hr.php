<?php
require("model/functions.php");
 
$action = filter_input(INPUT_POST, 'action');
if($action == NULL)
	$action = filter_input(INPUT_GET, 'action');



if ($action == "Home")
{ //display hr.php default view
  //no input to capture
	view: include("view/default_view.php");
}
elseif ($action == "All Employees")
{  //display employee list 
   //no input to capture
  $employees = //SELECT * FROM employees;
  $empList = createEmpList($employees);
 include("view/emplist.php");
}
if ($action == "All Departments")
{  //no data to capture
	$departments = // SELECT * FROM departments;
	$deptList = createDeptList($departments);
	include("view/deptlist.php");
}
elseif ($action == "Find Employees by Name")
{ //capture 'searchname'
  //process data
  $employees = findEmployeeByName($searchname);
  $empList = createEmpList($employees);
  include("view/emplist.php");
}
elseif ($action == "Find Employees by Department")
{ //capture 'dept_id'
  $employees = findEmpByDept($dept_id);
  //SELECT * from employees where dept_id = dept_id (use placeholder);
  $empList = createEmpList($employees);
  include("view/emplist.php");
}
elseif ($action == "View Dept")
{ // display one departments details when dept link clicked

}
elseif ($action == "View Employee")
{ // display an employee when their email is clicked

}