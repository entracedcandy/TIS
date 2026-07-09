function AddMasks()
{
  MaskedInput({
    elm: document.getElementById('ex-1'),
    format: 'MM/DD/YYYY',
    onbadkey: function(){
      ShakeShake({ elm: document.getElementById('ex-1') });
    }, badkeywait: 400
  });
  MaskedInput({
    elm: document.getElementById('ex-2'),
    format: '____-__-__',
    onbadkey: function(){
      ShakeShake({ elm: document.getElementById('ex-2') });
    }, badkeywait: 400
  });
  
  //input_daily_pellet_mill.htm
  MaskedInput({
    elm: document.getElementById('jam_start'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_start') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jam_end'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_end') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jam_start_downtime'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_start_downtime') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jam_end_downtime'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_end_downtime') });
    }, badkeywait: 90
  });
  
   //input_daily_extruder.htm
   MaskedInput({
    elm: document.getElementById('jam_start_ua'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_start_ua') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jam_end_ua'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_end_ua') });
    }, badkeywait: 90
  });
  
  
  
  
  //input daily feedtech
  MaskedInput({
    elm: document.getElementById('time_created'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('time_created') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('time_created_transfer'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('time_created_transfer') });
    }, badkeywait: 90
  });
  
  
  
  
  
  //input_pellet_mill_inventory.htm
  MaskedInput({
    elm: document.getElementById('jam'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam') });
    }, badkeywait: 90
  });
    
  MaskedInput({
    elm: document.getElementById('jam_change_status'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_change_status') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jam_awal_pasang'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_awal_pasang') });
    }, badkeywait: 90
  });
  
  
  
  
  
  
  //report_mixer_daily_usage.htm
  MaskedInput({
    elm: document.getElementById('jam_start_full'),
    format: '00:00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_start_full') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jam_end_full'),
    format: '00:00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_end_full') });
    }, badkeywait: 90
  });
  
  
  
  
  
  
  
  
  //dynamic_report
  MaskedInput({
    elm: document.getElementById('jam_1'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_1') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jam_2'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_2') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jamfull_1'),
    format: '00:00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_full_1') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('jamfull_2'),
    format: '00:00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('jam_full_2') });
    }, badkeywait: 90
  });
  
  
  
  
  
    //input_ot
  MaskedInput({
    elm: document.getElementById('start'),
    format: '0000-00-00 00:00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('start') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('finish'),
    format: '0000-00-00 00:00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('finish') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('BreakDuration'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('BreakDuration') });
    }, badkeywait: 90
  });


  
  //input_spl
  MaskedInput({
    elm: document.getElementById('input_bduration'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('input_bduration') });
    }, badkeywait: 90
  });

  MaskedInput({
    elm: document.getElementById('input_tduration'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('input_tduration') });
    }, badkeywait: 90
  });
  
  
  //Work Order request
  MaskedInput({
    elm: document.getElementById('timestart'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('timestart') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('actualtime'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('actualtime') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('start_work'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('start_work') });
    }, badkeywait: 90
  });
  
   MaskedInput({
    elm: document.getElementById('input_start'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('input_start') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('input_finish'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('input_finish') });
    }, badkeywait: 90
  });
   MaskedInput({
    elm: document.getElementById('input_start_downtime'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('input_start_downtime') });
    }, badkeywait: 90
  });
  MaskedInput({
    elm: document.getElementById('input_finish_downtime'),
    format: '00:00', separator: ':', typeon: '0',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('input_finish_downtime') });
    }, badkeywait: 90
  });
  
  
  
  MaskedInput({
    elm: document.getElementById('ex-4'),
    format: '(___) ___-____', separator: '()- ',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('ex-4'),
      prop: 'color', delta: 120, color: '#f00' });
    }, badkeywait: 130
  });
  MaskedInput({
    elm: document.getElementById('ex-text'),
    format: '____\n____\n____\n____', separator: '\n',
    allowed: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
    onbadkey: function(){
      FlashBang({ elm: document.getElementById('ex-text'),
      prop: 'color', delta: 120, color: '#f00' });
    }, badkeywait: 130
  });
}
function FlashBang(args)
{
  try {
    var elm = args['elm'],
      color = args['color'] || '#f55',
      prop  = args['prop']  || 'backgroundColor',
      delta = args['delta'] || 80,
      origStyle = elm.style[prop];
    elm.style[prop] = color;
    setTimeout(function(){ FlashOff(); },delta);
  } catch(e) { return; }
  function FlashOff() { elm.style[prop] = origStyle; }
}
function appendOnLoad(fx) { 
  try { // For browsers that know DOMContentLoaded (FF, Safari, Opera)
    document.addEventListener("DOMContentLoaded", fx, false);
  } catch(e) {
    var old = window.onload;
    if (typeof old != 'function') { window.onload = fx; }
    else { window.onload = function() { old(); fx(); } }
  }
}
appendOnLoad(AddMasks);