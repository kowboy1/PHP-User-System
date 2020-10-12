
// settings
if (window.location.href.indexOf("settings.php") > -1) {
  var menuSettingsBtns = document.querySelectorAll(".nav-tabs > li > a");
  menuSettingsBtns[0].parentNode.classList.add("active");
  
  for (let i = 0; i < menuSettingsBtns.length; i++) {
    
    menuSettingsBtns[i].addEventListener("click", function(){
      
      document.querySelector('.nav-tabs .dropdown-toggle').parentNode.classList.remove("active");
      document.querySelector('.nav-tabs .dropdown-toggle').parentNode.classList.remove("open");
      
      if ((menuSettingsBtns[i].href.substring(menuSettingsBtns[i].href.lastIndexOf('#') + 1)).length !== 0){
        
        var emailBtns = document.querySelectorAll(".nav-tabs > li > ul a");
        
        for (let l = 0; l < emailBtns.length; l++) {
          if (l !== 2) {
            document.querySelector('.tab-content #' + emailBtns[l].href.substring(emailBtns[l].href.lastIndexOf('#') + 1)).classList.add('fade');
            document.querySelector('.tab-content #' + emailBtns[l].href.substring(emailBtns[l].href.lastIndexOf('#') + 1)).classList.remove('display');
          }
        }
        
        for (let j = 0; j < menuSettingsBtns.length; j++) {
          if (i !== j) {
            if ((menuSettingsBtns[j].href.substring(menuSettingsBtns[j].href.lastIndexOf('#') + 1)).length != 0) {
              document.querySelector('.tab-content #' + menuSettingsBtns[j].href.substring(menuSettingsBtns[j].href.lastIndexOf('#') + 1)).classList.add('fade');
              document.querySelector('.tab-content #' + menuSettingsBtns[j].href.substring(menuSettingsBtns[j].href.lastIndexOf('#') + 1)).classList.remove('display');
              
              menuSettingsBtns[j].parentNode.classList.remove('active');
              
            }
          }
        }
        
        document.querySelector('.tab-content #' + menuSettingsBtns[i].href.substring(menuSettingsBtns[i].href.lastIndexOf('#') + 1)).classList.remove('fade');
        document.querySelector('.tab-content #' + menuSettingsBtns[i].href.substring(menuSettingsBtns[i].href.lastIndexOf('#') + 1)).classList.add('display');
        
        menuSettingsBtns[i].parentNode.classList.add('active');
        
      } else {
        
        menuSettingsBtns[i].parentNode.classList.add("open");
        
        var emailBtns = document.querySelectorAll(".nav-tabs > li > ul a");
        
        for (let l = 0; l < emailBtns.length; l++) {
          emailBtns[l].addEventListener("click", function(){
            
            menuSettingsBtns[i].parentNode.classList.remove("open");
            
            var allMenuBtns = document.querySelectorAll('.nav-tabs a')
            
            for (let m = 0; m < menuSettingsBtns.length; m++) {
              menuSettingsBtns[m].parentNode.classList.remove("active");
            }
            
            for (let j = 0; j < allMenuBtns.length; j++) {
              if (j !== 2) {
                document.querySelector('.tab-content #' + allMenuBtns[j].href.substring(allMenuBtns[j].href.lastIndexOf('#') + 1)).classList.add('fade');
                document.querySelector('.tab-content #' + allMenuBtns[j].href.substring(allMenuBtns[j].href.lastIndexOf('#') + 1)).classList.remove('display');
              }
            }
            
            menuSettingsBtns[2].parentNode.classList.add("active");
            
            document.querySelector('.tab-content #' + emailBtns[l].href.substring(emailBtns[l].href.lastIndexOf('#') + 1)).classList.remove('fade');
            document.querySelector('.tab-content #' + emailBtns[l].href.substring(emailBtns[l].href.lastIndexOf('#') + 1)).classList.add('display');
            
          });
        }
      }
    });
  }
} else if (window.location.href.indexOf("index.php") > -1){
  
  // control page
  var menuBtns = document.querySelectorAll(".nav-tabs a");
  
  if (menuBtns) {
    menuBtns[0].parentNode.classList.add('active');
    
    for (let i = 0; i < menuBtns.length - 1; i++) {
      
      menuBtns[i].addEventListener("click", function(){
        for (let j = 0; j < menuBtns.length - 1; j++) {
          if (i !== j) {
            
            menuBtns[j].parentNode.classList.remove('active');
            
            document.querySelector('.tab-content #' + menuBtns[j].href.substring(menuBtns[j].href.lastIndexOf('#') + 1)).classList.add('fade');
            document.querySelector('.tab-content #' + menuBtns[j].href.substring(menuBtns[j].href.lastIndexOf('#') + 1)).classList.remove('display');
          }
        }
        
        document.querySelector('.tab-content #' + menuBtns[i].href.substring(menuBtns[i].href.lastIndexOf('#') + 1)).classList.remove('fade');
        document.querySelector('.tab-content #' + menuBtns[i].href.substring(menuBtns[i].href.lastIndexOf('#') + 1)).classList.add('display');
        menuBtns[i].parentNode.classList.add('active');
      });
    }
  }
} else if (window.location.href.indexOf("users.php") > -1){
  
  // users page
  var menuBtns = document.querySelectorAll(".nav-tabs a");
  
  if (menuBtns) {
    menuBtns[0].parentNode.classList.add('active');
    
    for (let i = 0; i < menuBtns.length; i++) {
      
      menuBtns[i].addEventListener("click", function(){
        
        for (let j = 0; j < menuBtns.length; j++) {
          if (i !== j) {
            
            menuBtns[j].parentNode.classList.remove('active');
            
            document.querySelector('div#' + menuBtns[j].href.substring(menuBtns[j].href.lastIndexOf('#') + 1)).classList.add('fade');
            document.querySelector('div#' + menuBtns[j].href.substring(menuBtns[j].href.lastIndexOf('#') + 1)).classList.remove('active');
          }
        }
        
        document.querySelector('div#' + menuBtns[i].href.substring(menuBtns[i].href.lastIndexOf('#') + 1)).classList.remove('fade');
        document.querySelector('div#' + menuBtns[i].href.substring(menuBtns[i].href.lastIndexOf('#') + 1)).classList.add('active');
        menuBtns[i].parentNode.classList.add('active');
      });
    }
  }
}

