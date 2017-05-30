<div class="panel panel-success panel-sm">
  <div class="panel-heading">
      	<div class="row">
      		<div class="col-sm-6"><h3>Quản Lý Hình Ảnh :: Đăng nhiều ảnh</h3></div>
          	<div class="col-sm-3">
          		<form action="#" id="form-upload" name="form-upload" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
          			<input type="file" id="file-upload" name="file-upload[]" style="display: none;" multiple>
        			<a class="btn btn-success btn-sm" id="select-file" onclick="javascript:openFile();">Chọn ảnh</a>
        			<input type="hidden" id="token" name="token" value="<?php echo time();?>">
        			<button type="submit" class="btn btn-primary btn-sm" disabled="disabled">Đăng ảnh</button>
        			<a href="quan-ly-hinh-anh.html" class="btn btn-info btn-sm">Làm mới</a>
          		</form>
            </div>
            <div class="col-sm-3">
				<div class="uploading none">
					<label>&nbsp;</label>
					<img alt="" src="<?php echo TEMPLATE_URL?>/upload/multi/images/uploading.gif">
				</div>
			</div>
      	</div>
  </div>	   
  <div class="panel-body">  
      	
      	<!-- message -->
      	<div class="row">
        	<?php 
             $xhtml = '';
             if(isset($count)) {
                 $xhtml = '<div class="col-sm-12 col-md-12"><div class="alert alert-sm alert-warning">Có ' .$count. ' phần tử được upload</div></div>';
             }
             echo $xhtml;
        	?>	
    	</div>
    	
    	<!-- load image -->
        <div class="row" id="load-images">
        	
        </div>  
        
        <!-- input hidden -->
        <div class="row">
        	<input type="hidden" name="load-url" value="<?php echo linkCI('upload', 'multi', 'listImage');?>" >
        	<input type="hidden" name="delete-url" value="<?php echo linkCI('upload', 'multi', 'delete');?>" >
        	<input type="hidden" name="show-image" value="<?php echo linkCI('upload', 'multi', 'showImage');?>" >
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
<!-- upload multi ajax -->
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/upload/multi/js/upload.js"></script>
