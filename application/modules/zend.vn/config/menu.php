<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['left'] = array(
						'jquery' => array(
										'title' 	=> 'Jquery Master' ,
										'id'    	=> 'jquery' ,
				  			  			'link'  	=> 'jquery/index' ,
				  			  			'icon'      => '' ,
										'items'     => array(
															  'plugin'	=> array(
															  			  			'title' 	=> 'Plugin' ,
															  			  			'id'    	=> 'plugin' ,
															  			  			'link'  	=> 'plugin/index' ,
															  			  			'icon'      => '' ,
															  			  			'submenu'   => array(
															  			  								array('title' 	=> 'Zoom' ,
																				  			  			'id'    	=> 'zoom' ,
																				  			  			'link'  	=> 'plugin/zoom' ,
																				  			  			'icon'      => '' ,
																				  			  			) ,

															  			  								array('title' 	=> 'Slider' ,
																				  			  			'id'    	=> 'plugin' ,
																				  			  			'link'  	=> 'plugin/slider' ,
																				  			  			'icon'      => '' ,
																				  			  			)
															  			  						   )
															  			   )

													    ) 	
									)	,
						'php' => array(
										'title' 	=> 'PHP Cơ bản và Nâng cao' ,
										'id'    	=> 'php' ,
				  			  			'link'  	=> 'php/index' ,
				  			  			'icon'      => '' ,
										'items'     => array(
															  // 'plugin'	=> array(
															  // 			  			'title' 	=> 'Plugin' ,
															  // 			  			'id'    	=> 'plugin' ,
															  // 			  			'link'  	=> 'plugin/index' ,
															  // 			  			'icon'      => '' ,
															  // 			  			'submenu'   => array(
															  // 			  								array('title' 	=> 'Zoom' ,
																	// 			  			  			'id'    	=> 'zoom' ,
																	// 			  			  			'link'  	=> 'plugin/zoom' ,
																	// 			  			  			'icon'      => '' ,
																	// 			  			  			) ,

															  // 			  								array('title' 	=> 'Slider' ,
																	// 			  			  			'id'    	=> 'plugin' ,
																	// 			  			  			'link'  	=> 'plugin/slider' ,
																	// 			  			  			'icon'      => '' ,
																	// 			  			  			)
															  // 			  						   )
															  // 			   )

													    ) 	
									)							
				  ) ;
