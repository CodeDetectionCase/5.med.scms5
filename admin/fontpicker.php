<?php 
require '../function/conn.php';
require '../function/function.php';
?>
<link rel="stylesheet" href="../css/css/font-awesome.min.css?v=<?php echo gen_key(20)?>" type="text/css" />
<script src="js/jquery.min.js?v=<?php echo gen_key(20)?>"></script> <!-- 必需 -->
<style>
.box{padding: 10px;display: inline-block;cursor:pointer }
.box:hover{background: #DDDDDD;}
</style>
<script>
$(function(){
$(".box").click(function(){
    $('#U_ico', window.parent.document).val($(this).attr("data-f"));
    $('#U_icox', window.parent.document).html('<i class="fa fa-'+$(this).attr("data-f")+' fa-2x"></i>');
    parent.toastr.success("已选择图标："+$(this).attr("data-f"));
});
})
</script>




<div class='box' data-f='adjust'><i class='fa fa-adjust'></i></div>
<div class='box' data-f='anchor'><i class='fa fa-anchor'></i></div>
<div class='box' data-f='archive'><i class='fa fa-archive'></i></div>
<div class='box' data-f='area-chart'><i class='fa fa-area-chart'></i></div>
<div class='box' data-f='arrows'><i class='fa fa-arrows'></i></div>
<div class='box' data-f='arrows-h'><i class='fa fa-arrows-h'></i></div>
<div class='box' data-f='arrows-v'><i class='fa fa-arrows-v'></i></div>
<div class='box' data-f='asterisk'><i class='fa fa-asterisk'></i></div>
<div class='box' data-f='at'><i class='fa fa-at'></i></div>
<div class='box' data-f='automobile'><i class='fa fa-automobile'></i></div>
<div class='box' data-f='balance-scale'><i class='fa fa-balance-scale'></i></div>
<div class='box' data-f='ban'><i class='fa fa-ban'></i></div>
<div class='box' data-f='bank'><i class='fa fa-bank'></i></div>
<div class='box' data-f='bar-chart'><i class='fa fa-bar-chart'></i></div>
<div class='box' data-f='bar-chart-o'><i class='fa fa-bar-chart-o'></i></div>
<div class='box' data-f='barcode'><i class='fa fa-barcode'></i></div>
<div class='box' data-f='bars'><i class='fa fa-bars'></i></div>
<div class='box' data-f='battery-0'><i class='fa fa-battery-0'></i></div>
<div class='box' data-f='battery-1'><i class='fa fa-battery-1'></i></div>
<div class='box' data-f='battery-2'><i class='fa fa-battery-2'></i></div>
<div class='box' data-f='battery-3'><i class='fa fa-battery-3'></i></div>
<div class='box' data-f='battery-4'><i class='fa fa-battery-4'></i></div>
<div class='box' data-f='battery-empty'><i class='fa fa-battery-empty'></i></div>
<div class='box' data-f='battery-full'><i class='fa fa-battery-full'></i></div>
<div class='box' data-f='battery-half'><i class='fa fa-battery-half'></i></div>
<div class='box' data-f='battery-quarter'><i class='fa fa-battery-quarter'></i></div>
<div class='box' data-f='battery-three-quarters'><i class='fa fa-battery-three-quarters'></i></div>
<div class='box' data-f='bed'><i class='fa fa-bed'></i></div>
<div class='box' data-f='beer'><i class='fa fa-beer'></i></div>
<div class='box' data-f='bell'><i class='fa fa-bell'></i></div>
<div class='box' data-f='bell-o'><i class='fa fa-bell-o'></i></div>
<div class='box' data-f='bell-slash'><i class='fa fa-bell-slash'></i></div>
<div class='box' data-f='bell-slash-o'><i class='fa fa-bell-slash-o'></i></div>
<div class='box' data-f='bicycle'><i class='fa fa-bicycle'></i></div>
<div class='box' data-f='binoculars'><i class='fa fa-binoculars'></i></div>
<div class='box' data-f='birthday-cake'><i class='fa fa-birthday-cake'></i></div>
<div class='box' data-f='bolt'><i class='fa fa-bolt'></i></div>
<div class='box' data-f='bomb'><i class='fa fa-bomb'></i></div>
<div class='box' data-f='book'><i class='fa fa-book'></i></div>
<div class='box' data-f='bookmark'><i class='fa fa-bookmark'></i></div>
<div class='box' data-f='bookmark-o'><i class='fa fa-bookmark-o'></i></div>
<div class='box' data-f='briefcase'><i class='fa fa-briefcase'></i></div>
<div class='box' data-f='bug'><i class='fa fa-bug'></i></div>
<div class='box' data-f='building'><i class='fa fa-building'></i></div>
<div class='box' data-f='building-o'><i class='fa fa-building-o'></i></div>
<div class='box' data-f='bullhorn'><i class='fa fa-bullhorn'></i></div>
<div class='box' data-f='bullseye'><i class='fa fa-bullseye'></i></div>
<div class='box' data-f='bus'><i class='fa fa-bus'></i></div>
<div class='box' data-f='cab'><i class='fa fa-cab'></i></div>
<div class='box' data-f='calculator'><i class='fa fa-calculator'></i></div>
<div class='box' data-f='calendar'><i class='fa fa-calendar'></i></div>
<div class='box' data-f='calendar-check-o'><i class='fa fa-calendar-check-o'></i></div>
<div class='box' data-f='calendar-minus-o'><i class='fa fa-calendar-minus-o'></i></div>
<div class='box' data-f='calendar-o'><i class='fa fa-calendar-o'></i></div>
<div class='box' data-f='calendar-plus-o'><i class='fa fa-calendar-plus-o'></i></div>
<div class='box' data-f='calendar-times-o'><i class='fa fa-calendar-times-o'></i></div>
<div class='box' data-f='camera'><i class='fa fa-camera'></i></div>
<div class='box' data-f='camera-retro'><i class='fa fa-camera-retro'></i></div>
<div class='box' data-f='car'><i class='fa fa-car'></i></div>
<div class='box' data-f='caret-square-o-down'><i class='fa fa-caret-square-o-down'></i></div>
<div class='box' data-f='caret-square-o-left'><i class='fa fa-caret-square-o-left'></i></div>
<div class='box' data-f='caret-square-o-right'><i class='fa fa-caret-square-o-right'></i></div>
<div class='box' data-f='caret-square-o-up'><i class='fa fa-caret-square-o-up'></i></div>
<div class='box' data-f='cart-arrow-down'><i class='fa fa-cart-arrow-down'></i></div>
<div class='box' data-f='cart-plus'><i class='fa fa-cart-plus'></i></div>
<div class='box' data-f='cc'><i class='fa fa-cc'></i></div>
<div class='box' data-f='certificate'><i class='fa fa-certificate'></i></div>
<div class='box' data-f='check'><i class='fa fa-check'></i></div>
<div class='box' data-f='check-circle'><i class='fa fa-check-circle'></i></div>
<div class='box' data-f='check-circle-o'><i class='fa fa-check-circle-o'></i></div>
<div class='box' data-f='check-square'><i class='fa fa-check-square'></i></div>
<div class='box' data-f='check-square-o'><i class='fa fa-check-square-o'></i></div>
<div class='box' data-f='child'><i class='fa fa-child'></i></div>
<div class='box' data-f='circle'><i class='fa fa-circle'></i></div>
<div class='box' data-f='circle-o'><i class='fa fa-circle-o'></i></div>
<div class='box' data-f='circle-o-notch'><i class='fa fa-circle-o-notch'></i></div>
<div class='box' data-f='circle-thin'><i class='fa fa-circle-thin'></i></div>
<div class='box' data-f='clock-o'><i class='fa fa-clock-o'></i></div>
<div class='box' data-f='clone'><i class='fa fa-clone'></i></div>
<div class='box' data-f='close'><i class='fa fa-close'></i></div>
<div class='box' data-f='cloud'><i class='fa fa-cloud'></i></div>
<div class='box' data-f='cloud-download'><i class='fa fa-cloud-download'></i></div>
<div class='box' data-f='cloud-upload'><i class='fa fa-cloud-upload'></i></div>
<div class='box' data-f='code'><i class='fa fa-code'></i></div>
<div class='box' data-f='code-fork'><i class='fa fa-code-fork'></i></div>
<div class='box' data-f='coffee'><i class='fa fa-coffee'></i></div>
<div class='box' data-f='cog'><i class='fa fa-cog'></i></div>
<div class='box' data-f='cogs'><i class='fa fa-cogs'></i></div>
<div class='box' data-f='comment'><i class='fa fa-comment'></i></div>
<div class='box' data-f='comment-o'><i class='fa fa-comment-o'></i></div>
<div class='box' data-f='commenting'><i class='fa fa-commenting'></i></div>
<div class='box' data-f='commenting-o'><i class='fa fa-commenting-o'></i></div>
<div class='box' data-f='comments'><i class='fa fa-comments'></i></div>
<div class='box' data-f='comments-o'><i class='fa fa-comments-o'></i></div>
<div class='box' data-f='compass'><i class='fa fa-compass'></i></div>
<div class='box' data-f='copyright'><i class='fa fa-copyright'></i></div>
<div class='box' data-f='creative-commons'><i class='fa fa-creative-commons'></i></div>
<div class='box' data-f='credit-card'><i class='fa fa-credit-card'></i></div>
<div class='box' data-f='crop'><i class='fa fa-crop'></i></div>
<div class='box' data-f='crosshairs'><i class='fa fa-crosshairs'></i></div>
<div class='box' data-f='cube'><i class='fa fa-cube'></i></div>
<div class='box' data-f='cubes'><i class='fa fa-cubes'></i></div>
<div class='box' data-f='cutlery'><i class='fa fa-cutlery'></i></div>
<div class='box' data-f='dashboard'><i class='fa fa-dashboard'></i></div>
<div class='box' data-f='database'><i class='fa fa-database'></i></div>
<div class='box' data-f='desktop'><i class='fa fa-desktop'></i></div>
<div class='box' data-f='diamond'><i class='fa fa-diamond'></i></div>
<div class='box' data-f='dot-circle-o'><i class='fa fa-dot-circle-o'></i></div>
<div class='box' data-f='download'><i class='fa fa-download'></i></div>
<div class='box' data-f='edit'><i class='fa fa-edit'></i></div>
<div class='box' data-f='ellipsis-h'><i class='fa fa-ellipsis-h'></i></div>
<div class='box' data-f='ellipsis-v'><i class='fa fa-ellipsis-v'></i></div>
<div class='box' data-f='envelope'><i class='fa fa-envelope'></i></div>
<div class='box' data-f='envelope-o'><i class='fa fa-envelope-o'></i></div>
<div class='box' data-f='envelope-square'><i class='fa fa-envelope-square'></i></div>
<div class='box' data-f='eraser'><i class='fa fa-eraser'></i></div>
<div class='box' data-f='exchange'><i class='fa fa-exchange'></i></div>
<div class='box' data-f='exclamation'><i class='fa fa-exclamation'></i></div>
<div class='box' data-f='exclamation-circle'><i class='fa fa-exclamation-circle'></i></div>
<div class='box' data-f='exclamation-triangle'><i class='fa fa-exclamation-triangle'></i></div>
<div class='box' data-f='external-link'><i class='fa fa-external-link'></i></div>
<div class='box' data-f='external-link-square'><i class='fa fa-external-link-square'></i></div>
<div class='box' data-f='eye'><i class='fa fa-eye'></i></div>
<div class='box' data-f='eye-slash'><i class='fa fa-eye-slash'></i></div>
<div class='box' data-f='eyedropper'><i class='fa fa-eyedropper'></i></div>
<div class='box' data-f='fax'><i class='fa fa-fax'></i></div>
<div class='box' data-f='feed'><i class='fa fa-feed'></i></div>
<div class='box' data-f='female'><i class='fa fa-female'></i></div>
<div class='box' data-f='fighter-jet'><i class='fa fa-fighter-jet'></i></div>
<div class='box' data-f='file-archive-o'><i class='fa fa-file-archive-o'></i></div>
<div class='box' data-f='file-audio-o'><i class='fa fa-file-audio-o'></i></div>
<div class='box' data-f='file-code-o'><i class='fa fa-file-code-o'></i></div>
<div class='box' data-f='file-excel-o'><i class='fa fa-file-excel-o'></i></div>
<div class='box' data-f='file-image-o'><i class='fa fa-file-image-o'></i></div>
<div class='box' data-f='file-movie-o'><i class='fa fa-file-movie-o'></i></div>
<div class='box' data-f='file-pdf-o'><i class='fa fa-file-pdf-o'></i></div>
<div class='box' data-f='file-photo-o'><i class='fa fa-file-photo-o'></i></div>
<div class='box' data-f='file-picture-o'><i class='fa fa-file-picture-o'></i></div>
<div class='box' data-f='file-powerpoint-o'><i class='fa fa-file-powerpoint-o'></i></div>
<div class='box' data-f='file-sound-o'><i class='fa fa-file-sound-o'></i></div>
<div class='box' data-f='file-video-o'><i class='fa fa-file-video-o'></i></div>
<div class='box' data-f='file-word-o'><i class='fa fa-file-word-o'></i></div>
<div class='box' data-f='file-zip-o'><i class='fa fa-file-zip-o'></i></div>
<div class='box' data-f='film'><i class='fa fa-film'></i></div>
<div class='box' data-f='filter'><i class='fa fa-filter'></i></div>
<div class='box' data-f='fire'><i class='fa fa-fire'></i></div>
<div class='box' data-f='fire-extinguisher'><i class='fa fa-fire-extinguisher'></i></div>
<div class='box' data-f='flag'><i class='fa fa-flag'></i></div>
<div class='box' data-f='flag-checkered'><i class='fa fa-flag-checkered'></i></div>
<div class='box' data-f='flag-o'><i class='fa fa-flag-o'></i></div>
<div class='box' data-f='flash'><i class='fa fa-flash'></i></div>
<div class='box' data-f='flask'><i class='fa fa-flask'></i></div>
<div class='box' data-f='folder'><i class='fa fa-folder'></i></div>
<div class='box' data-f='folder-o'><i class='fa fa-folder-o'></i></div>
<div class='box' data-f='folder-open'><i class='fa fa-folder-open'></i></div>
<div class='box' data-f='folder-open-o'><i class='fa fa-folder-open-o'></i></div>
<div class='box' data-f='frown-o'><i class='fa fa-frown-o'></i></div>
<div class='box' data-f='futbol-o'><i class='fa fa-futbol-o'></i></div>
<div class='box' data-f='gamepad'><i class='fa fa-gamepad'></i></div>
<div class='box' data-f='gavel'><i class='fa fa-gavel'></i></div>
<div class='box' data-f='gear'><i class='fa fa-gear'></i></div>
<div class='box' data-f='gears'><i class='fa fa-gears'></i></div>
<div class='box' data-f='gift'><i class='fa fa-gift'></i></div>
<div class='box' data-f='glass'><i class='fa fa-glass'></i></div>
<div class='box' data-f='globe'><i class='fa fa-globe'></i></div>
<div class='box' data-f='graduation-cap'><i class='fa fa-graduation-cap'></i></div>
<div class='box' data-f='group'><i class='fa fa-group'></i></div>
<div class='box' data-f='hand-grab-o'><i class='fa fa-hand-grab-o'></i></div>
<div class='box' data-f='hand-lizard-o'><i class='fa fa-hand-lizard-o'></i></div>
<div class='box' data-f='hand-paper-o'><i class='fa fa-hand-paper-o'></i></div>
<div class='box' data-f='hand-peace-o'><i class='fa fa-hand-peace-o'></i></div>
<div class='box' data-f='hand-pointer-o'><i class='fa fa-hand-pointer-o'></i></div>
<div class='box' data-f='hand-rock-o'><i class='fa fa-hand-rock-o'></i></div>
<div class='box' data-f='hand-scissors-o'><i class='fa fa-hand-scissors-o'></i></div>
<div class='box' data-f='hand-spock-o'><i class='fa fa-hand-spock-o'></i></div>
<div class='box' data-f='hand-stop-o'><i class='fa fa-hand-stop-o'></i></div>
<div class='box' data-f='hdd-o'><i class='fa fa-hdd-o'></i></div>
<div class='box' data-f='headphones'><i class='fa fa-headphones'></i></div>
<div class='box' data-f='heart'><i class='fa fa-heart'></i></div>
<div class='box' data-f='heart-o'><i class='fa fa-heart-o'></i></div>
<div class='box' data-f='heartbeat'><i class='fa fa-heartbeat'></i></div>
<div class='box' data-f='history'><i class='fa fa-history'></i></div>
<div class='box' data-f='home'><i class='fa fa-home'></i></div>
<div class='box' data-f='hotel'><i class='fa fa-hotel'></i></div>
<div class='box' data-f='hourglass'><i class='fa fa-hourglass'></i></div>
<div class='box' data-f='hourglass-1'><i class='fa fa-hourglass-1'></i></div>
<div class='box' data-f='hourglass-2'><i class='fa fa-hourglass-2'></i></div>
<div class='box' data-f='hourglass-3'><i class='fa fa-hourglass-3'></i></div>
<div class='box' data-f='hourglass-end'><i class='fa fa-hourglass-end'></i></div>
<div class='box' data-f='hourglass-half'><i class='fa fa-hourglass-half'></i></div>
<div class='box' data-f='hourglass-o'><i class='fa fa-hourglass-o'></i></div>
<div class='box' data-f='hourglass-start'><i class='fa fa-hourglass-start'></i></div>
<div class='box' data-f='i-cursor'><i class='fa fa-i-cursor'></i></div>
<div class='box' data-f='image'><i class='fa fa-image'></i></div>
<div class='box' data-f='inbox'><i class='fa fa-inbox'></i></div>
<div class='box' data-f='industry'><i class='fa fa-industry'></i></div>
<div class='box' data-f='info'><i class='fa fa-info'></i></div>
<div class='box' data-f='info-circle'><i class='fa fa-info-circle'></i></div>
<div class='box' data-f='institution'><i class='fa fa-institution'></i></div>
<div class='box' data-f='key'><i class='fa fa-key'></i></div>
<div class='box' data-f='keyboard-o'><i class='fa fa-keyboard-o'></i></div>
<div class='box' data-f='language'><i class='fa fa-language'></i></div>
<div class='box' data-f='laptop'><i class='fa fa-laptop'></i></div>
<div class='box' data-f='leaf'><i class='fa fa-leaf'></i></div>
<div class='box' data-f='legal'><i class='fa fa-legal'></i></div>
<div class='box' data-f='lemon-o'><i class='fa fa-lemon-o'></i></div>
<div class='box' data-f='level-down'><i class='fa fa-level-down'></i></div>
<div class='box' data-f='level-up'><i class='fa fa-level-up'></i></div>
<div class='box' data-f='life-bouy'><i class='fa fa-life-bouy'></i></div>
<div class='box' data-f='life-buoy'><i class='fa fa-life-buoy'></i></div>
<div class='box' data-f='life-ring'><i class='fa fa-life-ring'></i></div>
<div class='box' data-f='life-saver'><i class='fa fa-life-saver'></i></div>
<div class='box' data-f='lightbulb-o'><i class='fa fa-lightbulb-o'></i></div>
<div class='box' data-f='line-chart'><i class='fa fa-line-chart'></i></div>
<div class='box' data-f='location-arrow'><i class='fa fa-location-arrow'></i></div>
<div class='box' data-f='lock'><i class='fa fa-lock'></i></div>
<div class='box' data-f='magic'><i class='fa fa-magic'></i></div>
<div class='box' data-f='magnet'><i class='fa fa-magnet'></i></div>
<div class='box' data-f='mail-forward'><i class='fa fa-mail-forward'></i></div>
<div class='box' data-f='mail-reply'><i class='fa fa-mail-reply'></i></div>
<div class='box' data-f='mail-reply-all'><i class='fa fa-mail-reply-all'></i></div>
<div class='box' data-f='male'><i class='fa fa-male'></i></div>
<div class='box' data-f='map'><i class='fa fa-map'></i></div>
<div class='box' data-f='map-marker'><i class='fa fa-map-marker'></i></div>
<div class='box' data-f='map-o'><i class='fa fa-map-o'></i></div>
<div class='box' data-f='map-pin'><i class='fa fa-map-pin'></i></div>
<div class='box' data-f='map-signs'><i class='fa fa-map-signs'></i></div>
<div class='box' data-f='meh-o'><i class='fa fa-meh-o'></i></div>
<div class='box' data-f='microphone'><i class='fa fa-microphone'></i></div>
<div class='box' data-f='microphone-slash'><i class='fa fa-microphone-slash'></i></div>
<div class='box' data-f='minus'><i class='fa fa-minus'></i></div>
<div class='box' data-f='minus-circle'><i class='fa fa-minus-circle'></i></div>
<div class='box' data-f='minus-square'><i class='fa fa-minus-square'></i></div>
<div class='box' data-f='minus-square-o'><i class='fa fa-minus-square-o'></i></div>
<div class='box' data-f='mobile'><i class='fa fa-mobile'></i></div>
<div class='box' data-f='mobile-phone'><i class='fa fa-mobile-phone'></i></div>
<div class='box' data-f='money'><i class='fa fa-money'></i></div>
<div class='box' data-f='moon-o'><i class='fa fa-moon-o'></i></div>
<div class='box' data-f='mortar-board'><i class='fa fa-mortar-board'></i></div>
<div class='box' data-f='motorcycle'><i class='fa fa-motorcycle'></i></div>
<div class='box' data-f='mouse-pointer'><i class='fa fa-mouse-pointer'></i></div>
<div class='box' data-f='music'><i class='fa fa-music'></i></div>
<div class='box' data-f='navicon'><i class='fa fa-navicon'></i></div>
<div class='box' data-f='newspaper-o'><i class='fa fa-newspaper-o'></i></div>
<div class='box' data-f='object-group'><i class='fa fa-object-group'></i></div>
<div class='box' data-f='object-ungroup'><i class='fa fa-object-ungroup'></i></div>
<div class='box' data-f='paint-brush'><i class='fa fa-paint-brush'></i></div>
<div class='box' data-f='paper-plane'><i class='fa fa-paper-plane'></i></div>
<div class='box' data-f='paper-plane-o'><i class='fa fa-paper-plane-o'></i></div>
<div class='box' data-f='paw'><i class='fa fa-paw'></i></div>
<div class='box' data-f='pencil'><i class='fa fa-pencil'></i></div>
<div class='box' data-f='pencil-square'><i class='fa fa-pencil-square'></i></div>
<div class='box' data-f='pencil-square-o'><i class='fa fa-pencil-square-o'></i></div>
<div class='box' data-f='phone'><i class='fa fa-phone'></i></div>
<div class='box' data-f='phone-square'><i class='fa fa-phone-square'></i></div>
<div class='box' data-f='photo'><i class='fa fa-photo'></i></div>
<div class='box' data-f='picture-o'><i class='fa fa-picture-o'></i></div>
<div class='box' data-f='pie-chart'><i class='fa fa-pie-chart'></i></div>
<div class='box' data-f='plane'><i class='fa fa-plane'></i></div>
<div class='box' data-f='plug'><i class='fa fa-plug'></i></div>
<div class='box' data-f='plus'><i class='fa fa-plus'></i></div>
<div class='box' data-f='plus-circle'><i class='fa fa-plus-circle'></i></div>
<div class='box' data-f='plus-square'><i class='fa fa-plus-square'></i></div>
<div class='box' data-f='plus-square-o'><i class='fa fa-plus-square-o'></i></div>
<div class='box' data-f='power-off'><i class='fa fa-power-off'></i></div>
<div class='box' data-f='print'><i class='fa fa-print'></i></div>
<div class='box' data-f='puzzle-piece'><i class='fa fa-puzzle-piece'></i></div>
<div class='box' data-f='qrcode'><i class='fa fa-qrcode'></i></div>
<div class='box' data-f='question'><i class='fa fa-question'></i></div>
<div class='box' data-f='question-circle'><i class='fa fa-question-circle'></i></div>
<div class='box' data-f='quote-left'><i class='fa fa-quote-left'></i></div>
<div class='box' data-f='quote-right'><i class='fa fa-quote-right'></i></div>
<div class='box' data-f='random'><i class='fa fa-random'></i></div>
<div class='box' data-f='recycle'><i class='fa fa-recycle'></i></div>
<div class='box' data-f='refresh'><i class='fa fa-refresh'></i></div>
<div class='box' data-f='registered'><i class='fa fa-registered'></i></div>
<div class='box' data-f='remove'><i class='fa fa-remove'></i></div>
<div class='box' data-f='reorder'><i class='fa fa-reorder'></i></div>
<div class='box' data-f='reply'><i class='fa fa-reply'></i></div>
<div class='box' data-f='reply-all'><i class='fa fa-reply-all'></i></div>
<div class='box' data-f='retweet'><i class='fa fa-retweet'></i></div>
<div class='box' data-f='road'><i class='fa fa-road'></i></div>
<div class='box' data-f='rocket'><i class='fa fa-rocket'></i></div>
<div class='box' data-f='rss'><i class='fa fa-rss'></i></div>
<div class='box' data-f='rss-square'><i class='fa fa-rss-square'></i></div>
<div class='box' data-f='search'><i class='fa fa-search'></i></div>
<div class='box' data-f='search-minus'><i class='fa fa-search-minus'></i></div>
<div class='box' data-f='search-plus'><i class='fa fa-search-plus'></i></div>
<div class='box' data-f='send'><i class='fa fa-send'></i></div>
<div class='box' data-f='send-o'><i class='fa fa-send-o'></i></div>
<div class='box' data-f='server'><i class='fa fa-server'></i></div>
<div class='box' data-f='share'><i class='fa fa-share'></i></div>
<div class='box' data-f='share-alt'><i class='fa fa-share-alt'></i></div>
<div class='box' data-f='share-alt-square'><i class='fa fa-share-alt-square'></i></div>
<div class='box' data-f='share-square'><i class='fa fa-share-square'></i></div>
<div class='box' data-f='share-square-o'><i class='fa fa-share-square-o'></i></div>
<div class='box' data-f='shield'><i class='fa fa-shield'></i></div>
<div class='box' data-f='ship'><i class='fa fa-ship'></i></div>
<div class='box' data-f='shopping-cart'><i class='fa fa-shopping-cart'></i></div>
<div class='box' data-f='sign-in'><i class='fa fa-sign-in'></i></div>
<div class='box' data-f='sign-out'><i class='fa fa-sign-out'></i></div>
<div class='box' data-f='signal'><i class='fa fa-signal'></i></div>
<div class='box' data-f='sitemap'><i class='fa fa-sitemap'></i></div>
<div class='box' data-f='sliders'><i class='fa fa-sliders'></i></div>
<div class='box' data-f='smile-o'><i class='fa fa-smile-o'></i></div>
<div class='box' data-f='soccer-ball-o'><i class='fa fa-soccer-ball-o'></i></div>
<div class='box' data-f='sort'><i class='fa fa-sort'></i></div>
<div class='box' data-f='sort-alpha-asc'><i class='fa fa-sort-alpha-asc'></i></div>
<div class='box' data-f='sort-alpha-desc'><i class='fa fa-sort-alpha-desc'></i></div>
<div class='box' data-f='sort-amount-asc'><i class='fa fa-sort-amount-asc'></i></div>
<div class='box' data-f='sort-amount-desc'><i class='fa fa-sort-amount-desc'></i></div>
<div class='box' data-f='sort-asc'><i class='fa fa-sort-asc'></i></div>
<div class='box' data-f='sort-desc'><i class='fa fa-sort-desc'></i></div>
<div class='box' data-f='sort-down'><i class='fa fa-sort-down'></i></div>
<div class='box' data-f='sort-numeric-asc'><i class='fa fa-sort-numeric-asc'></i></div>
<div class='box' data-f='sort-numeric-desc'><i class='fa fa-sort-numeric-desc'></i></div>
<div class='box' data-f='sort-up'><i class='fa fa-sort-up'></i></div>
<div class='box' data-f='space-shuttle'><i class='fa fa-space-shuttle'></i></div>
<div class='box' data-f='spinner'><i class='fa fa-spinner'></i></div>
<div class='box' data-f='spoon'><i class='fa fa-spoon'></i></div>
<div class='box' data-f='square'><i class='fa fa-square'></i></div>
<div class='box' data-f='square-o'><i class='fa fa-square-o'></i></div>
<div class='box' data-f='star'><i class='fa fa-star'></i></div>
<div class='box' data-f='star-half'><i class='fa fa-star-half'></i></div>
<div class='box' data-f='star-half-empty'><i class='fa fa-star-half-empty'></i></div>
<div class='box' data-f='star-half-full'><i class='fa fa-star-half-full'></i></div>
<div class='box' data-f='star-half-o'><i class='fa fa-star-half-o'></i></div>
<div class='box' data-f='star-o'><i class='fa fa-star-o'></i></div>
<div class='box' data-f='sticky-note'><i class='fa fa-sticky-note'></i></div>
<div class='box' data-f='sticky-note-o'><i class='fa fa-sticky-note-o'></i></div>
<div class='box' data-f='street-view'><i class='fa fa-street-view'></i></div>
<div class='box' data-f='suitcase'><i class='fa fa-suitcase'></i></div>
<div class='box' data-f='sun-o'><i class='fa fa-sun-o'></i></div>
<div class='box' data-f='support'><i class='fa fa-support'></i></div>
<div class='box' data-f='tablet'><i class='fa fa-tablet'></i></div>
<div class='box' data-f='tachometer'><i class='fa fa-tachometer'></i></div>
<div class='box' data-f='tag'><i class='fa fa-tag'></i></div>
<div class='box' data-f='tags'><i class='fa fa-tags'></i></div>
<div class='box' data-f='tasks'><i class='fa fa-tasks'></i></div>
<div class='box' data-f='taxi'><i class='fa fa-taxi'></i></div>
<div class='box' data-f='television'><i class='fa fa-television'></i></div>
<div class='box' data-f='terminal'><i class='fa fa-terminal'></i></div>
<div class='box' data-f='thumb-tack'><i class='fa fa-thumb-tack'></i></div>
<div class='box' data-f='thumbs-down'><i class='fa fa-thumbs-down'></i></div>
<div class='box' data-f='thumbs-o-down'><i class='fa fa-thumbs-o-down'></i></div>
<div class='box' data-f='thumbs-o-up'><i class='fa fa-thumbs-o-up'></i></div>
<div class='box' data-f='thumbs-up'><i class='fa fa-thumbs-up'></i></div>
<div class='box' data-f='ticket'><i class='fa fa-ticket'></i></div>
<div class='box' data-f='times'><i class='fa fa-times'></i></div>
<div class='box' data-f='times-circle'><i class='fa fa-times-circle'></i></div>
<div class='box' data-f='times-circle-o'><i class='fa fa-times-circle-o'></i></div>
<div class='box' data-f='tint'><i class='fa fa-tint'></i></div>
<div class='box' data-f='toggle-down'><i class='fa fa-toggle-down'></i></div>
<div class='box' data-f='toggle-left'><i class='fa fa-toggle-left'></i></div>
<div class='box' data-f='toggle-off'><i class='fa fa-toggle-off'></i></div>
<div class='box' data-f='toggle-on'><i class='fa fa-toggle-on'></i></div>
<div class='box' data-f='toggle-right'><i class='fa fa-toggle-right'></i></div>
<div class='box' data-f='toggle-up'><i class='fa fa-toggle-up'></i></div>
<div class='box' data-f='trademark'><i class='fa fa-trademark'></i></div>
<div class='box' data-f='trash'><i class='fa fa-trash'></i></div>
<div class='box' data-f='trash-o'><i class='fa fa-trash-o'></i></div>
<div class='box' data-f='tree'><i class='fa fa-tree'></i></div>
<div class='box' data-f='trophy'><i class='fa fa-trophy'></i></div>
<div class='box' data-f='truck'><i class='fa fa-truck'></i></div>
<div class='box' data-f='tty'><i class='fa fa-tty'></i></div>
<div class='box' data-f='tv'><i class='fa fa-tv'></i></div>
<div class='box' data-f='umbrella'><i class='fa fa-umbrella'></i></div>
<div class='box' data-f='university'><i class='fa fa-university'></i></div>
<div class='box' data-f='unlock'><i class='fa fa-unlock'></i></div>
<div class='box' data-f='unlock-alt'><i class='fa fa-unlock-alt'></i></div>
<div class='box' data-f='unsorted'><i class='fa fa-unsorted'></i></div>
<div class='box' data-f='upload'><i class='fa fa-upload'></i></div>
<div class='box' data-f='user'><i class='fa fa-user'></i></div>
<div class='box' data-f='user-plus'><i class='fa fa-user-plus'></i></div>
<div class='box' data-f='user-secret'><i class='fa fa-user-secret'></i></div>
<div class='box' data-f='user-times'><i class='fa fa-user-times'></i></div>
<div class='box' data-f='users'><i class='fa fa-users'></i></div>
<div class='box' data-f='video-camera'><i class='fa fa-video-camera'></i></div>
<div class='box' data-f='volume-down'><i class='fa fa-volume-down'></i></div>
<div class='box' data-f='volume-off'><i class='fa fa-volume-off'></i></div>
<div class='box' data-f='volume-up'><i class='fa fa-volume-up'></i></div>
<div class='box' data-f='warning'><i class='fa fa-warning'></i></div>
<div class='box' data-f='wheelchair'><i class='fa fa-wheelchair'></i></div>
<div class='box' data-f='wifi'><i class='fa fa-wifi'></i></div>
<div class='box' data-f='wrench'><i class='fa fa-wrench'></i></div>
<hr>
<div class='box' data-f='align-center'><i class='fa fa-align-center'></i></div>
<div class='box' data-f='align-justify'><i class='fa fa-align-justify'></i></div>
<div class='box' data-f='bold'><i class='fa fa-bold'></i></div>
<div class='box' data-f='chain'><i class='fa fa-chain'></i></div>
<div class='box' data-f='chain-broken'><i class='fa fa-chain-broken'></i></div>
<div class='box' data-f='clipboard'><i class='fa fa-clipboard'></i></div>
<div class='box' data-f='columns'><i class='fa fa-columns'></i></div>
<div class='box' data-f='copy'><i class='fa fa-copy'></i></div>
<div class='box' data-f='cut'><i class='fa fa-cut'></i></div>
<div class='box' data-f='dedent'><i class='fa fa-dedent'></i></div>
<div class='box' data-f='eraser'><i class='fa fa-eraser'></i></div>
<div class='box' data-f='file'><i class='fa fa-file'></i></div>
<div class='box' data-f='file-o'><i class='fa fa-file-o'></i></div>
<div class='box' data-f='file-text'><i class='fa fa-file-text'></i></div>
<div class='box' data-f='file-text-o'><i class='fa fa-file-text-o'></i></div>
<div class='box' data-f='files-o'><i class='fa fa-files-o'></i></div>
<div class='box' data-f='floppy-o'><i class='fa fa-floppy-o'></i></div>
<div class='box' data-f='font'><i class='fa fa-font'></i></div>
<div class='box' data-f='header'><i class='fa fa-header'></i></div>
<div class='box' data-f='indent'><i class='fa fa-indent'></i></div>
<div class='box' data-f='italic'><i class='fa fa-italic'></i></div>
<div class='box' data-f='link'><i class='fa fa-link'></i></div>
<div class='box' data-f='list'><i class='fa fa-list'></i></div>
<div class='box' data-f='list-alt'><i class='fa fa-list-alt'></i></div>
<div class='box' data-f='list-ol'><i class='fa fa-list-ol'></i></div>
<div class='box' data-f='list-ul'><i class='fa fa-list-ul'></i></div>
<div class='box' data-f='outdent'><i class='fa fa-outdent'></i></div>
<div class='box' data-f='paperclip'><i class='fa fa-paperclip'></i></div>
<div class='box' data-f='paragraph'><i class='fa fa-paragraph'></i></div>
<div class='box' data-f='paste'><i class='fa fa-paste'></i></div>
<div class='box' data-f='repeat'><i class='fa fa-repeat'></i></div>
<div class='box' data-f='rotate-left'><i class='fa fa-rotate-left'></i></div>
<div class='box' data-f='rotate-right'><i class='fa fa-rotate-right'></i></div>
<div class='box' data-f='save'><i class='fa fa-save'></i></div>
<div class='box' data-f='scissors'><i class='fa fa-scissors'></i></div>
<div class='box' data-f='strikethrough'><i class='fa fa-strikethrough'></i></div>
<div class='box' data-f='subscript'><i class='fa fa-subscript'></i></div>
<div class='box' data-f='superscript'><i class='fa fa-superscript'></i></div>
<div class='box' data-f='table'><i class='fa fa-table'></i></div>
<div class='box' data-f='text-height'><i class='fa fa-text-height'></i></div>
<div class='box' data-f='text-width'><i class='fa fa-text-width'></i></div>
<div class='box' data-f='th'><i class='fa fa-th'></i></div>
<div class='box' data-f='th-large'><i class='fa fa-th-large'></i></div>
<div class='box' data-f='th-list'><i class='fa fa-th-list'></i></div>
<div class='box' data-f='underline'><i class='fa fa-underline'></i></div>
<div class='box' data-f='undo'><i class='fa fa-undo'></i></div>
<div class='box' data-f='unlink'><i class='fa fa-unlink'></i></div>
<hr>
<div class='box' data-f='bitcoin'><i class='fa fa-bitcoin'></i></div>
<div class='box' data-f='btc'><i class='fa fa-btc'></i></div>
<div class='box' data-f='cny'><i class='fa fa-cny'></i></div>
<div class='box' data-f='dollar'><i class='fa fa-dollar'></i></div>
<div class='box' data-f='eur'><i class='fa fa-eur'></i></div>
<div class='box' data-f='euro'><i class='fa fa-euro'></i></div>
<div class='box' data-f='gbp'><i class='fa fa-gbp'></i></div>
<div class='box' data-f='gg'><i class='fa fa-gg'></i></div>
<div class='box' data-f='gg-circle'><i class='fa fa-gg-circle'></i></div>
<div class='box' data-f='ils'><i class='fa fa-ils'></i></div>
<div class='box' data-f='inr'><i class='fa fa-inr'></i></div>
<div class='box' data-f='jpy'><i class='fa fa-jpy'></i></div>
<div class='box' data-f='krw'><i class='fa fa-krw'></i></div>
<div class='box' data-f='money'><i class='fa fa-money'></i></div>
<div class='box' data-f='rmb'><i class='fa fa-rmb'></i></div>
<div class='box' data-f='rouble'><i class='fa fa-rouble'></i></div>
<div class='box' data-f='rub'><i class='fa fa-rub'></i></div>
<div class='box' data-f='ruble'><i class='fa fa-ruble'></i></div>
<div class='box' data-f='rupee'><i class='fa fa-rupee'></i></div>
<div class='box' data-f='shekel'><i class='fa fa-shekel'></i></div>
<div class='box' data-f='sheqel'><i class='fa fa-sheqel'></i></div>
<div class='box' data-f='try'><i class='fa fa-try'></i></div>
<div class='box' data-f='turkish-lira'><i class='fa fa-turkish-lira'></i></div>
<div class='box' data-f='usd'><i class='fa fa-usd'></i></div>
<div class='box' data-f='won'><i class='fa fa-won'></i></div>
<div class='box' data-f='yen'><i class='fa fa-yen'></i></div>