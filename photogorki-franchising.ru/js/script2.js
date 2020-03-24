jQuery(function($) {

var today = new Date(),
	months,
	day,
	dayTwo;
today = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 72, 0, 0);


months = ["января","февраля","марта","апреля","мая","июня","июля","авгуса","сентября","октября","ноября","дикабря"];
$("#endDate").text(today.getDate() + ' ' + months[today.getMonth()]);
    
months = ["января","февраля","марта","апреля","мая","июня","июля","авгуса","сентября","октября","ноября","дикабря"];
$("#endDat").text(today.getDate() + ' ' + months[today.getMonth()]);


Date.prototype.daysInMonth = function() {
    return 33 - new Date(this.getFullYear(), this.getMonth(), 33).getDate();
};


$('.timer').countdown({
  until: today,
  layout:

	'<div class="time_block"><div class="time">{dn}</div><div class="time_title">дни</div></div>'+
	'<div class="time_block"><div class="time">{hnn}</div><div class="time_title">час</div></div>'+
    '<div class="time_block"><div class="time">{mnn}</div><div class="time_title">мин</div></div>'+
    '<div class="time_block"><div class="time">{snn}</div><div class="time_title">сек</div></div>'+
	'<div class="clear"></div>'
});



});