// #################################################################################################

// admin menu
var adminMenuDropdown = document.querySelector("ul.navbar-right .dropdown");

if (adminMenuDropdown) {

  adminMenuDropdown.addEventListener("click", function(){
    adminMenuDropdown.classList.toggle('open');
  });
    }

// #################################################################################################

/* Ajax search. */
function searchSuggest(event) {
  $.post($(event.target.form).attr('action'), $(event.target.form).serialize(), function (data) {
    $('#search_suggest_' + ($(event.target.form).attr('action').indexOf('level') > -1 ? 'level' : 'user')).hide().html(data).fadeIn('fast');
  });
}

// #################################################################################################

/* checkbox logic */
$('.add-on :checkbox').click(function () {
	"use strict";
    if ($(this).attr('checked')) {
        $(this).parents('.add-on').addClass('active');
    } else {
        $(this).parents('.add-on').removeClass('active');
    }
});

/* forgotten password modal */
$('#forgot-form').bind('shown', function () {
	"use strict";
    $('#usernamemail').focus();
});

$('#forgot-form').bind('hidden', function () {
	"use strict";
    $('#username').focus();
});

// $("#sign-up-form").validate({
//   rules: {
// 		name: "required",
// 		username: {
// 			required: true,
// 			minlength: 2,
// 			remote: {
// 				url: "signup.php",
// 				type: "post",
// 				data: { checkusername: "1" }
// 			}
// 		},
// 		password: {
// 			required: true,
// 			minlength: 5
// 		},
// 		validation: {
// 			required: true
// 		},
// 		password_confirm: {
// 			required: true,
// 			minlength: 5,
// 			equalTo: "#password"
// 		},
// 		email: {
// 			required: true,
// 			email: true,
// 			remote: {
// 				url: "signup.php",
// 				type: "post",
// 				data: { checkemail: "1" }
// 			}
// 		}
// 	},
// 	messages: {
// 		name: "I know you've got one.",
// 		username: {
// 			required: "You need a username!",
// 			minlength: $.validator.format("Enter at least {0} characters"),
// 			remote: $.validator.format("Username has been taken.")
// 		},
// 		password: {
// 			required: "Create a password",
// 			minlength: $.validator.format("Enter at least {0} characters")
// 		},
// 		password_confirm: {
// 			required: "Confirm your password",
// 			minlength: $.validator.format("Enter at least {0} characters"),
// 			equalTo: "Your passwords do not match."
// 		},
// 		email: {
// 			required: "What's your email address?",
// 			email: "Please enter a valid email address.",
// 			remote: $.validator.format("Email address is in use.")
// 		}
//    },
// 	errorClass: 'has-error',
// 	validClass: 'has-success',
// 	errorElement: 'p',
// 	highlight: function(element, errorClass, validClass) {
// 		$(element).parent('div').parent('div').removeClass(validClass).addClass(errorClass);
// 	},
// 	unhighlight: function(element, errorClass, validClass) {
// 		$(element).parent('div').parent('div').removeClass(errorClass).addClass(validClass);
// 	}
// });

$('#forgotsubmit').click(function(){
	if ( $(this).text() != 'Done') {
		$('#forgotform').submit();
	}
})

