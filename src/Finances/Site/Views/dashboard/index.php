

<section id="finances-dashboard" class="mbtm">
	<section class="container-fluid container">
		<section class="row-fluid">

			<h1>Finances</h1>
           <div class="btn btn-info" disabled="disabled"> Total Available: <?php echo $dash->getAvailableFunds();?></div>
			
			<div>
			<h3>Recent Transactions</h3>
			
			<ul class="list-group">
			 <li class="list-group-item  header">
			 	<ul class="transaction-list-head">
			 		<li class=""><i></i></li>
			 		
			 		<li class="type" style="width:10%;">Type</li>
			 		<li style="width:20%;" class="transactionAmount"><label>Transaction Amount</label></li>
			 		<li style="width:20%;" class="availableAmount"><label>Available Amount</label></li>
			 		<li style="width:30%;" class="desc">Description</li>
			 		<li style="width:10%;" class="desc">Details</li>
			 	</ul>
			 </li>
			 <?php if (!empty($transactions->items)) : ?>    
   
			<?php foreach ($transactions->items as $trans) : ?>
			 <li class="list-group-item transaction-<?php echo $trans->type;?>">
			 	<ul class="transaction-<?php echo $trans->type; ?>">
			 		<li class="indicate-<?php echo $trans->type; ?>"><i></i></li>
			 		<li style="width:10%;" class="type type-<?php echo $trans->type; ?>"><?php echo $trans->type; ?></li>
			 		<li style="width:20%;" class="transactionAmount"><?php echo $trans->getAmount(); ?></li>
			 		<li style="width:20%;" class="availableAmount"><?php echo $trans->getActualamount(); ?></li>
			 		<li style="width:30%;" class="desc"><?php echo $trans->getDesc(); ?></li>
			 		<li style="width:10%;" class="desc"><a target="_blank" href="/finances/transaction/details/<?php echo $trans->id; ?>">Details</a></li>
			 	</ul>
			 </li>
			<?php endforeach; ?>
			</ul>
			</div>
          <?php endif; ?>
			<div class="pagination-wrapper">
        <div class="row">
            <div class="col-sm-10">
                <?php if (!empty($transactions->total_pages) && $transactions->total_pages > 1) { ?>
                    <?php echo $transactions->serve(); ?>
                <?php } ?>
            </div>
            <div class="col-sm-2">
                <div class="pagination-count pull-right">
                    <span class="pagination">
                        <?php echo (!empty($transactions->total_pages)) ? $transactions->getResultsCounter() : null; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>       

		</section>
	</section>
</section>