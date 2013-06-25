<div id="sidebar">
	<a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
	<ul style="display: block;">
		<?php if($super_admin): ?>
		<li class="submenu active linc1">
			<a href="#" linc='linc1'><i class="icon icon-user"></i> <span>Users</span></a>
			<ul>
				<li><?php echo $this->Html->link('Users',array('controller'=>'users','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc1','sub_linc'=>'linc1b')); ?></li>
				<li><?php echo $this->Html->link('Openings',array('controller'=>'openings','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc1','sub_linc'=>'linc1b')); ?></li>
			</ul>
		</li>
		<?php endif; ?>
		<li class="submenu linc3">
			<a href="#" linc='linc3'><i class="icon icon-file"></i> <span>Purchase Receipts</span></span></a>
			<ul>
				<li><?php echo $this->Html->link('Add New Purchase receipts',array('controller'=>'purchased_receipts','action'=>'add'),array('class'=>'use-ajax sub_link','linc'=>'linc3','sub_linc'=>'linc3d')); ?></li>
				<li><?php echo $this->Html->link('List Purchase receipts',array('controller'=>'purchased_receipts','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc3','sub_linc'=>'linc3a')); ?></li>
				<li><?php echo $this->Html->link('Large Cash Purchase receipts',array('controller'=>'purchased_receipts','action'=>'index','large_cash'),array('class'=>'use-ajax sub_link','linc'=>'linc3','sub_linc'=>'linc3b')); ?></li>
				<?php if($super_admin): ?>
					<li><?php echo $this->Html->link('Upload/Send Purchase receipts',array('controller'=>'purchased_receipts','action'=>'upload'),array('class'=>'use-ajax sub_link','linc'=>'linc3','sub_linc'=>'linc3c')); ?></li>
				<?php endif; ?>				
			</ul>
		</li>
		<li class="submenu linc2">
			<a href="#" linc='linc2'><i class="icon icon-file"></i> <span>Sales Receipts</span></a>
			<ul>
				<li><?php echo $this->Html->link('Add New Sales receipts',array('controller'=>'sold_receipts','action'=>'add'),array('class'=>'use-ajax sub_link','linc'=>'linc2','sub_linc'=>'linc2d')); ?></li>
				<li><?php echo $this->Html->link('List Sales receipts',array('controller'=>'sold_receipts','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc2','sub_linc'=>'linc2a')); ?></li>
				<li><?php echo $this->Html->link('Large Cash Sales receipts',array('controller'=>'sold_receipts','action'=>'index','large_cash'),array('class'=>'use-ajax sub_link','linc'=>'linc2','sub_linc'=>'linc2b')); ?></li>
				<?php if($super_admin): ?>
					<li><?php echo $this->Html->link('Upload/Send Sales receipts',array('controller'=>'sold_receipts','action'=>'upload'),array('class'=>'use-ajax sub_link','linc'=>'linc2','sub_linc'=>'linc2c')); ?></li>
				<?php endif; ?>				
			</ul>
		</li>
		
		<li class="submenu linc4">
			<a href="#" linc='linc4'><i class="icon icon-book"></i> <span>Book Keeping </span></a>
			<ul>
				<?php if($super_admin): ?>
					<li><?php echo $this->Html->link('Individual Closing Position',array('controller'=>'users','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc4b')); ?></li>
				<?php else:?>
					<li><?php echo $this->Html->link('Individual Closing Position',array('controller'=>'balancings','action'=>'show_individually'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc4b')); ?></li>
				<?php endif;?>
				
				<?php if($super_admin): ?>
				<li><?php echo $this->Html->link('Daily General Closing Position',array('controller'=>'balancings','action'=>'show_generally'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc4b')); ?></li>
				<li><?php echo $this->Html->link('Final General Closing Position',array('controller'=>'balancings','action'=>'show_generally_final'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc4b')); ?></li>
				<li><?php echo $this->Html->link('Cash Flow',array('controller'=>'balancings','action'=>'show_cash_flow'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc9b')); ?></li>
				<?php endif;?>
				<li><?php echo $this->Html->link('Add New Expense',array('controller'=>'expenses','action'=>'add'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc8b')); ?></li>
				<li><?php echo $this->Html->link('List Expenses',array('controller'=>'expenses','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc8b')); ?></li>
				<li><?php echo $this->Html->link('Add Currency',array('controller'=>'other_currencies','action'=>'add'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc8b')); ?></li>
				<li><?php echo $this->Html->link('List New Currency',array('controller'=>'other_currencies','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc4','sub_linc'=>'linc8b')); ?></li>
			</ul>
		</li>
		<?php if($super_admin): ?>
		<li class="submenu linc9">
			<a href="#" linc='linc9'><i class="icon icon-bold"></i> <span>BOU Reports</span></a>
			<ul>				
				<li><?php echo $this->Html->link('General Sales returns',array('controller'=>'returns','action'=>'returns_weekly'),array('class'=>'use-ajax sub_link','linc'=>'linc9','sub_linc'=>'linc9b')); ?></li>
				<li><?php echo $this->Html->link('General Purchased returns',array('controller'=>'returns','action'=>'returns_weekly_purchases'),array('class'=>'use-ajax sub_link','linc'=>'linc9','sub_linc'=>'linc9b')); ?></li>
				<li><?php echo $this->Html->link('Add New Daily return',array('controller'=>'daily_returns','action'=>'add'),array('class'=>'use-ajax sub_link','linc'=>'linc9','sub_linc'=>'linc5a')); ?></li>
				<li><?php echo $this->Html->link('List Daily Returns',array('controller'=>'daily_returns','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc9','sub_linc'=>'linc9b')); ?></li>
				
			</ul>
		</li>
		<?php endif;?>
		
		<?php if($super_admin):?>
		<li class="submenu linc10">
			<a href="#" linc='linc10'><i class="icon icon-th"></i> <span>Cash Position</span></a>
			<ul>				
				<li><?php echo $this->Html->link('Cash At Bank Ugx',array('controller'=>'cash_at_bank_ugxes','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc10','sub_linc'=>'linc9b')); ?></li>
				<li><?php echo $this->Html->link('Cash At Bank Foreign',array('controller'=>'cash_at_bank_foreigns','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc10','sub_linc'=>'linc9b')); ?></li>
				<li><?php echo $this->Html->link('Creditors',array('controller'=>'creditors','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc10','sub_linc'=>'linc5a')); ?></li>
				<li><?php echo $this->Html->link('Debtors ',array('controller'=>'debtors','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc10','sub_linc'=>'linc9b')); ?></li>
				
			</ul>
		</li>
		<?php endif; ?>
		
		<li class="submenu linc6">
			<a href="#" linc='linc6'><i class="icon icon-pencil"></i> <span>Customers</span></a>
			<ul>				
				<li><?php echo $this->Html->link('List Contacts',array('controller'=>'contacts','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc6','sub_linc'=>'linc7b')); ?></li>
				<li><?php echo $this->Html->link('Add New Contact',array('controller'=>'contacts','action'=>'add'),array('class'=>'use-ajax sub_link','linc'=>'linc6','sub_linc'=>'linc7b')); ?></li>
				<li><?php echo $this->Html->link('List Contact groups',array('controller'=>'contact_lists','action'=>'index'),array('class'=>'use-ajax sub_link','linc'=>'linc6','sub_linc'=>'linc6b')); ?></li>
				<li><?php echo $this->Html->link('Add New Contact group',array('controller'=>'contact_lists','action'=>'add'),array('class'=>'use-ajax sub_link','linc'=>'linc6','sub_linc'=>'linc6b')); ?></li>
			</ul>
		</li>
		
		
		
	</ul>
</div>

<script>
	var current_linc='linc_a';//set the first active link
	$(document).ready(function(){
		$('#sidebar a').click(function(){
			
			$('#sidebar ul li').removeClass('active');
			
			var linc=$(this).attr('linc');
			$('.'+linc).addClass('active');
			$('.display-none').fadeIn('slow');
			
			var _url=$(this).attr('data-taget');
			
			if($(this).hasClass('sub_link')){
				linc=$(this).attr('sub_linc');
			}
			
			if(_url!='' && _url!='#' && current_linc!=linc ){
				current_linc=linc;
				show_loading();
				$.get(_url, function(data) {
					after_fetching_data(data);
					remove_loading();
				});
			}
			
		});
		
	});
</script>