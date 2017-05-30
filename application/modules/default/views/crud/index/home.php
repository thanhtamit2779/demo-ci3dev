<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-success">
          <div class="panel-heading">
            <h4>ÔN TẬP AJAX (THÊM - XÓA - SỬA)</h4>
          </div>
		</div>
		<div class="panel panel-info">
			<div class="panel-heading">
				<h4><i class="fa fa-list"></i> Danh Sách Sản Phẩm (<?php echo $countItems;?> sản phẩm)</h4>	
			</div>
			<div class="panel-body">
			    <div id="message"></div>         	
                <div class="table-responsive">  
                		<form action="#" method="post" name="form" id="form">             	
                        <table class="table table-hover table-bordered" data-toggle="table" data-url="" data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-select-item-name="toolbar1">
                            <thead>
                                <tr>
                                    <th data-field="checkall-toggle"><input type="checkbox" name="checkall-toggle" data-original-title="Check All"></th>
                                    <th><a href="#">Hình ảnh</a></th>
                                    <th><a href="#">Tên sản phẩm </a></th>
                                    <th><a href="#">Đơn giá </a></th> 
                                    <th><a href="#">Ngày tạo</a></th> 
                                    <th><a href="#">Quà tặng</a></th>     
                                    <th><a href="#">Bảo hành </a></th>				        
                                    <th><a href="#">ID </a></th>
                                    <th><a href="#">Hành động </a></th>
                                </tr>    
                            </thead>
                            <input type="hidden" name="delete" value="<?php echo linkCI('default', 'crud', 'index', array('action' => 'delete'));?>">
                            <tbody>
                            	
                            </tbody>
                        </table>
                        </form>                               			   				    	   				
                </div> 
				<div id="load-add-product"></div>
    			<div class="form-group">
    				<button class="btn btn-success btn-sm pull-left" onClick="javascript:delete_selected_product('<?php echo linkCI('default', 'crud', 'index', array('action' => 'deleteSelected'))?>')"><i class="glyphicon glyphicon-minus"></i> Xóa chọn</button>
    				<button class="btn btn-success btn-sm pull-right" onClick="javascript:show_add_product()" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Thêm sản phẩm</button>
    			</div>    			
                <div class="form-group text-center" style="margin:5px 0px">
                	<input type="hidden" value="<?php echo linkCI('default', 'crud', 'index', array('action' => 'loadMore'));?>" name="load-more">
                	<button class="btn btn-sm btn-info" id="load-more"> Xem thêm </button>
                </div>
			</div>
		</div>
	</div>
</div>

<!-- hiển thị hộp thoại thêm, cập nhật -->
<div class="modal fade bs-example-modal-lg" id="modal-add-product" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center">Thêm Sản Phẩm</h3>
            </div>
            <div class="modal-body form">
                <form action="" id="form" class="form-horizontal" method="post">
                    <input type="hidden" value="" name="id" /> 
                    <div class="form-body">
                          <!-- tên sản phẩm -->
                          <div class="form-group has-success" id="name">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tên sản phẩm</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name="name" placeholder="Nhập tên sản phẩm..." value="<?php echo set_value('name');?>">
                            </div>
                          </div>
                               
                          <!-- đơn giá -->
                          <div class="form-group has-success" id="price">
                            <label for="inputPassword3" class="col-sm-2 control-label">Đơn giá</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name="price" placeholder="Nhập đơn giá cho sản phẩm..." value="<?php echo set_value('price');?>">
                            </div>
                          </div>
                          
                          <!-- quà tặng -->
                          <div class="form-group has-success" id="gifts">
                            <label for="inputPassword3" class="col-sm-2 control-label">Quà tặng</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name="gifts" placeholder="Nhập quà tặng (nếu có)...">
                            </div>
                          </div>
                          
                          <!-- bảo hành -->
                          <div class="form-group has-success" id="warranty">
                            <label for="inputPassword3" class="col-sm-2 control-label">Bảo hành</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control" name="warranty" placeholder="Nhập thời gian bảo hành..." value="<?php echo set_value('warranty');?>">
                            </div>
                          </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-success">
                <button type="button" class="btn btn-success btn-sm" onClick="javascript:form_product('<?php echo linkCI('default', 'crud', 'index', 'form');?>')"> Thực hiện </button>
                <button type="button" class="btn btn-info btn-sm" data-dismiss="modal">Thoát</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- hiển thị hộp thoại thêm, cập nhật -->

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

<!-- hiển thị hộp thoại khi chưa chọn sản phẩm cần xóa -->
<div class="modal fade bs-example-modal-sm" id="modal-alert-delete-product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="text-center">Thông Báo</h4>
            </div>
            <div class="modal-body">
                <i>Bạn chưa chọn sản phẩm cần xóa!</i>
            </div>
            <div class="modal-footer bg-success">
                <button type="button" class="btn btn-success btn-xs" data-dismiss="modal">Thoát</button>
            </div>
        </div>
    </div>
</div>
<!-- / hiển thị hộp thoại khi chưa chọn sản phẩm cần xóa -->
<!-- crud ajax -->
<script type="text/javascript" src="<?php echo TEMPLATE_URL?>/default/crud/js/crud.js"></script>