$('#forgotform').submit(function (e) {
	"use strict";

	e.preventDefault();
	$('#forgotsubmit').button('loading');


	var post = $('#forgotform').serialize();
	var action = $('#forgotform').attr('action');

	$("#message").slideUp(350, function () {

		$('#message').hide();

		$.post(action, post, function (data) {
			$('#message').html(data);
			document.getElementById('message').innerHTML = data;
			$('#message').slideDown('slow');
			$('#usernamemail').focus();
			if (data.match('success') !== null) {
				$('#forgotform').slideUp('slow');
				$('#forgotsubmit').button('complete');
				$('#forgotsubmit').click(function (eb) {
					eb.preventDefault();
					$('#forgot-form').modal('hide');
				});
			} else if (data.match('smsforgotform') !== null){
				$('#forgotform').remove();
				$('#forgotsubmit').remove();
			} else {
				$('#forgotsubmit').button('reset');
			}
		});
	});
});

$(document).on('submit', '#smsforgotform', function (e) {
	"use strict";

	e.preventDefault();
	$('#smsforgotform').button('loading');

	var post = $('#smsforgotform').serialize();
	var action = $('#smsforgotform').attr('action');

	$("#message").slideUp(350, function () {

		$('#message').hide();

		$.post(action, post, function (data) {
			$('#message').html(data);
			document.getElementById('message').innerHTML = data;
			$('#message').slideDown('slow');
			$('#usernamemail').focus();
			if (data.match('success') !== null) {
				$('#forgotform').slideUp('slow');
				$('#forgotsubmit').button('complete');
				$('#forgotsubmit').click(function (eb) {
					eb.preventDefault();
					$('#forgot-form').modal('hide');
				});
			} else {
				$('#forgotsubmit').button('reset');
			}
		});
	});
});

// #################################################################################################

/* Save settings */
$('#settings-form').submit(function (e) {
  "use strict";
  
  e.preventDefault();
//   $('#save-settings').button('loading');
  
  var post = $('#settings-form').serialize();
  var action = $("#settings-form").attr('action');
  
  $("#message").slideUp(350, function () {
    
    $('#message').hide();
    
    $.post(action, post, function (data) {
      $('#message').html(data);
      $('#message').slideDown('slow');
      if (data.match('success') !== null) {
        $('#save-settings').button('reset');
      } else {
        $('#save-settings').button('reset');
			}
		});
	});
});

// #################################################################################################

/* Awesome Select2 jQuery plugin. */
$(".chzn-select").select2();

/* Some pretty checkbox features. */
function checkboxToggles() {
  $('input.collapsed').click(unhideHidden);
  $('input.uncollapsed').click(hideHidden);
  
  if ($('input:checked.collapsed')) {
    $('input:checked.collapsed').parent().next().hide().removeClass('hidden');
  }
  
  if ($('input:checked.uncollapsed')) {
    $('input:checked.uncollapsed').parent().next().hide().addClass('hidden');
  }
}

checkboxToggles();

// #################################################################################################

function hideHidden() {
  if ($(this).prop('checked')) {
    $(this).parent().nextAll().each(
      function(){
        if ($(this).filter('.last').length) {
          $(this).fadeOut(function() { $(this).addClass('hidden') });
          return false;
        }
        
        $(this).fadeOut(function() { $(this).addClass('hidden') });
      });
  } else {
    $(this).parent().nextAll().hide().removeClass('hidden').fadeIn();
  }
}

function unhideHidden() {
  jQuery(':input:not(:checked).collapsed').parent().next().hide().addClass('hidden');
  
  if ($(this).prop('checked')) {
    $(this).parent().nextAll().hide().removeClass('hidden').fadeIn();
  } else {
    $(this).parent().nextAll().each(
      function(){
        if ($(this).filter('.last').length) {
          $(this).fadeOut(function() { $(this).addClass('hidden') });
          return false;
        }
        
        $(this).fadeOut(function() { $(this).addClass('hidden') });
      }
    );
  }
}

// #################################################################################################

function hideHidden() {
  if ($(this).prop('checked')) {
    $(this).parent().nextAll().each(
      function(){
        if ($(this).filter('.last').length) {
          $(this).fadeOut(function() { $(this).addClass('hidden') });
          return false;
        }
        
        $(this).fadeOut(function() { $(this).addClass('hidden') });
      });
  }
  	else {
      $(this).parent().nextAll().hide().removeClass('hidden').fadeIn();
    }
}

// #################################################################################################

