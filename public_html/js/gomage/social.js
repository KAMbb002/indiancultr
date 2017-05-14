
/**
 * GoMage Social Connector Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2013 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 1.1.0
 * @since        Class available since Release 1.1.0
 */

GomageSocialClass = Class.create({

    config: null,
    overlay: null,
    isExisting:'',
    checkEmailURL:'',
    

    /*initialize: function (config) {
        this.overlay = $('gomage-social-overlay');
        if (!this.overlay) {
            var element = $$('body')[0];
            this.overlay = $(document.createElement('div'));
            this.overlay.id = 'gomage-social-overlay';
            document.body.appendChild(this.overlay);
            var offsets = element.cumulativeOffset();
               window.onload = function(){
                   //$('gs-email').focus();
                   var wrapper = GomageSocialClass.elementsByClass('wrapper');
                   if(wrapper){

                           GomageSocialClass.overlay.setStyle({
                               'height': wrapper.getHeight() + 'px',
                               'width': wrapper.getWidth() + 'px'
                           });

                   }
               }
            this.overlay.setStyle({
                'top': offsets[1] + 'px',
                'left': offsets[0] + 'px',
                'width': element.offsetWidth + 'px',
                'height': screen.height + 'px',
                'position': 'absolute',
                'display': 'none',
                'zIndex': '2000'

             });
         }      
    },*/
    bindEvents:function(){
        /*jQuery('input#social_email').blur(function()
        {
            GomageSocialClass.checkEmailExist();
        });*/
        
        jQuery('#joinButton').live('click',function(e)
        {
            GomageSocialClass.submitJoinForm();
        });
	jQuery('#mjoinButton').live('click',function(e){
	    GomageSocialClass.submitMJoinForm();
	});
	jQuery('#join_email').live('focus',function(e){
	    if(jQuery(this).val()=='Please enter a valid email id')
	    jQuery(this).val('');
	});
        jQuery('#loginButton').live('click',function(e)
        {
            GomageSocialClass.submitLoginForm();
        });
        jQuery('#forgotButton').live('click',function(e)
        {
            GomageSocialClass.submitForgotForm();
        });
        
        jQuery('#joinSection input[type=text],#loginSection input[type=text],#forgotSection input[type=text] ').keypress(function(e)
    	{
    	 if (e.which == 13) 
         {
         	jQuery(this).parents('form').find('.button').trigger('click');
       	}}); 
         
    },

    sendEmail : function(url) {
        var gsForm = new VarienForm('gs-validate-detail', true);
        var params = this.getFormData();
        if (gsForm.validator && gsForm.validator.validate()) {
            $('gs-please-wait').show();
            $('gs-message').innerHTML = '';
            parameters: params;

            var request = new Ajax.Request(url, {
                method : 'POST',
                parameters : params,
                loaderArea : false,
                onSuccess : function(transport) {
                    var response = eval('(' + (transport.responseText || false)
                        + ')');
                    if(response.error){
                        $('gs-message').innerHTML = response.error;
                    }
                    if(response.success){
                        $('gs-popup-content').hide();
                        window.location.replace(location.href.replace('#_=_', ''));
                    }
                    if(response.redirect){
                        window.location.replace(response.redirect);
                    }
                    $('gs-please-wait').hide();

                }
            });

        }
    },

     id_window: function() {
    var elements = window.parent.document.getElementsByClassName('dialog');
    for (i = 0; i < elements.length; i++){
        id = elements[i].firstChild.id;
        break;
    }
   return id;

},
    unsGsProfile: function(url) {
        var request = new Ajax.Request(url, {
                method : 'POST',
                loaderArea : false
        });
    },
    elementsByClass: function(name) {
        var elements = window.parent.document.getElementsByClassName(name);
        for (i = 0; i < elements.length; i++){
            obj = elements[i];
            break;
        }
        return obj;
    },

    getFormData:function(){
        var form_data = $('gs-form').serialize(true);
        for (var key in form_data){
                form_data[key] = GlcUrl.encode(form_data[key]);
        }

        return form_data;
    },

    createWindow : function (title,width,height)
    {
    win = new Window({
    className:'dialog-gs magento',
    title: title,
    width:width,
    height:height,
        maximizable:false,
        minimizable:false,
        resizable:false,
        draggable:false,
        closeOnEsc: false,
        showEffect:Effect.BlindDown,
        hideEffect:Effect.BlindUp,
        showEffectOptions: {duration: 0.4},
        hideEffectOptions: {duration: 0.4}}

    );
     win.setZIndex(5000);
     return win;
    },
    
    validateEmailAddress: function(emailEl) {
	aa=emailEl;
        var emailflag = true;
        var email = jQuery.trim(emailEl.val());
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

        if(email == "" || email == "Enter your email address here"){
            emailEl.addClass("input-field-error");
	$(emailEl).parent().addClass("input-field-error");	
            emailflag = false;

        } else if(!emailPattern.test(email)){
            emailEl.addClass("input-field-error");
        	emailEl.val("Please enter a valid email id");
        	$(emailEl).parent().addClass("input-field-error");	
        	emailflag = false;
        }

        if(emailflag){
            if (emailEl.hasClass("input-field-error")) {
                emailEl.removeClass("input-field-error");
		$(emailEl).parent().removeClass("input-field-error");	
                
	    }
        }

        return emailflag;
    },
    
    
    
    checkEmailExist : function()
    {
        emailEl=jQuery('#social_email');
        emailEl.val(jQuery.trim(emailEl.val()));
        if (!GomageSocialClass.validateEmailAddress(emailEl)) {
            return;
        }
        jQuery.ajax(
        {
            async: true,
            type: "GET",
            url: this.checkEmailURL+"mail/"+emailEl.val(),

            beforeSend: function(){
               // GomageSocialClass.loadingPageEl.show();
            },
            success: function(data)
            {

                //GomageSocialClass.loadingPageEl.hide();
                var stingArr = data.split(",");
                if(stingArr[3] =='Y') {
                    GomageSocialClass.isExisting = true;
                    if(stingArr[4]=="First Name"){
                        jQuery("#login_fname").show();
                        jQuery("#login_lname").show();
                    }else
                    {
                        if (stingArr[4] != '') {
                            jQuery('#login_firstname').val(stingArr[4]);
                        }
                        if (stingArr[5] != '') {
                             jQuery('#login_lastname').val(stingArr[5]);
                        }
                        jQuery("#login_fname").hide();
                        jQuery("#login_lname").hide();
                    }

                    jQuery('#header_login').removeClass('btn-login-dis').addClass('btn-login');
                    jQuery('#header_join').removeClass('btn-join').addClass('btn-join-dis');

                } else {
                    GomageSocialClass.isExisting = false;
                    jQuery("#login_fname").show();
                        jQuery("#login_lname").show();
                    jQuery('#login_firstname').val('');
                    jQuery('#login_lastname').val('');
                    jQuery('#header_join').removeClass('btn-join-dis').addClass('btn-join');
                    jQuery('#header_login').removeClass('btn-login').addClass('btn-login-dis');
                }
            }
        });
    },
    validateMForm:function() {
        var validFlag = true;
        var passflag = true;
		
        var emailEl = jQuery('input#join_email');
        var passObj = jQuery('input#join_password');
        var confirmPass=jQuery('input#confirm_pass');
        if (!GomageSocialClass.validateEmailAddress(emailEl)) {
            validFlag = false;
	
        }
        if(passObj.val() == "" || passObj.val() =="Create / Enter your password" ){
            passObj.addClass("input-field-error");
	    passObj.parent().addClass("input-field-error");
            passflag = false;

        } else if(passObj.val().length <= 5 ){
    		passObj.addClass("input-field-error");
		passObj.parent().addClass("input-field-error");
            	passflag = false;
        }
	else
	{
		passObj.removeClass("input-field-error");
		passObj.parent().removeClass("input-field-error");
	}
	if(confirmPass.val()=='' || confirmPass.val()=='Re-enter your password')
	{
	     confirmPass.addClass("input-field-error");
		confirmPass.parent().addClass("input-field-error");
            passflag = false;
	}
	else if(confirmPass.val()!=passObj.val())
	{
	    confirmPass.addClass("input-field-error");
	    confirmPass.parent().addClass("input-field-error");
            passflag = false;
	}
        
        if(passflag && validFlag){
            passObj.removeClass("input-field-error");
 	passObj.parent().removeClass("input-field-error");
            validFlag = true;
        } else {
            validFlag = false;
        }

        return validFlag;
    },
    validateForm:function() {
        var validFlag = true;
        var passflag = true;
		
        var emailEl = jQuery('input#join_email');
        var passObj = jQuery('input#join_password');
        var firstObj = jQuery('input#join_firstname');
        var lastObj = jQuery('input#join_lastname');
        var confirmPass=jQuery('input#confirm_pass');
        if (!GomageSocialClass.validateEmailAddress(emailEl)) {
            validFlag = false;
	
        }
        if(passObj.val() == "" || passObj.val() =="Create / Enter your password" ){
            passObj.addClass("input-field-error");
		passObj.parent().addClass("input-field-error");
            passflag = false;

        } else if(passObj.val().length <= 5 ){
    		passObj.addClass("input-field-error");
		passObj.parent().addClass("input-field-error");
            	passflag = false;
        }
	else
	{
		passObj.removeClass("input-field-error");
		passObj.parent().removeClass("input-field-error");
	}
	if(confirmPass.val()=='' || confirmPass.val()=='Re-enter your password')
	{
	     confirmPass.addClass("input-field-error");
		confirmPass.parent().addClass("input-field-error");
            passflag = false;
	}
	else if(confirmPass.val()!=passObj.val())
	{
	    confirmPass.addClass("input-field-error");
	    confirmPass.parent().addClass("input-field-error");
            passflag = false;
	}
        if(firstObj.val() == "" || firstObj.val() == "Enter your first name")
        {
            firstObj.addClass("input-field-error");
		firstObj.parent().addClass("input-field-error");
            passflag = false;
        }else
	{
		firstObj.removeClass("input-field-error");
		firstObj.parent().removeClass("input-field-error");
 
	}
        if(lastObj.val() == "" || lastObj.val()=='Enter your last name')
        {
           
        	lastObj.addClass("input-field-error");
            lastObj.parent().addClass("input-field-error");
            passflag = false;
        }else
        {
        	lastObj.removeClass("input-field-error");
        	lastObj.parent().removeClass("input-field-error");
        	
        }


        if(passflag && validFlag){
            passObj.removeClass("input-field-error");
 	passObj.parent().removeClass("input-field-error");
            validFlag = true;
        } else {
            validFlag = false;
        }

        return validFlag;
    },
    validateLoginForm:function(){
        var validFlag = true;
        var passflag = true;
        
        var emailEl = jQuery('input#login_email');
        var passObj = jQuery('input#login_password');
        if (!GomageSocialClass.validateEmailAddress(emailEl)) {
            validFlag = false;
	
        }
        if(passObj.val() == "" || passObj.val() == "Create / Enter your password"){
            //passObj.addClass("input-field-error");
		passObj.addClass("input-field-error");
		passObj.parent().addClass("input-field-error");
            
            passflag = false;

        } else if(passObj.val().length <= 5 ){
            passObj.addClass("input-field-error");
		passObj.parent().addClass("input-field-error");
            passflag = false;
        }
	else
	{
		passObj.removeClass("input-field-error");
		passObj.parent().removeClass("input-field-error");
	}
        if(passflag && validFlag){
            validFlag = true;
        } else {
            validFlag = false;
        }
        return validFlag;
    },
    submitMJoinForm:function() {
   
          if (!GomageSocialClass.validateMForm()) {
            return false;
          }  
		jQuery('#mjoinButton').hide();
		jQuery('#registerFormSection .loaderPop').first().show();
		var request = new Ajax.Request(
		GomageSocialClass.accountCreateURL,
		{
			method:'post',
			onComplete: function(){
			   
			},
			onSuccess: function(transport){
			   if (transport && transport.responseText){
			 try{
				response = eval('(' + transport.responseText + ')');
			  }
			  catch (e) {
				response = {};
			  }
			}
			
			if (response.success){
		    	       Mage.Cookies.set('member', 'Yes');
			       
			       //fb pixel
			       var _fbq = window._fbq || (window._fbq = []);
				if (!_fbq.loaded) {
				  var fbds = document.createElement('script');
				  fbds.async = true;
				  fbds.src = '//connect.facebook.net/en_US/fbds.js';
				  var s = document.getElementsByTagName('script')[0];
				  s.parentNode.insertBefore(fbds, s);
				  _fbq.loaded = true;
				}
				window._fbq = window._fbq || [];
				window._fbq.push(['track', '6024504743917', {'value':'0.00','currency':'USD'}]);
			       
			        var _fbq = window._fbq || (window._fbq = []);
				if (!_fbq.loaded) {
				  var fbds = document.createElement('script');
				  fbds.async = true;
				  fbds.src = '//connect.facebook.net/en_US/fbds.js';
				  var s = document.getElementsByTagName('script')[0];
				  s.parentNode.insertBefore(fbds, s);
				  _fbq.loaded = true;
				}
				window._fbq = window._fbq || [];
				window._fbq.push(['track', '6020191795885', {'value':'0.00','currency':'USD'}]);
				//jQuery('body').append('<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6020191795885&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>');
			       
			       //fb pixel
			       
			       google_conversion_id = 958174239;
				google_conversion_language = "en";
				google_conversion_format = "3";
				google_conversion_color = "ffffff";
				google_conversion_label = "gax1CMWevlkQn6jyyAM";
				google_remarketing_only = false;
				var adScript = document.createElement('script');
				adScript.setAttribute('src','//www.googleadservices.com/pagead/conversion.js');
				document.head.appendChild(adScript);
			       
			       
			        window.location.href = '/thankyou';
			       
			    }else{
				if ((typeof response.message) == 'string') {
				    jQuery('#registerFormSection .messages').html('<span>' + response.message + '</span>');
					jQuery('#registerFormSection .messages').addClass('error');
		                    jQuery("#registerFormSection .messages").show();
                                    jQuery('#mjoinButton').removeClass('joinButtonDis').addClass('joinButton');
				} 
				jQuery('#mjoinButton').show();
				jQuery('#registerFormSection .loaderPop').first().hide();
				return false;
			}
			},
			onFailure: function(){
			jQuery('#mjoinButton').show();
			jQuery('#registerFormSection .loaderPop').first().hide();
			  jQuery('#registerFormSection .messages').html('<span>Failed</span>');
				jQuery('#registerFormSection .messages').addClass('error');
                          jQuery("#registerFormSection .messages").show();
                          jQuery('#mjoinButton').removeClass('joinButtonDis').addClass('joinButton');
			},
			parameters: Form.serialize('joinSection'),
			onLoading: function(){
                            jQuery("#registerFormSection .messages").hide();
		            jQuery('.load-messages').show();
                            jQuery('#mjoinButton').removeClass('joinButton').addClass('joinButtonDis');
                        //jQuery(".messages").show(); 
		    }
		}
	    );
    },
    submitJoinForm:function() {
   
          if (!GomageSocialClass.validateForm()) {
            return false;
          }  
		jQuery('#joinButton').hide();
		jQuery('#registerFormSection .loaderPop').first().show();
            var request = new Ajax.Request(
		GomageSocialClass.accountCreateURL,
		{
			method:'post',
			onComplete: function(){
			   
			},
			onSuccess: function(transport){
			   if (transport && transport.responseText){
			 try{
				response = eval('(' + transport.responseText + ')');
			  }
			  catch (e) {
				response = {};
			  }
			}
			
			if (response.success){
		    	       Mage.Cookies.set('member', 'Yes');
			       jQuery('#top_links').replaceWith(response.content);
			       //fb pixel
			       var _fbq = window._fbq || (window._fbq = []);
				if (!_fbq.loaded) {
				  var fbds = document.createElement('script');
				  fbds.async = true;
				  fbds.src = '//connect.facebook.net/en_US/fbds.js';
				  var s = document.getElementsByTagName('script')[0];
				  s.parentNode.insertBefore(fbds, s);
				  _fbq.loaded = true;
				}
				window._fbq = window._fbq || [];
				window._fbq.push(['track', '6024504743917', {'value':'0.00','currency':'USD'}]);
			       
			        var _fbq = window._fbq || (window._fbq = []);
				if (!_fbq.loaded) {
				  var fbds = document.createElement('script');
				  fbds.async = true;
				  fbds.src = '//connect.facebook.net/en_US/fbds.js';
				  var s = document.getElementsByTagName('script')[0];
				  s.parentNode.insertBefore(fbds, s);
				  _fbq.loaded = true;
				}
				window._fbq = window._fbq || [];
				window._fbq.push(['track', '6020191795885', {'value':'0.00','currency':'USD'}]);
				//jQuery('body').append('<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6020191795885&amp;cd[value]=0.00&amp;cd[currency]=USD&amp;noscript=1" /></noscript>');
			       
			       //fb pixel
			       
			       
			       
			        google_conversion_id = 958174239;
				google_conversion_language = "en";
				google_conversion_format = "3";
				google_conversion_color = "ffffff";
				google_conversion_label = "gax1CMWevlkQn6jyyAM";
				google_remarketing_only = false;
				var adScript = document.createElement('script');
				adScript.setAttribute('src','//www.googleadservices.com/pagead/conversion.js');
				document.head.appendChild(adScript);
			       //jQuery('body').append('<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/958174239/?label=gax1CMWevlkQn6jyyAM&amp;guid=ON&amp;script=0"/>');
			       if(jQuery('#waitlistClick') && jQuery('#waitlistClick').val()==1)
			       {
				    jQuery('.loginJoinPopup .popClose').click();
				    jQuery('#isCustLogin').val(1);
				    if(jQuery('#isCatPage'))
				    {
					catalogAddtoWaitlist('',jQuery('#wishlistProduct').val());
				    }
				    else
				    {
					addToWaitlist(jQuery('#addtowaitlist'));
				    }
				    
				    return;
			       }
			       else if(jQuery('#wishlistClick') && jQuery('#wishlistClick').val()==1)
			       {
				    jQuery('.loginJoinPopup .popClose').click();
				    jQuery('#isCustLogin').val(1);
				    //addtoWishlist();
				    return;
			       }
			       else
			       {
				    window.location.href = CURRENT_URL;
			       }
			       // 
			       
			    }else{
				if ((typeof response.message) == 'string') {
				    jQuery('#registerFormSection .messages').html('<span>' + response.message + '</span>');
					jQuery('#registerFormSection .messages').addClass('error');
		                    jQuery("#registerFormSection .messages").show();
                                    jQuery('#joinButton').removeClass('joinButtonDis').addClass('joinButton');
				} 
				jQuery('#joinButton').show();
				jQuery('#registerFormSection .loaderPop').first().hide();
				return false;
			}
			},
			onFailure: function(){
			jQuery('#joinButton').show();
			jQuery('#registerFormSection .loaderPop').first().hide();
			  jQuery('#registerFormSection .messages').html('<span>Failed</span>');
				jQuery('#registerFormSection .messages').addClass('error');
                          jQuery("#registerFormSection .messages").show();
                          jQuery('#joinButton').removeClass('joinButtonDis').addClass('joinButton');
			},
			parameters: Form.serialize('joinSection'),
			onLoading: function(){
                            jQuery("#registerFormSection .messages").hide();
		            jQuery('.load-messages').show();
                            jQuery('#joinButton').removeClass('joinButton').addClass('joinButtonDis');
                        //jQuery(".messages").show(); 
		    }
		}
	    );
    },
    submitForgotForm:function(){
        
       var emailForgotEl = jQuery('#forgot_email');
       if (!GomageSocialClass.validateEmailAddress(emailForgotEl)) {
            return;
        }
		jQuery('#forgotButton').hide();
		jQuery('#forgotPassSection .loaderPop').first().show();
        var request = new Ajax.Request(
		GomageSocialClass.forgotPasswordURL,
		{
			method:'post',
			onComplete: function(){
			   
			},
			onSuccess: function(transport){
			   if (transport && transport.responseText){
			 try{
				response = eval('(' + transport.responseText + ')');
			  }
			  catch (e) {
				response = {};
			  }
			}
			
			if (response.success){
				jQuery('#forgotButton').show();
				jQuery('#forgotPassSection .loaderPop').first().hide();
		       	jQuery('#forgotPassSection .messages').html('<span>A new password has been sent.</span>');
				jQuery('#forgotPassSection .messages').removeClass('error');	
				jQuery('#forgotPassSection .messages').addClass('success');	
                      		jQuery('#forgotPassSection .messages').show();
			    }else{
				if ((typeof response.message) == 'string') {
				    jQuery('#forgotPassSection .messages').html('<span>' + response.message + '</span>');
					jQuery('#forgotPassSection .messages').addClass('error');
                    			jQuery("#forgotPassSection .messages").show();
                    			jQuery('#forgotButton').removeClass('forgotButtonDis').addClass('forgotButton');
				} 
				jQuery('#forgotButton').show();
				jQuery('#forgotPassSection .loaderPop').first().hide();
				return false;
			}
			},
			onFailure: function(){
				jQuery('#forgotButton').show();
				jQuery('#forgotPassSection .loaderPop').first().hide();
			  jQuery('#forgotPassSection .messages').html('<span>Failed</span>');
			jQuery('#forgotPassSection .messages').addClass('error');
                          jQuery("#forgotPassSection .messages").show();
                          jQuery('#forgotButton').removeClass('forgotButtonDis').addClass('forgotButton');
			},
			parameters: Form.serialize('forgotSection'),
			onLoading: function(){
                            jQuery("#forgotPassSection .messages").hide();
		            jQuery('.load-messages').show();
                            jQuery('#forgotButton').removeClass('forgotButton').addClass('forgotButtonDis');
                       // jQuery(".messages").show(); 
		    }
		}
	    );
    },
    submitLoginForm:function() {
        
        if (!GomageSocialClass.validateLoginForm()) {
            return;
        }
		jQuery('#loginButton').hide();
		jQuery('#loginFormSection .loaderPop').first().show();
        var request = new Ajax.Request(
		GomageSocialClass.accountLoginURL,
		{
			method:'post',
			onComplete: function(){
			   
			},
			onSuccess: function(transport){
				
				
			   if (transport && transport.responseText){
			 try{
				response = eval('(' + transport.responseText + ')');
			  }
			  catch (e) {
				response = {};
			  }
			}
			
			if (response.success){
		    	       Mage.Cookies.set('member', 'Yes');
			       
			      
			       if(jQuery('#waitlistClick') && jQuery('#waitlistClick').val()==1)
			       {
				 jQuery('.loginJoinDrawer .popClose').click();
				 jQuery('#top_links').replaceWith(response.content);
				    jQuery('#isCustLogin').val(1);
				    if(jQuery('#isCatPage'))
				    {
					catalogAddtoWaitlist('',jQuery('#wishlistProduct').val());
				    }
				    else
				    {
					addToWaitlist(jQuery('#addtowaitlist'));
				    }
				    return;
			       }
			       else if(jQuery('#wishlistClick') && jQuery('#wishlistClick').val()==1)
			       {
				jQuery('#top_links').replaceWith(response.content);
				    jQuery('.loginJoinDrawer .popClose').click();
				    jQuery('#isCustLogin').val(1);
				    //addtoWishlist();
				    return;
			       }
			       else
			       {
				window.location.href = CURRENT_URL;
			       }
			       // 
			    }else{
				jQuery('#loginButton').show();
				jQuery('#loginFormSection .loaderPop').first().hide();
				if ((typeof response.message) == 'string') {
				    jQuery('#loginFormSection .messages').first().html('<span>' + response.message + '</span>');
				    jQuery('#loginFormSection .messages').first().addClass('error');
		                    jQuery("#loginFormSection .messages").first().show();
                                    jQuery('#loginButton').removeClass('loginButtonDis').addClass('loginButton');
				} 
				return false;
			}
			},
			onFailure: function(){
				
			jQuery('#loginButton').show();
			jQuery('#loginFormSection .loaderPop').first().hide();
			  jQuery('#loginFormSection .messages').first().html('<span>Failed</span>');
			jQuery('#loginFormSection .messages').first().addClass('error');
                          jQuery("#loginFormSection .messages").first().show();
                          jQuery('#loginButton').removeClass('loginButtonDis').addClass('loginButton');
			},
			parameters: Form.serialize('loginSection'),
			onLoading: function(){
                            jQuery("#loginFormSection .messages").first().hide();
		            jQuery('.load-messages').show();
                            jQuery('#loginButton').removeClass('loginButton').addClass('loginButtonDis');
                    
		    }
		}
	    );

    },




});

var GlcUrl = {

    encode : function (string) {
        return escape(this._utf8_encode(string));
    },

    decode : function (string) {
        return this._utf8_decode(unescape(string));
    },

    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }
};
function textColorChange(obj)
{
	jQuery(obj).removeClass("input-field-error");
}