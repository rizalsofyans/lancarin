<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<title><?=get_option("website_title", "Stackposts - Social Marketing Tool")?></title>
		<meta name="description" content="<?=get_option("website_description", "save time, do more, manage multiple social networks at one place")?>" />
		<meta name="keywords" content="<?=get_option("website_keyword", 'social marketing tool, social planner, automation, social schedule')?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="icon" type="image/png" href="<?=get_option("website_favicon", BASE.'assets/img/favicon.png')?>" />
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script>
			WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
      token_name = "<?=$this->security->get_csrf_token_name(); ?>";
      token_value = '<?=$this->security->get_csrf_hash();?>';
        </script>

		<!--end::Web font -->

		<!--begin::Global Theme Styles -->
		<link href="<?=base_url('assets/metronic/assets/vendors/base/vendors.bundle.css');?>" rel="stylesheet" type="text/css" />

		<link href="<?=base_url('assets/metronic/assets/demo/default/base/style.bundle.css');?>" rel="stylesheet" type="text/css" />
		
		<!--end::Global Theme Styles -->
    
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('track', 'PageView');
  fbq('track', 'CompleteRegistration');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=324241628191012&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
    
    <style>
    .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__account .m-login__account-msg {
        color:#333 !important;
      }
     .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-sub .m-checkbox {
        color:#333 !important;
      }
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-sub .m-link {
        color:#333 !important;
      }
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__head .m-login__desc {
        color:#333 !important;
      }
      .m-login.m-login--2 .m-login__wrapper .m-login__container .m-login__head .m-login__desc{
        font-size:1.2rem !important;
      }
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control {
        color:#333 !important;
        background-color : rgb(248, 248, 248) !important;
        border : 1px solid #d8d8d8 !important;
        border-radius: .25rem !important;
      }
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form {
        color:#333 !important;
      }
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__head .m-login__title {
        color:#333 !important;
      }
      .m-login.m-login--2 .m-login__wrapper .m-login__container .m-login__head .m-login__title {
        font-size: 1.7rem;
        font-weight: 600;
        text-transform:uppercase;
      }
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control::-moz-placeholder {
        color: #777 !important;
        opacity: 1;
       }
    .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control:-ms-input-placeholder {
    color: #777 !important;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control::-webkit-input-placeholder {
    color: #777 !important;
}
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__account .m-login__account-link {
        color:#333 !important;
        text-decoration:underline;
      } 
.m-checkbox.m-checkbox--light.m-checkbox--disabled {
    opacity: .8;
    filter: alpha(opacity=80);
}

.m-checkbox.m-checkbox--light>span {
    border: 1px solid #333;
}

.m-checkbox.m-checkbox--light>span:after {
    border: solid #333;
}

.m-checkbox.m-checkbox--light>input:disabled ~ span:after {
    border-color: #333;
}

.m-checkbox.m-checkbox--light>input:checked ~ span {
    border: 1px solid #333;
}

.m-checkbox.m-checkbox--light.m-checkbox--check-bold>input:checked ~ span {
    border: 2px solid #333;
}

.m-checkbox.m-checkbox--light>input:disabled ~ span {
    opacity: .6;
    filter: alpha(opacity=60);
}

.m-checkbox.m-checkbox--light.m-checkbox--solid>span {
    border: 1px solid transparent !important;
}

.m-checkbox.m-checkbox--light.m-checkbox--solid>span:after {
    border: 1px solid #333;
}

.m-checkbox.m-checkbox--light.m-checkbox--solid>input:focus ~ span {
    border: 1px solid transparent !important;
}

