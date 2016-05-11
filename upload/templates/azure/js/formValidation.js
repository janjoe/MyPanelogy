/* Registration Form validation starts here  */


$( "#registerfrm" ).submit(function( event ) {
	event.preventDefault();
  	var tempfn    = 0;
	var templn    = 0;
	var tempe     = 0;
	var tempp     = 0;
	var tempC     = 0;
	var tempEmp   = 0;  
	var tempTitle = 0;
	var tempIT    = 0;  
	var tempInd   = 0;  
	var tempNE    = 0;    
	var tempCR    = 0;
	var tempA     = 0;    
	
	
	var a =$("#reg_fname").val();
	if(a=="")
	{
		$("#reg_fname_error").css("display","block");
		$("#reg_fname_error").html("This field is required");
		tempfn=1;
	}
	else if(!isNaN(a))
	{
		$("#reg_fname_error").css("display","block");
		$("#reg_fname_error").html("This field is required");
		tempfn=1;
	}
	else
	{
		$("#reg_fname_error").css("display","none");
		$("#reg_fname_error").html("");
		tempfn=0;
	}
	
	var a =$("#reg_lname").val();
	if(a=="")
	{
		$("#reg_lname_error").css("display","block");
		$("#reg_lname_error").html("This field is required");
		templn=1;
	}
	else if(!isNaN(a))
	{
		$("#reg_lname_error").css("display","block");
		$("#reg_lname_error").html("This field is required");
		templn=1;
	}
	else
	{
		$("#reg_lname_error").css("display","none");
		$("#reg_lname_error").html("");
		templn=0;
	}
	
	var a =$("#reg_email").val();
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	if(a=="")
	{
		$("#reg_email_error").css("display","block");
		$("#reg_email_error").html("This field is required");
		tempe=1;
	}
	else if (!filter.test(a)) 
	{
		$("#reg_email_error").css("display","block");
		$("#reg_email_error").html("Please enter valid email");
		tempe=1;
    	}
	else
	{
		$("#reg_email_error").css("display","none");
		$("#reg_email_error").html("");
		tempe=0;
	}
	
	var a =$("#reg_password").val();
	var len = a.length;
	if(a=="")
	{
		$("#reg_password_error").css("display","block");
		$("#reg_password_error").html("This field is required");
		tempp=1;
	}
	else if(len < 8 || len > 13)
	{
		if(len < 8)
		{
			$('#reg_password_error').css('display', 'block');
			$('#reg_password_error').html('Minimum 8 characters required');
			tempp=1;
		}
		else
		{
			$("#reg_password_error").css("display","none");
			tempp=0;
		}
	}
	else
	{
		$("#reg_password_error").css("display","none");
		$("#reg_password_error").html("");
		tempp=0;
	}
	
	var a =$("#reg_country").val();
	if(a=="")
	{
		$("#reg_country_error").css("display","block");
		$("#reg_country_error").html("This field is required");
		tempC=1;
	}
	else
	{
		$("#reg_country_error").css("display","none");
		$("#reg_country_error").html("");
		tempC=0;
	}
	
	var a =$("#reg_emp_status").val();
	if(a=="")
	{
		$("#reg_emp_status_error").css("display","block");
		$("#reg_emp_status_error").html("This field is required");
		tempEmp=1;
	}
	else if(!isNaN(a))
	{
		$("#reg_emp_status_error").css("display","block");
		$("#reg_emp_status_error").html("This field is required");
		tempEmp=1;
	}
	else
	{
		$("#reg_emp_status_error").css("display","none");
		$("#reg_emp_status_error").html("");
		tempEmp=0;
	}
	
	
	var a =$("#reg_role").val();
	if(a=="")
	{
		$("#reg_role_error").css("display","block");
		$("#reg_role_error").html("This field is required");
		tempTitle=1;
	}
	else if(!isNaN(a))
	{
		$("#reg_role_error").css("display","block");
		$("#reg_role_error").html("This field is required");
		tempTitle=1;
	}
	else
	{
		$("#reg_role_error").css("display","none");
		$("#reg_role_error").html("");
		tempTitle=0;
	}
	
	var a =$("#reg_IT").val();
	if(a=="")
	{
		$("#reg_IT_error").css("display","block");
		$("#reg_IT_error").html("This field is required");
		tempIT=1;
	}
	else if(!isNaN(a))
	{
		$("#reg_IT_error").css("display","block");
		$("#reg_IT_error").html("This field is required");
		tempIT=1;
	}
	else
	{
		$("#reg_IT_error").css("display","none");
		$("#reg_IT_error").html("");
		tempIT=0;
	}
	
	var a =$("#reg_industry").val();
	if(a=="")
	{
		$("#reg_industry_error").css("display","block");
		$("#reg_industry_error").html("This field is required");
		tempInd=1;
	}
	else if(!isNaN(a))
	{
		$("#reg_industry_error").css("display","block");
		$("#reg_industry_error").html("This field is required");
		tempInd=1;
	}
	else
	{
		$("#reg_industry_error").css("display","none");
		$("#reg_industry_error").html("");
		tempInd=0;
	}
	
	var a =$("#reg_num_employee").val();
	if(a=="")
	{
		$("#reg_num_employee_error").css("display","block");
		$("#reg_num_employee_error").html("This field is required");
		tempNE=1;
	}
	else if(isNaN(a))
	{
		$("#reg_num_employee_error").css("display","block");
		$("#reg_num_employee_error").html("This field is required");
		tempNE=1;
	}
	else
	{
		$("#reg_num_employee_error").css("display","none");
		$("#reg_num_employee_error").html("");
		tempNE=0;
	}
	
	var a =$("#reg_company_revenue").val();
	if(a=="")
	{
		$("#reg_company_revenue_error").css("display","block");
		$("#reg_company_revenue_error").html("This field is required");
		tempCR=1;
	}
	else if(isNaN(a))
	{
		$("#reg_company_revenue_error").css("display","block");
		$("#reg_company_revenue_error").html("This field is required");
		tempCR=1;
	}
	else
	{
		$("#reg_company_revenue_error").css("display","none");
		$("#reg_company_revenue_error").html("");
		tempCR=0;
	}
	
	if($("#agrre").is(":checked"))
	{
 		$('#agrre_error').removeClass('error');
        tempA=0;
	}
	else 
    {
    	$('#agrre_error').addClass('error');
		tempA=1;
    }
	
	if(tempfn==0 && templn==0 && tempe==0 && tempp==0 && tempC==0 && tempEmp==0 && tempTitle==0 && tempIT==0 && tempInd==0 && tempNE==0 && tempCR==0)
	{
		$.post( "registration.php", { 
			reg_fname:$("#reg_fname").val(), 
			reg_lname:$("#reg_lname").val(), 
			reg_email: $("#reg_email").val(), 
			reg_password: $("#reg_password").val(), 
			reg_country: $("#reg_country").val(), 
			reg_emp_status: $("#reg_emp_status").val(), 
			reg_role: $("#reg_role").val(), 
			reg_IT: $("#reg_IT").val(), 
			reg_industry: $("#reg_industry").val(), 
			reg_num_employee: $("#reg_num_employee").val(), 
			reg_company_revenue: $("#reg_company_revenue").val()
		})
		.done(function( data ) {
			if(data==1)	{
  				$( "#Success").show();
  				$("#registerfrm")[0].reset();
  				setTimeout(function() {
        			$( "#Success").hide();
    			}, 3000);
			}
			else{
				$( "#Error").show();
				$("#registerfrm")[0].reset();
				setTimeout(function() {
        			$( "#Error").hide();
    			}, 3000);
			}
		});
	}
});


/* Registration Form validation ends here  */