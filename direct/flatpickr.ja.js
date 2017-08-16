/* Japanese locals for flatpickr */
var Flatpickr = Flatpickr || { l10ns: {} };
Flatpickr.l10ns.ja = {};

Flatpickr.l10ns.ja.weekdays = {
	shorthand: ['日', '月', '火', '水', '木', '金', '土'],
	longhand:  ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日']
};

Flatpickr.l10ns.ja.months = {
	shorthand: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
	longhand:  ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
};
if (typeof module !== 'undefined') {
	module.exports = Flatpickr.l10ns;
}

const flatpickrConfig = {
	locale: 'ja',
	enableTime: true,
	time_24hr: true,
};

$(function(){
	$('.date').flatpickr($.extend({}, flatpickrConfig, {enableTime: false}));
	$('.datelist').flatpickr($.extend({}, flatpickrConfig, {enableTime: false, mode: 'multiple'}));
	$('.datetime').flatpickr($.extend({}, flatpickrConfig, {minuteIncrement: 5}));
	$('.datetime5').flatpickr($.extend({}, flatpickrConfig, {minuteIncrement: 5}));
	$('.datetime10').flatpickr($.extend({}, flatpickrConfig, {minuteIncrement: 10}));
	$('.datetime15').flatpickr($.extend({}, flatpickrConfig, {minuteIncrement: 15}));
	$('.datetime30').flatpickr($.extend({}, flatpickrConfig, {minuteIncrement: 30}));
	$('.datetime60').flatpickr($.extend({}, flatpickrConfig, {minuteIncrement: 60}));
	$('.time').flatpickr($.extend({}, flatpickrConfig, {noCalendar: true, minuteIncrement: 5}));
	$('.time5').flatpickr($.extend({}, flatpickrConfig, {noCalendar: true, minuteIncrement: 5}));
	$('.time10').flatpickr($.extend({}, flatpickrConfig, {noCalendar: true, minuteIncrement: 10}));
	$('.time15').flatpickr($.extend({}, flatpickrConfig, {noCalendar: true, minuteIncrement: 15}));
	$('.time30').flatpickr($.extend({}, flatpickrConfig, {noCalendar: true, minuteIncrement: 30}));
	$('.time60').flatpickr($.extend({}, flatpickrConfig, {noCalendar: true, minuteIncrement: 60}));
});
