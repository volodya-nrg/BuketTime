$(function(){
	$("a[class=a_float_vert_text]").hover(function(){
														var obj = $(this).find("div[mytag=text]");
														var parent = $(this).find("div[mytag=parent]");
														
														obj.animate({	
																		top: -5 + "px"
																	 }, "fast");
														
														obj.find("div[mytag=strelka]").fadeIn("fast");
														
														$(this).find("div[mytag=bg]")
															   .css("height", parent.height() + "px")
															   .css("width", 243 + "px")
															   .fadeIn("fast");	
													},
										   function(){
											   			var obj = $(this).find("div[mytag=strelka]");
														var parent = $(this).find("div[mytag=text]");
														
											   			obj.fadeOut("fast");
														
														parent.animate({	
																			top: 0 + "px"
																	    }, "fast");
														
														$(this).find("div[mytag=bg]").fadeOut("fast");
													 });
	$("div[class=div_icon_small]").hover(function(){
												   		if($(this).hasClass("selected")) return;
														
														$(this).find("img").animate({
																		"top": -10 + "px"
																	 }, "fast");
												    },
										 function(){
											 			if($(this).hasClass("selected")) return;
														
											       		$(this).find("img").animate({
																		"top": 0 + "px"
																	 }, "fast");
												    });
	$("ul[class=ul_fag] li[mytag=true] a[mytag=true]").toggle(function(){
													$(this).parent().find("div[mytag=true]").slideDown();
													$(this).css("color", "black");
											   },
									function(){
										       		$(this).parent().find("div[mytag=true]").slideUp();
													$(this).hover(function(){
																			 	$(this).css("color", "black");
																			 },
																  function(){
																	   		 	$(this).css("color", "#558401");
																			 })
											   });
	$("input[type=text][class=input_with_desc], textarea[class=input_with_desc]").each(function(){
										  	$(this).attr("value", $(this).attr("mytag"))
												   .css("color", "#ccc")
												   .bind("focus", function(){
													   							var str = $.trim($(this).attr("value"));
													   							if( str == $(this).attr("mytag") )
																					$(this).attr("value", "");
																				
																				$(this).css("color", "black");
																			 })
												   .bind("blur", function(){
													   							if( $.trim($(this).attr("value")) == "" )
																					$(this).attr("value", $(this).attr("mytag"))
																						   .css("color", "#ccc");
																			});
										  });
	$("#admin_panel").css({
								left: (($(document).width() - $("#admin_panel").width())/2)-20 + "px"
						   });
	$("#div_go_up").hover(
							 function(){
								var my_opacisy = getCurOpacityScroll() + 0.2;
								
								if(my_opacisy > 1) my_opacisy = 1;
								 
								$(this).css("opacity", my_opacisy);
							 },
							 function(){
							 	$(this).css("opacity", getCurOpacityScroll());
							 }
						   )
					.bind("click", function(){
													$('body, html').stop().animate({
																					scrollTop: 0
																				  }, 800);
											 });
							 
	$("#reg_form").find("input[type=radio][name=radio]").bind("click", function(){
			$("#reg_form div.div_reg").removeClass("selected");
			$(this).parent().parent().addClass("selected");
		});											
	$("span[class=span_schedle_img_icon]").live("click", function(){
																		var obj = $(this).find("input[type=checkbox]");
																		
																		if(obj.attr("checked")){
																			obj.attr("checked", false);
																			$(this).css("border-color", "#eee");	
																		}else{
																			obj.attr("checked", true);
																			$(this).css("border-color", "#999");
																		} 
												  			 		});
	$("input[type=checkbox][mytag=schedle_img_icon]").live("click", function(){
																					$(this).parent().click();	
																			   });
	$("#div_change_logo_img_on_index").css({ "margin-left": ($("div[class=div_in_index_baner]").width() + 10) + "px" });
	$("#a_account").toggle(function(){
										$(this).removeClass("account");
										$(this).removeClass("a_top_menu");
										
										$(this).addClass("account_press");
									  },
						   function(){
										$(this).removeClass("account_press");
										
										$(this).addClass("account");
										$(this).addClass("a_top_menu");
									  });
	$("span[class=payment]").live("click", function(){
													  	$(this).find("input").attr("checked", "checked");
													  });	  
	if( $("#div_place_all_big_foto").size() ) $("div.div_icon_small").first().click();
	
	changeBgImageOnIndex(1);
	bodyOnResize();
	$(window).resize(function() { bodyOnResize();});
	$(window).scroll(function(){  
									var pogresh = 0;
									if($.browser.msie) pogresh = 9;
										
									if($(window).scrollTop() > 160){
										$("#div_top_link_float").css({
																	  	position: "absolute",
																		top: $(window).scrollTop() + "px",
																		left: (($(document).width() - 980)/2) - pogresh + "px",
																		"z-index": 99,
																		width: 980 + "px"
																	  });
										$("#div_top_link_float_shadow").fadeIn();	
									}else{
										$("#div_top_link_float").css({
																	  	position: "static"
																	  });
										$("#div_top_link_float_shadow").fadeOut();
									}
									
									if($(window).scrollTop() > 190){
										$("#div_checkout_now").css({
																	  	position: "absolute",
																		top: $(window).scrollTop() + "px",
																		left: (($(document).width() - 980)/2) + (980 - 170) - pogresh + "px",
																		"z-index": 98
																	  });	
									}else{
										$("#div_checkout_now").css({
																	  	position: "static"
																	  });
									}
									
									$("#div_go_up").css({"opacity": getCurOpacityScroll()});
		                       });
});

