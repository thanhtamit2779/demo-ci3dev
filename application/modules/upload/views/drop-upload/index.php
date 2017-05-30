<div class="panel panel-success panel-sm">
  <div class="panel-heading">
      	<div class="row">
      		<div class="col-sm-6"><h3>Quản Lý Hình Ảnh :: Kéo thả ảnh</h3></div>
          	<div class="col-sm-6">
				<a class="btn btn-success" id="btn-upload" type="button"> Chọn ảnh </a>
            </div>            
      	</div>
  </div>	   
  <div class="panel-body"> 
  		<div class="row" id="drop-upload">
  			<?php
               echo form_open_multipart('upload/dropupload/upload', array('class' => 'dropzone', 'id' => 'form-upload'));
               echo form_close();
	        ?>  			
  		</div>
  		 
    	<!-- load image -->
        <div class="row" id="load-images">
        	       	
        </div>  
        
        <!-- input hidden -->
        <div class="row">
        	<input type="hidden" name="load-url" value="<?php echo linkCI('upload', 'dropupload', 'listImage');?>" >
        	<input type="hidden" name="delete-url" value="<?php echo linkCI('upload', 'dropupload', 'delete');?>" >
        	<input type="hidden" name="show-image" value="<?php echo linkCI('upload', 'dropupload', 'showImage');?>" >
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

<!-- hiển thị hộp thoại cảnh báo khi xóa -->
<div class="modal fade bs-example-modal-sm" id="modal-delete-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="text-center">Thông Báo</h4>
            </div>
            <div class="modal-body">
                <i>Bạn có muốn xóa sản phẩm vừa chọn!</i>
            </div>
            <div class="modal-footer bg-success">
                <button type="button" class="btn btn-info btn-xs" data-dismiss="modal">Thoát</button>
                <button class="btn btn-success btn-ok btn-xs" id="confirm-delete">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- / hiển thị hộp thoại cảnh báo khi xóa -->