jQuery.noConflict();
(function($){
    var baseUrl = 'https://dashboard.revechat.com/';
    var ReveChat ={
        init: function () {
            this.toggleForms();
            this.bindFormSubmit();
        },

        toggleForms: function ()
        {
            var toggleForms = function ()
            {
                if ($('#new_revechat_account').is(':checked'))
                {
                    $('#revechat_already_have').hide();
                    $('#revechat_new_account').show();
                }
                else if ($('#has_revechat_account').is(':checked'))
                {
                    $('#revechat_new_account').hide();
                    $('#revechat_already_have').show();
                    $('#edit-revechat-account-email').focus();
                }
            };
            toggleForms();

            $('#revechat_choose_form input').click(toggleForms);
        },

        bindFormSubmit: function () {
            $('#revechat-admin-settings-form').submit(function(e)
            {

                if($('#edit-submit').val() == 'Disconnect'){
                    $('#revechat_aid').val(0);
                    $('#revechat-admin-settings-form').submit();
                }
                if (((parseInt($('#revechat_aid').val()) !== 0) || $('#has_revechat_account').is(':checked')))
                {
                    return ReveChat.alreadyHaveAccountForm();
                }
                else
                {
                    return ReveChat.newLicenseForm();
                }

            });
        },

        alreadyHaveAccountForm: function()
        {
            var email = $.trim($('#edit-revechat-account-email').val());

            if(ReveChat.isValidEmailAddress(email))
            {
                if((parseInt($('#revechat_aid').val()) == 0 || $('#revechat_aid').val() == ""))
                {
                    $('.ajax_message').removeClass('message').addClass('wait').html('Please wait&hellip;');

                    ReveChat.signIn(email);
                    return false;
                }
            }
            else
            {
                alert('Please provide a valid email address');
                $('#edit-revechat-account-email').focus();
                return false;
            }
            return true;
        },
        signIn: function (email) {
            var signInUrl = baseUrl +'/license/adminId/'+email+'/?callback=?';
            $.getJSON(signInUrl,
                function(response)
                {
                    if (response.error)
                    {
                        alert('Incorrect REVE Chat login');
						$('.ajax_message').removeClass('wait');
                        $('#edit-revechat-account-email').focus();
                        return false;
                    }
                    else
                    {
                        $('#revechat_aid').val(response.data.account_id);
                        $('#revechat-admin-settings-form').submit();
                    }
                });
        },
        newLicenseForm: function()
        {
            if (parseInt(($('#revechat_aid').val()) > 0))
            {
                return true;
            }

            if(this.validateNewLicenseForm())
            {
                $('.ajax_message').removeClass('message').addClass('wait').html('Please wait...');
                ReveChat.createLicense();
            }
            return false;
        },
        createLicense: function()
        {

            $('.ajax_message').removeClass('message').addClass('wait').html('Creating new account&hellip;');

            var firstName = $.trim($('#edit-firstname').val());
            var lastName = $.trim($('#edit-lastname').val());
            var email = $.trim($('#edit-email').val());
            var password = $.trim($('#edit-accountpassword').val());
            var rePassword = $.trim($('#edit-retypepassword').val());

            var signUpUrl = baseUrl + 'revechat/cms/api/signup.do';

            $.ajax({
                 data: { 'firstname':firstName, 'lastname':lastName, 'mailAddr':email, 'password':password, 'utm_source':'cms', 'utm_content':'wordpress', 'referrer':'https://wordpress.org/plugins/'  },
                type:'POST',
                url:signUpUrl,
                dataType: 'json',
                cache:false,
                success: function(response) {
                    if(response.status == 'error')
                    {
                        $('.ajax_message').removeClass('wait');
                        alert(response.message);
                        return false;
                    }
                    else if(response.status == 'success')
                    {
                        $('#edit-revechat-account-email').val(email);
                        ReveChat.signIn(email);
                        return false;
                    }
                }
            });
        },
        validateNewLicenseForm: function()
        {

            var firstName = $.trim($('#edit-firstname').val());
            var lastName = $.trim($('#edit-lastname').val());
            var email = $.trim($('#edit-email').val());
            var password = $.trim($('#edit-accountpassword').val());
            var rePassword = $.trim($('#edit-retypepassword').val());



            if (!firstName.length)
            {
                alert('Please provide your first name.');
				$('.ajax_message').removeClass('wait');
                $('#edit-firstname').focus();
                return false;
            }

            if (!lastName.length)
            {
                alert('Please provide your last name.');
				$('.ajax_message').removeClass('wait');
                $('#edit-lastname').focus();
                return false;
            }

            if (!ReveChat.isValidEmailAddress(email))
            {
                alert('Please provide your valid email address.');
				$('.ajax_message').removeClass('wait');
                $('#edit-email').focus();
                return false;
            }

             if(password.length < 6){
                alert('Please provide your password. The password must be at least 6 characters long.')
                $('.ajax_message').removeClass('wait');
				$('#edit-accountpassword').focus();
                return false;
             }

            if(!rePassword.length || password.length < 6){
                alert('Please retype your password.');
				$('.ajax_message').removeClass('wait');
                $('#edit-retypepassword').focus();
                return false;
            }

            if(password != rePassword){
                alert('Password does not match the confirm password.');
				$('.ajax_message').removeClass('wait');
                return false;
            }
           

            return true;
        },
        isValidEmailAddress: function (emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(emailAddress);
        },
    }
    $(document).ready(function()
    {
        ReveChat.init();
    });
})(jQuery);