var setInt = false;
var key_ajax = false;

function bodyOnResize(){
	$("#div_top_link_float").css({ left: (($(document).width() - 980)/2) + "px"});
	
	$("#div_checkout_now").css({ left: (($(document).width() - 980)/2) + (980 - 170) + "px"});

	$("#div_go_up").css({
							top: $(window).height() - 70 + "px",
							left: $(window).width() - 70 + "px",
							opacity: getCurOpacityScroll()
						});
	
	var wH = $(window).height();
	var h = $("#div_top_header_menu").height() + $("div[class=div_body]").height() + $("#div_footer_sait").height();
	
	if(wH > h){
		h = wH - $("#div_top_header_menu").height() - $("#div_footer_sait").height();
		
		$("div[class=div_body]").css({
										height: h + "px"
									  });	
	}
}
function changeBgImageOnIndex(val){
	if(window.setInt) clearInterval(window.setInt);
	
	if( ($("div[mytag=bg_image_on_index_" + val + "]").css("display") == "block") ||
		($("div[mytag=bg_image_on_index_" + val + "]").css("display") == "inline") ) return;
		
	$("div[mytag^=bg_image_on_index_]").fadeOut();
	$("div[mytag=bg_image_on_index_" + val + "]").fadeIn();
	
	$("a[mytag^=btn_bg_img_index_]").css({
										  	color: "gray",
											"font-weight": "normal"
										  })
								    .attr("active", "false");
	$("a[mytag^=btn_bg_img_index_" + val + "]").css({
														color: "white",
														"font-weight": "bold"
													  })
											   .attr("active", "true");
	
	window.setInt = setInterval(setIntBgImageOnIndex, 6000);	
}
function setIntBgImageOnIndex(){
	var total = $("div[mytag^=bg_image_on_index_]").size();
	
	var j=1;
	for(var i=1; i<=total; i++){
		
		if( ($("div[mytag=bg_image_on_index_" + i + "]").css("display") == "block") ||
			($("div[mytag=bg_image_on_index_" + i + "]").css("display") == "inline") )
			j=++i;
	}
	
	if(j>total) j=1;
	
	changeBgImageOnIndex(j);
}
function getCurOpacityScroll(){
		var cur_scroll = $(window).scrollTop();
		var stepen = 0;							
									
		if( (cur_scroll < 220) ) 							 { stepen = 0;   display = "none"; }
		else if( (cur_scroll >= 220) && (cur_scroll < 240) ) { stepen = 0.1; display = "block"; }
		else if( (cur_scroll >= 240) && (cur_scroll < 260) ) { stepen = 0.2; display = "block"; }
		else if( (cur_scroll >= 260) && (cur_scroll < 280) ) { stepen = 0.3; display = "block"; }
		else if( (cur_scroll >= 280) && (cur_scroll < 300) ) { stepen = 0.4; display = "block"; }
		else if( (cur_scroll >= 300) && (cur_scroll < 320) ) { stepen = 0.5; display = "block"; }
		else if( (cur_scroll >= 320) && (cur_scroll < 340) ) { stepen = 0.6; display = "block"; }
		else if( (cur_scroll >= 340) && (cur_scroll < 360) ) { stepen = 0.7; display = "block"; }
		else if( (cur_scroll >= 360) && (cur_scroll < 380) ) { stepen = 0.8; display = "block"; }
		else if( (cur_scroll >= 380) && (cur_scroll < 400) ) { stepen = 0.9; display = "block"; }
		else 												 { stepen = 1;   display = "block"; }
		
		$("#div_go_up").css("display", display);
		
		return stepen;
}
function sendPass(obj){
	if(window.key_ajax) return;
	var email = $.trim($("#send_mail").val());
	
	if(email == ""){
		alert("Ошибка: впишите eмэйл!");
		return;	
	}
	
	window.key_ajax = true;
	$.get("/backend.php", "send_pass=" + email, function(data){
		alert(data.message);
		if(data.result) window.location.href="/";
		window.key_ajax = false;													 		   			
	}, 'json');
}

