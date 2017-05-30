<script type="text/javascript">
(function($){
		$.fn.zoom_it  = function(options) {
			// Khai báo biến
			var new_width , new_height ;
			var defaults ;

			// Khai báo thông số truyền mặc định
			defaults  = {
							width  : 100 ,
							height : 100 ,
							box    : 100 
					     }

		     // Gộp đối tượng truyền vào và đối tượng mặc định
		     options  = $.extend(defaults, options) ;

		     // Kiểm tra điều kiện
			 if(options.box != 100) { // Người dùng truền vào phần trăm 
				 new_width  = $(this).width()  * options.box/100 ;
				 new_height = $(this).height() * options.box/100 ;
			 }
			 else {
				 new_width  = $(this).width()  * options.width/100 ;
				 new_height = $(this).height() * options.height/100 ; 
			 }

			 $(this).animate({ width : new_width, height : new_height } , 500) ;
			 return this ;
		}					     
	})(jQuery) ;

	$(document).ready(function() {
		$('#zoomIn').click(function(e) {
			$('#box').zoom_it({ width : 80 , height : 70 }).removeClass('box02').addClass('box01') ;	
		}) ;

		$('#zoomOut').click(function(e) {
			$('#box').zoom_it({ width: 120, height: 130 }).removeClass('box01').addClass('box02') ;	
		}) ;
	}) ;
</script>
<style type="text/css">
/* CSS Document */
*{
	padding: 0px;
	margin: 0px;
	border: 0px;
}

.clr{
	clear: both;	
}

.left{
	float: left;	
}

.input{
	padding: 3px;
	border: solid 1px #333;	
}

.button{
	font-size: 12px;
	padding: 3px;
	border: solid 1px #333;	
}


.bg01{
	background-color: #FF9;	
}

.bg02{
	background-color: #CFC;
}

.box{
	height: 200px;
	width: 200px;
	padding: 5px;
	margin: 20px auto;
	border: 3px solid #090;
	text-align: center;
	font-size: 20px;	
}
    
</style>
<center>
    	<h1 style="margin-bottom: 20px;">
         Zoom Plugin
       </h1>
    </center>
    
    <center>
    	<input type="button" id="zoomIn" value="Zoom In" class="input"/>
        <input type="button" id="zoomOut" value="Zoom Out" class="input"/>
    </center>
    
    <div id="box" class="box">My Box</div>
