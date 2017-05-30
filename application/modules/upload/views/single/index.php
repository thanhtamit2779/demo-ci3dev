<div class="panel panel-success panel-sm">
  <div class="panel-heading">
      	<div class="row">
      		<div class="col-sm-6"><h3>Quản Lý Hình Ảnh :: Đăng 1 ảnh</h3></div>
          	<div class="col-sm-6">
          		<form action="#" id="form-upload" name="form-upload" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
          			<input type="file" id="file-upload" name="file-upload" style="display: none;">
        			<a class="btn btn-success btn-sm" id="select-file" onclick="javascript:openFile();">Chọn ảnh</a>
        			<input type="hidden" id="token" name="token" value="<?php echo time();?>">
        			<button type="submit" class="btn btn-primary btn-sm" disabled="disabled">Đăng ảnh</button>
        			<a href="quan-ly-hinh-anh.html" class="btn btn-info btn-sm">Làm mới</a>
          		</form>
            </div>
      	</div>
  </div>	   
  <div class="panel-body">  
      	
      	<!-- message -->
      	<div class="row">
        	<?php 
             $xhtml = '';
             if(isset($message)) {
                 $xhtml = '<div class="col-sm-12 col-md-12"><div class="alert alert-sm alert-warning">' .$message. '</div></div>';
             }
             echo $xhtml;
        	?>	
    	</div>
    	
    	<!-- load image -->
        <div class="row" id="load-images">
        	
        </div>  
        
        <!-- input hidden -->
        <div class="row">
        	<input type="hidden" name="load-url" value="<?php echo linkCI('upload', 'single', 'listImage');?>" >
        	<input type="hidden" name="delete-url" value="<?php echo linkCI('upload', 'single', 'delete');?>" >	
        </div>       			
  </div>
</div>
<!-- modal zoom image-->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal-image-zoom">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
      	<img src="" class="img-responsive"> 
      </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn-xs" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!-- / modal zoom image -->
<!-- upload single ajax -->
	<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/upload/single/js/upload.js"></script>

