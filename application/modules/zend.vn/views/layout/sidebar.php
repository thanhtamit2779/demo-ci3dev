<aside class="main-sidebar col-sm-3 hidden-xs col-md-3 col-lg-3">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
          <?php 
               $this->config->load('zend.vn/menu') ;
               $sidebar   = $this->config->item('left') ;
          ?>  
          <!-- Tiêu đề từng menu -->  
          <?php if( ! empty($sidebar) ) : ?>
                <ul class="sidebar-menu">
                  <?php foreach ($sidebar as $position => $menus) : ?>
                       <li class="header"><?php echo $menus['title'] ;?></li>

                       <!-- Menu cấp 1 -->
                       <?php if( ! empty($menus['items']) ) :?>
                            <?php foreach ($menus['items'] as $menu) : ?>
                                <li class="treeview">
                                    <a href="<?php echo $menu['link'] ;?>">
                                        <i class="<?php echo $menu['icon'] ;?>"></i> <span><?php echo $menu['title'] ; ?></span>
                                        <span class="pull-right-container">
                                          <i class="fa fa-angle-left pull-right"></i>
                                        </span>
                                    </a>

                                    <!-- Menu cấp 2 -->
                                    <?php if( ! empty($menu['submenu']) ) : ?>
                                        <ul class="treeview-menu">
                                        <?php foreach ($menu['submenu'] as $key => $submenu) : ?>
                                              <li><a href="<?php echo $submenu['link'] ;?>"><i class="<?php echo $submenu['icon'] ;?>"></i> <?php echo $submenu['title'] ; ?></a></li>
                                        <?php endforeach ?>
                                        </ul>
                                    <?php endif ?>                 
                                </li> 
                            <?php endforeach ?>
                       <?php endif ;?>
                  <?php endforeach ; ?>
                </ul>
          <?php endif ?>   
    </section>
    <!-- /.sidebar -->
</aside>