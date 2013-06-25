<?php echo $this->Html->script(array('script_dynamic_content'));?>
<div class="flash-message"><?php echo $this->Session->flash(); ?></div>
<div id="use">
    <div class="users view">
        <div class="related">
            
            <ul class="nav nav-tabs" id="myTab">
                <li><a href="#a" class='no-ajax'><i class="icon-user"></i> Profile</a></li>
            </ul>
                
            <div class="tab-content">
                <div id="a" class="tab-pane active">
                    <dl><?php $i = 0;
$class = ' class="altrow"'; ?>
                        <dt<?php if ($i % 2 == 0)
                            echo $class; ?>><?php echo __('Name'); ?></dt>
                        <dd<?php if ($i++ % 2 == 0)
                                echo $class; ?>>
                                <?php echo $user['User']['name']; ?>
                            &nbsp;
                        </dd>
                        <dt<?php if ($i % 2 == 0)
                                    echo $class; ?>><?php echo __('Username'); ?></dt>
                        <dd<?php if ($i++ % 2 == 0)
                                echo $class; ?>>
                                <?php echo $user['User']['username']; ?>
                            &nbsp;
                        </dd>
                        <dt<?php if ($i % 2 == 0)
                                    echo $class; ?>><?php echo __('Email'); ?></dt>
                        <dd<?php if ($i++ % 2 == 0)
                                echo $class; ?>>
                                <?php echo $user['User']['email']; ?>
                            &nbsp;
                        </dd>
                    </dl>
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
       <?php if($super_admin): ?>
			<ul>
				<li><?php echo $this->Html->link('Individual Balancing',array('controller'=>'balancings','action'=>'show_individually',0,0,0,$user['User']['id'])); ?></li>
			</ul>
		<?php endif; ?>
    </div>
    