.m-checkbox.m-checkbox--light.m-checkbox--solid>input:checked ~ span {
    background: #333;
}
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control:focus {
    color: #fff;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control:focus::-moz-placeholder {
    color: #e3d9fa;
    opacity: 1
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control:focus:-ms-input-placeholder {
    color: #e3d9fa;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .form-control:focus::-webkit-input-placeholder {
    color: #e3d9fa;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-sub .m-checkbox {
    color: #c2acf4;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-sub .m-link {
    color: #c2acf4;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-sub .m-link:hover {
    color: #fff;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-sub .m-link:hover:after {
    border-bottom: 1px solid #fff;
    opacity: .3;
    filter: alpha(opacity=30)
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn {
    color: #fff;
    border-color: #333;
    background-color: transparent
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn:focus,
.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn:hover {
    color: #fff;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn.m-login__btn--primary {
    color: #fff;
    border-color: #333
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn.m-login__btn--primary:focus,
.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn.m-login__btn--primary:hover {
    color: #fff;
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__account .m-login__account-msg {
    color: #333
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__account .m-login__account-link {
    color: #333
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__account .m-login__account-link:hover {
    color: #333
}

.m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__account .m-login__account-link:hover:after {
    border-bottom: 1px solid #fff;
    opacity: .3;
    filter: alpha(opacity=30)
}
      .m-login.m-login--2.m-login-2--skin-1 .m-login__container .m-login__form .m-login__form-action .m-login__btn {
    background-color : #506CEA !important;
    border : 1px solid #506CEA !important;
    border-radius: 5px !important;
}
.btn.m-btn--custom {
    font-size: 1.1rem !important;
    font-weight: 500;
      }
   
    </style>
	</head>

	<!-- end::Head -->

<!-- begin::Body -->
    <body  class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >

        
        
    	<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    
			
				<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-1" id="m_login" style="background :#fff !important">
	<div class="m-grid__item m-grid__item--fluid m-login__wrapper">
		<div class="m-login__container">
			<div class="m-login__logo">
				<a href="<?=BASE;?>">
				<img style="max-height: 50px;" src="<?=BASE;?>assets/uploads/user1/04b2dde2bd6264ef4409852a9457f13d.png">  	
				</a>
			</div>
			<div class="m-login__signin">
				<div class="m-login__head">
					<h3 class="m-login__title">Login</h3>
				</div>
				<form class="m-login__form m-form" action="" data-redirect="<?=get("redirect")?cn(get("redirect")):cn('dashboard')?>">
					<div class="form-group m-form__group">
						<input class="form-control m-input"   type="text" placeholder="Email" name="email" >
					</div>
					<div class="form-group m-form__group">
						<input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
					</div>
					<?php if(!empty(session('login_failed'))):?>
					<div class="form-group m-form__group" style="text-align: center;">
						<div style="margin-top:20px; display: inline-block;" class="g-recaptcha" data-sitekey="<?=get_option('google_recaptcha_sitekey', '6LdmV4cUAAAAALbSfMoU87pE2sKniP3uXTLF5i95');?>"></div>
					</div>
					<?php endif;?>
					<div class="row m-login__form-sub">
						<div class="col m--align-left m-login__form-left">
							<label class="m-checkbox  m-checkbox--light">
							<input type="checkbox" name="remember" value="on"> Remember me
							<span></span>
							</label>
						</div>
						<div class="col m--align-right m-login__form-right">
							<a href="javascript:;" id="m_login_forget_password" class="m-link" style="text-decoration:underline;">Lupa Password ?</a>
						</div>
					</div>
					<div class="m-login__form-action">
						<button id="m_login_signin_submit" class="btn m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary">Login</button>
					</div>
				</form>
			</div>
			<div class="m-login__signup">
				<div class="m-login__head">
					<h3 class="m-login__title">Daftar</h3>
					<div class="m-login__desc">Silahkan isi form di bawah ini :</div>
				</div>
				<form class="m-login__form m-form" action="">
					<div class="form-group m-form__group">
						<input class="form-control m-input" type="text" placeholder="Fullname" name="fullname">
					</div>
					<div class="form-group m-form__group">
						<input class="form-control m-input" type="email" placeholder="Email" name="email" >
					</div>
					<div class="form-group m-form__group">
						<input class="form-control m-input" type="text" placeholder="Nomor Whatsapp" name="whatsapp" >
					</div>
					<div class="form-group m-form__group">
						<input class="form-control m-input" type="password" placeholder="Password" name="password">
					</div>
					<div class="form-group m-form__group">
						<input class="form-control m-input m-login__form-input--last" type="password" placeholder="Confirm Password" name="confirm_password">
					</div>
					<div class="form-group m-form__group" style="text-align: center;">
						<div style="margin-top:20px; display: inline-block;" class="g-recaptcha" data-sitekey="<?=get_option('google_recaptcha_sitekey', '6LdmV4cUAAAAALbSfMoU87pE2sKniP3uXTLF5i95');?>"></div>
					</div>
					<div class="row form-group m-form__group m-login__form-sub">
						<div class="col m--align-left">
							<label class="m-checkbox m-checkbox--light">
							<input type="checkbox" name="terms">Saya menyetujui <a href="https://www.lancarin.com/p/term-of-service" class="m-link m-link--focus">terms and service</a>.
							<span></span>
							</label>
							<span class="m-form__help"></span>
						</div>
					</div>
					<div class="m-login__form-action">
						<button id="m_login_signup_submit" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Daftar</button>&nbsp;&nbsp;
						<button id="m_login_signup_cancel" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn">Batal</button>
					</div>
				</form>
			</div>
			<div class="m-login__forget-password">
				<div class="m-login__head">
					<h3 class="m-login__title">Lupa Password ?</h3>
					<div class="m-login__desc">Masukkan email Anda untuk reset password :</div>
				</div>
				<form class="m-login__form m-form" action="">
					<div class="form-group m-form__group">
						<input class="form-control m-input" type="text" placeholder="Email" name="email" id="m_email" >
					</div>
					<div class="m-login__form-action">
						<button id="m_login_forget_password_submit" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary">Request</button>&nbsp;&nbsp;
						<button id="m_login_forget_password_cancel" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn">Batal</button>
					</div>
				</form>
			</div>
			<div class="m-login__account">
				<span class="m-login__account-msg">
				Belum punya akun ?
				</span>&nbsp;
				<a href="<?=!empty(get('redirect'))?"javascript:;":site_url('#pricing');?>" id="m_login_signup" class="m-link m-link--light m-login__account-link">Daftar</a>
			</div>
		</div>
	</div>
</div>				
		

</div>
<!-- end:: Page -->
		<!--begin::Global Theme Bundle -->
		<script src="<?=base_url('assets/metronic/assets/vendors/base/vendors.bundle.js');?>" type="text/javascript"></script>
		<script src="<?=base_url('assets/metronic/assets/demo/default/base/scripts.bundle.js');?>" type="text/javascript"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.0/js.cookie.min.js"></script>			
		<!--end::Global Theme Bundle -->
		<script>
			$(function(){
				var refcode = Cookies.get('refcode');
				var SnippetLogin = function() {
				var redir = <?=!empty(get("redirect"))?1:0;?>;
				var e = $("#m_login"),
				i = function(e, type, text) {
					var l = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\t\t\t<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\t\t\t<span></span>\t\t</div>');console.log(e);
					e.find(".alert").remove(), l.prependTo(e), mUtil.animateClass(l[0], "fadeIn animated"), l.find("span").html(text);
				},
				a = function() {
					e.removeClass("m-login--forget-password"), e.removeClass("m-login--signup"), e.addClass("m-login--signin"), mUtil.animateClass(e.find(".m-login__signin")[0], "flipInX animated");
				},
				l = function() {
					$("#m_login_forget_password").click(function(ev) {
						ev.preventDefault(), e.removeClass("m-login--signin"), e.removeClass("m-login--signup"), e.addClass("m-login--forget-password"), mUtil.animateClass(e.find(".m-login__forget-password")[0], "flipInX animated")
					}), $("#m_login_forget_password_cancel").click(function(e) {
						e.preventDefault(), a()
					}), $("#m_login_signup").click(function(ev) {
						//i.preventDefault(), 
						if(redir==1){
						e.removeClass("m-login--forget-password"), e.removeClass("m-login--signin"), e.addClass("m-login--signup"), mUtil.animateClass(e.find(".m-login__signup")[0], "flipInX animated");
						}
					}), $("#m_login_signup_cancel").click(function(e) {
						e.preventDefault(), a()
					})
				};
				return {
        init: function() {
            l(), $("#m_login_signin_submit").click(function(e) {
                e.preventDefault();
                var a = $(this),
                    l = $(this).closest("form");
					formdata = l.serializeArray();
                formdata.push({'name': token_name, 'value': token_value});
                l.validate({
                    rules: {
                        email: {
                            required: !0,
                            email: !0
                        },
                        password: {
                            required: !0
                        }
                    }
                }), l.valid() && (a.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), l.ajaxSubmit({
                    url: '<?=site_url('auth/ajax_login');?>',
										data: formdata,
										type:'post',
										dataType: 'json',
                    success: function(e, t, r, s) {
                      if(e.status=='success'){
                        i(l, "success", e.message);
                          fbq('track', 'CompleteRegistration');
                          setTimeout(function(){
                          window.location = l.data('redirect');
                        },1500)
											}else{
												i(l, "danger", e.message);
												if(e.c){
													location.reload();
												}
											}
                        setTimeout(function() {
                            a.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        }, 2e3)
                    },
										error: function(d){
											i(l, "danger", 'Something wrong!');
											setTimeout(function() {
                            a.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        }, 2e3)
										}
                }))
            }), $("#m_login_signup_submit").click(function(l) {
                l.preventDefault();
                var t = $(this),
                    r = $(this).closest("form");
				formdata = r.serializeArray();
				formdata.push({'name': token_name, 'value': token_value});
				formdata.push({'name': 'refcode', 'value': refcode});
				formdata.push({'name': 'redirect', 'value': '<?=get('redirect');?>'});
				$.validator.addMethod("WHATSAPP",function(value,element){
					return this.optional(element) || /08+[0-9]{8,10}/.test(value);
				},"Invalid Phone Number. Format(08xxxx).");
                r.validate({
                    rules: {
                        fullname: {
                            required: !0
                        },
                        email: {
                            required: !0,
                            email: !0
                        },
                        whatsapp: {
                            required: !0,
							WHATSAPP: !0,
							minlength:10, 
							maxlength:14
                        },
                        password: {
                            required: !0
                        },
                        confirm_password: {
                            required: !0
                        },
                        agree: {
                            required: !0
                        }
                    }
                }), r.valid() && (t.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), r.ajaxSubmit({
                    url: '<?=site_url('auth/ajax_register');?>',
					data: formdata,
					type:'post',
					dataType: 'json',
                    success: function(l, s, n, o) {
						var ls = e.find(".m-login__signin form");
						if(l.status=='success'){
							ls.clearForm(), ls.validate().resetForm(), ls.find('input[name="email"]').val(r.find('input[name="email"]').val()), ls.find('input[name="password"]').val(r.find('input[name="password"]').val()), a(), i(ls, "success", l.message);$("#m_login_signin_submit").click();
						}else{
							//ls.clearForm(), ls.validate().resetForm(), 
							i(r, "danger", l.message)
						}
                        setTimeout(function() {
                            t.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
							//, r.clearForm(), r.validate().resetForm(), a();
                        }, 2e3)
						grecaptcha.reset();
                    },
					error: function(d){
						i(r, "danger", 'Something wrong!');
						setTimeout(function() {
							t.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
						}, 2e3)
						grecaptcha.reset();
					}
                }))
            }), $("#m_login_forget_password_submit").click(function(l) {
                l.preventDefault();
                var t = $(this),
                    r = $(this).closest("form");
								formdata = r.serializeArray();
                formdata.push({'name': token_name, 'value': token_value});
                r.validate({
                    rules: {
                        email: {
                            required: !0,
                            email: !0
                        }
                    }
                }), r.valid() && (t.addClass("m-loader m-loader--right m-loader--light").attr("disabled", !0), r.ajaxSubmit({
                     url: '<?=site_url('auth/ajax_forgot_password');?>',
										data: formdata,
										type:'post',
										dataType: 'json',
                    success: function(l, s, n, o) {
											var ls = e.find(".m-login__signin form");
											if(l.status=='success'){
												ls.clearForm(), ls.validate().resetForm(), a(), i(ls, "success", l.message);
											}else{
												i(r, "danger", l.message);
											}
											setTimeout(function() {
													t.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
											}, 2e3)
                    },
										error: function(d){
											i(r, "danger", 'Something wrong!');
											setTimeout(function() {
                            t.removeClass("m-loader m-loader--right m-loader--light").attr("disabled", !1)
                        }, 2e3)
										}
                }))
            })
			
			if(redir==1) $('#m_login_signup').click();
        }
		
		
        
    }
}();
				
				
        SnippetLogin.init();
       
			});
		</script>
		
		
	</body>

	<!-- end::Body -->
</html>