// -----------------------------------------------------------------
function send_subscribe(obj){
	if(window.key_ajax) return;
	
	var mail = $.trim($("#input_subscribe").val());
	if(mail == "") return;
	if(mail == $("#input_subscribe").attr("mytag")){
		alert("Ошибка: впишите корректный е-мэйл!");
		return;
	} 
	
	$(obj).find("img").fadeIn("fast");
	window.key_ajax = true;
	$.get("/backend.php", "send_subscribe=" + mail, function(data){
			
			if(data.result){
				$("#input_subscribe").val("");
				alert(data.message);
			}else alert(data.message);
			
			window.key_ajax = false;
			$(obj).find("img").fadeOut("fast");
		
		}, "json");
}
function showSlideOneFoto(obj, val){
	if(val == "next" || val == "prev"){
		var mytag = $("div.div_icon_small.selected").attr("mytag");
		var temp = parseInt(mytag);
		(val == "next")? temp++: temp--;
		val = temp;
	}
	
	if( parseInt($(obj).attr("mytag")) == val && $(obj).hasClass("selected") ) return;
	
	(val<2)? $("#div_prev").hide(): $("#div_prev").show();
	(val>$("a.a_under_top_link").size()-1)? $("#div_next").hide(): $("#div_next").show();
	
	$("a.a_under_top_link").removeClass("selected");
	$("div.div_icon_small.selected").find("img").animate({"top": 0 + "px"}, "fast", function(){$(this).parent().removeClass("selected")});
	
	$("a.a_under_top_link[mytag=" + val + "]").addClass("selected");
	$("div.div_icon_small[mytag=" + val + "]").find("img").animate({"top": -10 + "px"}, "fast", function(){$(this).parent().addClass("selected")});
	$("#div_place_all_big_foto").animate({
										  	"margin-left": (980 * --val * -1) + "px"
										  }, 1000);
}
function changeSchedleBtn(obj, mytag){
	$("button[mytag=" + mytag + "]").removeClass("mybtn_select")
									.attr("disabled", false)
									.addClass("mybtn");
	$(obj).removeClass("mybtn")
		  .attr("disabled", true)
		  .addClass("mybtn_select");
}
function collectDataFromSchedle(curObj, collection_id){
	
	var how_often 		= $("button.mybtn_select[mytag=how_often]").val();
	var what_day  		= $("button.mybtn_select[mytag=on_what_days]").val();
	var bouquets 		= new Array();
	var text_for_a_card = $.trim($("#schedle_text_on_postcard").val());
	var address 		= $.trim($("#schedle_address").val());
	var metro 			= $("#schedle_metro").val();
	var tel 			= $.trim($("#schedle_tel").val());
	var dop_info 		= $.trim($("#schedle_dop_info").val());
	var option_to_buy 	= $("input[type=radio][name=schedle_oplata]:checked").val();
	
	$("input[type=checkbox][mytag=schedle_img_icon]:checked").each(function(){
		bouquets.push($(this).val());	
	});
	
	var err = "";
	if(!bouquets.length) 	err += "- выберите букет\n";
	if(address == "") 		err += "- заполните поле адрес\n";
	if(tel == "") 			err += "- укажите номер телефона";
	
	if(err != ""){
		err = "Ошибка:\n" + err;
		alert(err);
		return;	
	}
	
	window.key_ajax = true;
	$.post("/backend.php", {collection_id: collection_id,
							how_often: how_often,
							what_day: what_day,
							bouquets: bouquets,
							text_for_a_card: text_for_a_card,
							address: address,
							metro: metro,
							tel: tel,
							dop_info: dop_info,
							option_to_buy: option_to_buy
							}, function(data){
		if(data.result && data.message == 1){
			$("body").append(data.chronopay);
			$("#form_for_chronopay").submit();
		}else if(data.result && data.message == 2){
			window.location.href = "/payment_success/";
		}else{
			alert(data.message);
		}
		window.key_ajax = false;
	}, "json");
}
function update_cart(curCbj){
	if(window.key_ajax) return;
	var obj = new Object;
	$("div[class=div_one_cell_cart]").each(function(){
															var key = $(this).attr("mytag");
															var count = $(this).find("input[type=text]").val();
															obj[key]=count;
													  });

	window.key_ajax = true;
	$.get("/backend.php", {update_cart: obj}, function(data){
		if(data.result) window.location.href="/cart.php";
		window.key_ajax = false;
	}, "json");
}
function orderCart(){
	if(window.key_ajax) return;
	window.key_ajax = true;
	$.get("/backend.php", "show_choose_cart=1", function(data){
		data.result? getDarkScreen(data.message): alert(data.message);
		window.key_ajax = false;
	}, "json");
}
function myGetDate(param){
	var aMonthName = new Array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
	
	var d = new Date();
	var myData = new Object();
	
	if(!window.month_hidden) myData["month"] = d.getMonth();
	else myData["month"] = window.month_hidden;
	
	if(!window.year_hidden)	myData["year"] = d.getFullYear();
	else myData["year"] = window.year_hidden;
	
	if(param == 1){
		window.month_hidden++;
		myData["month"] = window.month_hidden;
	} 
	else if(param == 0){
		window.month_hidden--;
		myData["month"] = window.month_hidden;
	} 
	
	if(myData["month"]>11){
		window.month_hidden=0;
		myData["month"]=window.month_hidden;
		
		window.year_hidden++;
		myData["year"] = window.year_hidden;	
	}
	else if(myData["month"]<0){
		window.month_hidden=11;
		myData["month"]=window.month_hidden;
		
		window.year_hidden--;
		myData["year"] = window.year_hidden;	
	}
	
	myData["monthName"] = aMonthName[myData["month"]];
	
	myData["howManyDays"] = new Date(myData["year"], myData["month"]+1, 0).getDate();
	myData["holost"]	  = new Date(myData["year"], myData["month"], 0).getDay();
	
	var str = "";
	
	for(var i=0; i<myData["holost"]; i++){
		str += "<span mytag=holost></span>";
	}
	
	var temp_d = new Date();
	
	/*------------------------- вычесляем текущую точку даты -------------------------*/
	if(temp_d.getMonth() < 10) 	var str_month = "0" + String(temp_d.getMonth());
	else 						var str_month= String(temp_d.getMonth());
	
	if(temp_d.getDate() < 10) 	var str_day = "0" + String(temp_d.getDate());
	else 						var str_day = String(temp_d.getDate());
	
	var str_year = String(temp_d.getFullYear());	
	var cur_control_point = str_year + str_month + str_day;
	cur_control_point = Number(cur_control_point);
	/*------------------------- конец -------------------------*/
	
	for(var i=1; i<=myData["howManyDays"]; i++){
		if( (temp_d.getFullYear() + "-" + temp_d.getMonth() + "-" + temp_d.getDate()) == (myData["year"] + "-" + myData["month"] + "-" + i) ) 
			var dob = "selected=true"; 
		else  
			var dob = "";
		
		/*------------------------- делаем проверку на не кликабельные ранние даты -------------------------*/
		if(myData["month"] < 10) 	str_month = "0" + String(myData["month"]);
		else 						str_month = String(myData["month"]);
		
		if(i < 10) str_day = "0" + String(i);
		else       str_day = String(i);
		
		str_year = String(myData["year"]);	
		var_control_point = str_year + str_month + str_day;
		var_control_point = Number(var_control_point);
		/*------------------------- конец -------------------------*/
		
		if(cur_control_point > var_control_point)
			str += "<span mytag=dayNumNotActive>" + i + "</span>";
		else
			str += "<span " + dob + " mytag=dayNum onclick=\"$('#cart_last_date').val('" + myData["year"] + "-" + (myData["month"]+1) + "-" + i + "'); $('div[class=calendar]').fadeOut();\">" + i + "</span>";
	}
	
	if($("div[mytag=place_for_day]").size())
		$("div[mytag=place_for_day]").html(str);
	else myData["days"] = str;
	
	if($("td[mytag=month_year]").size())
		$("td[mytag=month_year]").html(myData["monthName"] + " " + myData["year"]);
	
	$("span[selected=true]").addClass("dayNumSel").attr("onclick", "").css("cursor", "default");
	
	return myData;	
}
function getDarkScreen(html, marginTop, marginBottom){
	if(html == undefined) html = "";
	
	var totalDarkScreen = $(".dark_screen").size();
	var z_index = 100 + totalDarkScreen;
	var marginTop = (marginTop == undefined || marginTop == "")? 40: marginTop;
	var marginBottom = (marginBottom == undefined || marginBottom=="")? 40: marginBottom;
	
	if($('body').css('overflow') != 'hidden') $('body').css('overflow', 'hidden');
	
	$("body").append('<div align=center class=dark_screen mytag=' + z_index + ' style="z-index:' + z_index + ';"></div>');
	
	$(".dark_screen[mytag=" + z_index + "]").css({
														height: $(window).height() + "px",
														top: $(window).scrollTop() + "px"
												  })
											.html('<div mytag=popUpCarcas>' + html + '</div>');
	
	$(".dark_screen[mytag=" + z_index + "] div[mytag=popUpCarcas]").css({
																		 	"margin-top": marginTop + "px",
																			"margin-bottom": marginBottom + "px",
																			width: 800 + "px"	
																		 })
																   .bind("click", function(event){ event.stopPropagation(); });
		
	$(".dark_screen[mytag=" + z_index + "]").fadeIn("fast");
	
	$(".dark_screen[mytag=" + z_index + "]").one('click', function(){
		if(window.reload_page) window.location.href="";
		else $(this).fadeOut("fast", function(){ $(this).remove(); if(totalDarkScreen == 0) $('body').css('overflow', 'auto'); });
	});
}
function collectFinalDataFromcartSendAndBuy(curObj){
	var err = "";
	var obj = new Object();
	
	obj["text_on_postcard"] = "";
	obj["ifo"] = "";
	obj["address"] = "";
	obj["metro"] = "";
	obj["date"] = "";
	obj["tel"] = "";
	obj["mail"] = "";
	obj["dop_info"] = "";
	
	obj["payment_type_group_id"] = Number($("input[type=radio][name=payment_type_group_id]:checked").val());
	if( (obj["payment_type_group_id"] != 1)  && 
		(obj["payment_type_group_id"] != 15) && 
		(obj["payment_type_group_id"] != 16) &&
		(obj["payment_type_group_id"] != 21) &&
		(obj["payment_type_group_id"] != 22) 	){
		obj["payment_type_group_id"] = 1;		
	}
	
	obj_temp = $.trim($("#cart_last_text_on_postcard").val());
	if( obj_temp != "" ) obj["text_on_postcard"] = obj_temp;
	
	obj_temp = $.trim($("#cart_last_ifo").val());
	if( obj_temp != "" ) obj["ifo"] = obj_temp;
	
	obj_temp = $.trim($("#cart_last_address").val());
	if( obj_temp != "" ) obj["address"] = obj_temp;
	
	obj_temp = $.trim($("#cart_last_metro").find("option:selected").text());
	if( obj_temp != "" ) obj["metro"] = obj_temp;
	
	obj_temp = $.trim($("#cart_last_date").val());
	if( obj_temp != "" ) obj["date"] = obj_temp;
	
	obj_temp = $.trim($("#cart_last_tel").val());
	if( obj_temp != "" ) obj["tel"] = obj_temp;
	
	obj_temp = $.trim($("#cart_last_mail").val());
	if( obj_temp != "" ) obj["mail"] = obj_temp;
	
	obj_temp = $.trim($("#cart_last_dop_info").val());
	if( obj_temp != "" ) obj["dop_info"] = obj_temp;
	
	if(obj["tel"] 		== "") 	err += "Ошибка: необходим ваш номер телефона для уточнения заказа!\n";
	if(obj["ifo"]		== "") 	err += "Ошибка: впишите имя/фамилию!\n";
	if(obj["address"]	== "") 	err += "Ошибка: необходим адресс доставки подарока(ов)!\n";
	if(obj["mail"] 		== "") 	err += "Ошибка: необходим мэйл для фиксации заказа!\n";
	if(obj["date"] 		== "") 	err += "Ошибка: необходима дата доставки!\n";
	
	if(err != ""){
		alert(err);
		return;	
	}
	
	showHideWhenDoAjaxReguest(curObj, true);
	$.get("/backend.php", {cart_send_data: obj}, function(data){
		if(data.result){
			if(data.message == 1) window.location.href="/payment_success/";
			else if(data.message == 2){
				$("body").append(data.chronopay);
				$("#form_for_chronopay").submit();
			}
		}else{
			alert(data.message);
			getWhiteScreen(false); 
			$("#btn_from_cart_send").find("img").css("display", "none");	
		}	
	}, "json");
}
function showHideWhenDoAjaxReguest(obj, flag){
	if(flag){
		getWhiteScreen(true);
		$(obj).find("img").css("display", "block");	
	}
	else{
		getWhiteScreen(false);
		$(obj).find("img").css("display", "none");
	}
}
function changeOnOffSubscriptions(obj, id){
	if(obj.attr("class") == "mybtn_select") return;
	if(window.key_ajax) return;
	
	window.key_ajax = true;
	$.get("/backend.php", "on_off_id=" + id, function(data){
		window.key_ajax = false;	
	}, "json");	
}
function deleteSubscriptions(id){
	if(confirm("Вы уверены что хотите удалить подписку ?")){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.get("/backend.php", "deleteSubscriptions=" + id, function(data){
			window.key_ajax = false;
			window.location.href="/profile.php?subscriptions";	
		}, "json");	
	}
}
function getWhiteScreen(flag){
	if(!flag){ 
		if($("#div_white_screen").length) $("#div_white_screen").remove(); 
		return;
	}
	
	$("body").append('<div id=div_white_screen></div>');
	$("#div_white_screen").css({ display: "block",
								width: 100 + "%",
								height: 100 + "%",
								position: "fixed",
								"background-color": "white",
								opacity: 0.7,
								top: 0 + "px",
								left: 0 + "px",
								"z-index": 2000
								 });
}
function slideDopGifts(obj){
	if($(obj).hasClass("selected")) return;
	
	var padding = 20;
	var height = $("div.dop_gift").first().height() + padding;
	var total = $("div.dop_gift").size();
	var predel = (total - 3) * height;
	var minTop = 0;
	var maxTop = total * $("div.dop_gift").first().height();
	var curTop = parseInt($("#indicator").css("top").slice(0,-2));
	alert(curTop);
	if($(obj).attr("mytag") == "top"){
		curTop -= height;
	}else{
		curTop += height;
	}
	$("#indicator").animate({top: curTop +  "px"}, "fast", function(){});
	
	if( $("div.arrow_vert").hasClass("selected") ) $("div.arrow_vert").removeClass("selected");
	
	if( curTop<0 && curTop <= (-1*predel) ) $(obj).addClass("selected");
	if( curTop>=0 ) $(obj).addClass("selected");
}
function addDopGiftInCart(obj, id){
	if($(obj).hasClass("btn_gray") || window.key_ajax) return;
	
	window.key_ajax = true;
	$.get("/backend.php", "addDopGiftInCartId=" + id, function(data){
		if(data.result){
			$(obj).removeClass("btn_black").addClass("btn_gray");
			$("#trash_elem").html(data.total);	
		}else
			alert("Ошибка!");
		window.key_ajax = false;	 
	}, 'json');
}
function getPage(url){
	window.location.href = url;
}

