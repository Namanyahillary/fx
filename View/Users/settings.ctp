<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div id="use">
    <div class="users view">
        <div class="related">
            
            <ul class="nav nav-tabs" id="myTab">
                <li><a href="#b" class='no-ajax'><i class="icon-cog"></i> Settings</a></li>
				<li><a href="#c" class='no-ajax'><i class="icon-refresh"></i> Password Reset</a></li>
            </ul>
                
            <div class="tab-content">      
                <div id="b" class="tab-pane active">
                    <div class="users ">
                    <?php echo $this->Form->create('User',array('type'=>'file','id'=>'user_edit_form','controller'=>'users','action'=>'edit'));?>
                            <?php
                                    echo $this->Form->input('id');
                                    echo $this->Form->input('name');
                                    echo $this->Form->input('email');
                                    echo $this->Form->input('username');
                                    //echo $this->Form->input('password',array('value'=>''));
                                    //echo $this->Form->input('password_confirmation',array('type'=>'password'));
                                    if($super_admin && $this->data['User']['id']!=$users_Id){		
                                            echo $this->Form->input('role',array('type'=>'select','options'=>array('regular'=>'Regular user','admin'=>'Administrator'),'selected'=>1));
                                            		
                                    }
                                    

                            ?>
                            </fieldset>
                    <?php echo $this->Form->end(__('Update', true));?>
                    </div> 
                </div>
				
				<div id="c" class="tab-pane">
						<?php echo $this->Form->create('User',array('type'=>'file','id'=>'user_edit_form','controller'=>'users','action'=>'edit'));?>
								<?php
										echo $this->Form->input('id');										
										echo $this->Form->input('password',array('value'=>'','label'=>'New Password'));
										echo $this->Form->input('password_confirmation',array('type'=>'password','label'=>'Confirm password'));								

								?>
								</fieldset>
						<?php echo $this->Form->end(__('Change Password', true));?>
					</div>
				
                <hr/>
                <script>
                    $('#myTab a').click(function (e){
                        e.preventDefault();
                        $(this).tab('show');
                    });
                </script>
                    
            </div>
        </div>

    </div>
    
    
    <div class="actions">
        <h3><?php echo'<strong>';
__('My account');
echo'</strong>'; ?></h3>
        <ul id="imge">
            <?php
				echo $this->Html->image("pic/" . $user['User']['profile_image'], array('width' => '50px', 'height' => '40px', 'alt' => 'Profile Picture'));
            ?>
        </ul>

    </div>
    