/* Function necessary to retrieve pagination or other GET variables and pass them to the page we're retrieving. */
function getParameters() {
  var searchString = window.location.search.substring(1) , params = searchString.split("&") , hash = '';
  if ( searchString ) {
    for (var i = 0; i < params.length; i++) {
      var val = params[i].split("=");
      if ( i == 0 ) hash += "?";
      hash += unescape(val[0]) + "=" + unescape(val[1]);
      if ( i + 1 < params.length ) { hash += "&"; }
    }
  }
  
  return hash;
}

// #################################################################################################

// prevent enter keypress to submit
if (window.location.href.indexOf("index.php") > -1) {
  document.getElementById('username-search').addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
    }
  });
}

$('#add_new_user_btn').click(function(e) {
  e.preventDefault();
  $('#add_user').slideToggle();
});

$('#showUsers').blur(function() {
  $.post(
    'classes/functions.php',
    {
      'showUsers' : $(this).val()
    }
  );
  
  //   Little hack to refresh the page silently...
  $('a[href="#level-control"]').tab('show');
  $('a[href="#user-control"]').tab('show');
});

/** Admin add user form validation */
$("#user-add-form").validate({
  
  /** Admin add user form submit */
  submitHandler: function() {
    
//     $('#user-add-submit').button('loading');
    
    var post = $('#user-add-form').serialize();
    var action = $('#user-add-form').attr('action');
    
    $("#message").slideUp(350, function () {
      
      $('#message').hide();
      
      $.post(action, post, function (data) {
        
        $('#message').html(data);
        $('#message').slideDown('slow');
        
        if (data.match('success') !== null) {
          $('#user-add-form input').val('');
          $('#user-add-submit').button('reset');
        } else {
//           $('#user-add-submit').button('reset');
        }
      });
    });
  },
  rules: {
    name: "required",
    username: {
      required: true,
      minlength: 2,
      remote: {
        url: "page/search-users.php",
        type: "post",
        data: { checkusername: "1" }
      }
    },
    email: {
      required: true,
      email: true,
      remote: {
        url: "page/search-users.php",
        type: "post",
        data: { checkemail: "1" }
      }
    }
  },
  messages: {
    name: "Please enter a name.",
    username: {
      required: "Username is required.",
      minlength: $.validator.format("Enter at least {0} characters"),
                             remote: $.validator.format("Username has been taken.")
    },
    email: {
      required: "We need an email address too.",
      email: "Please enter a valid email address.",
      remote: $.validator.format("Email address is in use.")
    }
  },
  errorClass: 'has-error',
  validClass: 'has-success',
  errorElement: 'p',
  highlight: function(element, errorClass, validClass) {
    $(element).parent('div').parent('div').removeClass(validClass).addClass(errorClass);
  },
  unhighlight: function(element, errorClass, validClass) {
    $(element).parent('div').parent('div').removeClass(errorClass).addClass(validClass);
  },
});

// #################################################################################################

// prevent enter keypress to submit
if (window.location.href.indexOf("index.php") > -1) {
  document.getElementById('level').addEventListener('keypress', function(event) {
    if (event.keyCode == 13) {
      event.preventDefault();
    }
  });
    }
$('#create_new_level_btn').click(function(e) {
  e.preventDefault();
  $('#create_level').slideToggle();
});

$('#showLevels').blur(function() {
  $.post('classes/functions.php', {'showLevels' : $(this).val()});

  /* Little hack to refresh the page silently... */
  $('a[href="#level-control"]').tab('show');
  $('a[href="#user-control"]').tab('show');
    });

// ----------

$("#level-add-form").validate({
  
  /** admin add role form */
  submitHandler: function() {
    
//     $('#level-add-submit').button('loading');
    
    var post = $('#level-add-form').serialize();
    var action = $("#level-add-form").attr('action');
    
    $("#level-message").slideUp(350, function () {
      
      $('#level-message').hide();
      
      $.post(action, post, function (data) {

        $('#level-message').html(data);
        $('#level-message').slideDown('slow');
        
        //document.getElementById('level-message').innerHTML = data;
        
        if (data.match('success') !== null) {
          $('#level-add-form input').val('');
//           $('#level-add-submit').button('reset');
        } else {
          $('#level-add-submit').button('reset');
        }
      });
    });
  },
  rules: {
    level: {
      required: true,
      remote: {
        url: "page/search-levels.php",
        type: "post",
        data: { checklevel: "1" }
      }
    }
  },
  messages: {
    level: {
      required: "This needs to be filled out.",
      remote: $.validator.format("Role name already in use.")
    },
  },
  errorClass: 'has-error',
  validClass: 'has-success',
  errorElement: 'p',
  
  highlight: function(element, errorClass, validClass) {
    $(element).parent('div').parent('div').removeClass(validClass).addClass(errorClass);
  },

  unhighlight: function(element, errorClass, validClass) {
    $(element).parent('div').parent('div').removeClass(errorClass).addClass(validClass);
  },
});