Account = {
	updatePass: function(obj){
		if(window.key_ajax) return;
		
		var curPass = $("#setting_cur_pass").val();
		var newPass = $("#setting_new_pass").val();
		var confirmNewPass = $("#setting_new_pass_confirm").val();
		
		if(curPass == "" || newPass == "" || confirmNewPass == ""){
			alert("Ошибка: заполните все поля для изменения пароля!");
			return;	
		}
		if(newPass != confirmNewPass){
			alert("Ошибка: пароли не совпадают!");
			return;	
		}
		
		window.key_ajax = true;
		$(obj).find("img").show();
		$.get("/backend.php", "curPass=" + curPass + "&newPass=" + newPass + "&confirmNewPass=" + confirmNewPass, function(data){
				
				if(data.result) window.location.href="";
				else alert(data.message);
				
				window.key_ajax = false;
				$(obj).find("img").hide();
			}, "json");
	},
	updateToSendNews: function(obj){
		if(window.key_ajax) return;
		
		var checkbox = $("#setting_send_news").is(":checked");
		checkbox = checkbox? 1: 0;
		
		window.key_ajax = true;
		$(obj).find("img").show();
		$.get("/backend.php", "setting_send_news=" + checkbox, function(data){
				
				if(data.result) window.location.href="";
				else alert("Ошибка: данные не обновились!");
				
				window.key_ajax = false;
				$(obj).find("img").hide();
			}, "json"); 
	},
	updateBankCard: function(obj){
		if(window.key_ajax) return;
		
		var name 	= $("#setting_name").val();
		var address = $("#setting_billing_address").val();
		var city 	= $("#setting_town").val();
		var country = $("#setting_country").val();
		var tel 	= $("#setting_tel").val();
		
		if(name == "" || address == "" || city == "" || country == "" || tel == ""){
			alert("Ошибка: заполните все поля!");
			return;	
		}
		
		window.key_ajax = true;
		$(obj).find("img").show();
		$.get("/backend.php", "name=" + name + "&address=" + address + "&city=" + city + "&country=" + country + "&tel=" + tel, function(data){
				
				if(data.result) window.location.href="";
				else alert(data.message);
				
				window.key_ajax = false;
				$(obj).find("img").hide();
			}, "json");
	}
}
Admin = {
	getAboutUs: function(){
		$.get("/backend.php", "opt=about_us", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setAboutUs: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "about_us",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},
	getAgreement: function(){
		$.get("/backend.php", "opt=agreement", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setAgreement: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "agreement",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},
	getBenefitsForMembers: function(){
		$.get("/backend.php", "opt=BenefitsForMembers", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setBenefitsForMembers: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "BenefitsForMembers",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},
	getBusiness: function(){
		$.get("/backend.php", "opt=business", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setBusiness: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "business",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},
	getDeliver: function(){
		$.get("/backend.php", "opt=deliver", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setDeliver: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "deliver",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},
	getOneTimeDelivery: function(){
		$.get("/backend.php", "opt=OneTimeDelivery", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setOneTimeDelivery: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "OneTimeDelivery",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},
	getContact: function(){
		$.get("/backend.php", "opt=contact", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setContact: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "contact",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},
	getFaq: function(){
		$.get("/backend.php", "opt=faq", function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setFaq: function(){
		if(window.key_ajax) return;
		
		window.key_ajax = true;
		$.post("/backend.php", {
									opt: "faq",
									set: 1,
									title: 			$.trim($("#admin_title").val()),
									meta_desc: 		$.trim($("#admin_meta_desc").val()),
									meta_keywords: 	$.trim($("#admin_meta_keywords").val()),
									content: 		$.trim($("#admin_textarea").val())
								}, function(data){
			data.result? window.location.href="": alert("Ошибка!");
			window.key_ajax = false;
		}, "json");	
	},

	getBlogItem: function(id){
		if(id == undefined || id == "") id="";
		$.get("/backend.php", "opt=blogItem&id=" + id, function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");		
	},
	setBlogItem: function(id){
		if(window.key_ajax) return;
		if(id == undefined || id == "") id="";
		window.key_ajax = true;
		
		$("#insertCode").ajaxStart(function(){
			
		}).ajaxComplete(function(){
			window.location.href="";
		});
		$.ajaxFileUpload( {url:'/backend.php', 
						   data: {
									opt: "blogItem",
									set: id,
									title: 			encodeURI($.trim($("#admin_title").val())),
									meta_desc: 		encodeURI($.trim($("#admin_meta_desc").val())),
									meta_keywords: 	encodeURI($.trim($("#admin_meta_keywords").val())),
									content: 		encodeURI($.trim($("#admin_textarea").val())) 
								  },
						   secureuri:false, 
						   fileElementId:"admin_file",
						   dataType: 'json'});
	},
	delBlogItem: function(id){
		if(id == undefined || id == "") return;
		if(confirm("Вы точно хотите удалить блог?")){
			$.get("/backend.php", "opt=blogItem&id=" + id + "&delete=1", function(data){
				if(data.result){
					window.location.href="";
				}else
					alert("Ошибка!");
			}, "json");		
		}
	},
	getCollections: function(id){
		if(id == undefined || id == "") id="";
		$.get("/backend.php", "opt=collection&id=" + id, function(data){
			if(data.result){
				getDarkScreen(data.html);
				//$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");	
	},
	setCollection: function(id){
		if(window.key_ajax) return;
		if(id == undefined || id == "") id="";
		window.key_ajax = true;
		
		$("#insertCode").ajaxStart(function(){
			
		}).ajaxComplete(function(){
			window.location.href="";
		});
		$.ajaxFileUpload( {url:'/backend.php', 
						   data: {
									opt: "collection",
									set: id,
									title: 			encodeURI($.trim($("#admin_title").val())),
									meta_desc: 		encodeURI($.trim($("#admin_meta_desc").val())),
									meta_keywords: 	encodeURI($.trim($("#admin_meta_keywords").val())),
									content: 		encodeURI($.trim($("#admin_textarea").val())),
									cost:			encodeURI($.trim($("#admin_cost").val())),
									discount: 		encodeURI($.trim($("#admin_discount").val())),
								  },
						   secureuri:false, 
						   fileElementId:"admin_file",
						   dataType: 'json'});
	},
	delCollection: function(id){
		if(id == undefined || id == "") return;
		if(confirm("Вы точно хотите удалить коллекцию?")){
			$.get("/backend.php", "opt=collection&id=" + id + "&delete=1", function(data){
				if(data.result){
					window.location.href="";
				}else
					alert("Ошибка!");
			}, "json");		
		}
	},
	getGift: function(id){
		if(id == undefined || id == "") id="";
		$.get("/backend.php", "opt=gift&id=" + id, function(data){
			if(data.result){
				getDarkScreen(data.html);
				$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");	
	},
	setGift: function(id){
		if(window.key_ajax) return;
		if(id == undefined || id == "") id="";
		window.key_ajax = true;
		
		$("#insertCode").ajaxStart(function(){
			
		}).ajaxComplete(function(){
			window.location.href="";
		});
		$.ajaxFileUpload( {url:'/backend.php', 
						   data: {
									opt: "gift",
									set: id,
									title: 			encodeURI($.trim($("#admin_title").val())),
									title_page: 	encodeURI($.trim($("#admin_title_page").val())),
									meta_desc: 		encodeURI($.trim($("#admin_meta_desc").val())),
									meta_keywords: 	encodeURI($.trim($("#admin_meta_keywords").val())),
									content: 		encodeURI($.trim($("#admin_textarea").val())),
									cost:			encodeURI($.trim($("#admin_cost").val())),
									discount: 		encodeURI($.trim($("#admin_discount").val())),
									dop_gifts:		encodeURI($.trim($("#admin_dop_gifts").val()))
								  },
						   secureuri:false, 
						   fileElementId:"admin_file",
						   dataType: 'json'});
	},
	delGift: function(id){
		if(id == undefined || id == "") return;
		if(confirm("Вы точно хотите удалить коллекцию?")){
			$.get("/backend.php", "opt=gift&id=" + id + "&delete=1", function(data){
				if(data.result){
					window.location.href="";
				}else
					alert("Ошибка!");
			}, "json");		
		}
	},
	getPressa: function(id){
		if(id == undefined || id == "") id="";
		$.get("/backend.php", "opt=pressa&id=" + id, function(data){
			if(data.result){
				getDarkScreen(data.html);
				//$("#admin_textarea").redactor().setCode(data.content);	
			}else
				alert("Ошибка!");
		}, "json");	
	},
	setPressa: function(id){
		if(window.key_ajax) return;
		if(id == undefined || id == "") id="";
		window.key_ajax = true;
		
		$("#insertCode").ajaxStart(function(){
			
		}).ajaxComplete(function(){
			window.location.href="";
		});
		$.ajaxFileUpload( {url:'/backend.php', 
						   data: {
									opt: "pressa",
									set: id,
									link: 			encodeURI($.trim($("#admin_link").val())),
									content: 		encodeURI($.trim($("#admin_textarea").val()))
								  },
						   secureuri:false, 
						   fileElementId:"admin_file",
						   dataType: 'json'});
	},
	delPressa: function(id){
		if(id == undefined || id == "") return;
		if(confirm("Вы точно хотите удалить?")){
			$.get("/backend.php", "opt=pressa&id=" + id + "&delete=1", function(data){
				if(data.result){
					window.location.href="";
				}else
					alert("Ошибка!");
			}, "json");		
		}
	}
}