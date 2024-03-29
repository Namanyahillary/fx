$(document).ready(function(){
	setInterval(function(){
		fetchReceipts();
	},2000);
	
	$('.receipt_type').click(function(){
		if($('.receipt_type').html()=='Sales receipt'){
			$('.receipt_type').html('Purchase receipt');				
			$('.purpose-title').html('Source of funds');
			$('.receipt_instrument').hide();
		}else{
			$('.receipt_type').html('Sales receipt');				
			$('.purpose-title').html('Purpose of purchase');
			$('.receipt_instrument').show();
		}
	});
	
});
var lock=0;
function fetchReceipts(){
	if(lock==0)lock=1;
	else return;
	
	//load new receipts-These are receipts that are to be printed
	var data = {};
	if(lock==1){
		$.ajax({
			url: '/fx/m/get_new_sold_receipts.php',
			dataType: 'json',
			data: data,
			success: getit,
			error: err
		});
	}
	
	function getit(data){ 
		$.each(data.Receipts, function(i, receipt) {
			lock=0;
			$('.receipt_number').text(receipt.id);
			$('.receipt_currency').text((receipt.currency_id!='c8')?getCurrencyText(receipt.currency_id):receipt.other_name);
			$('.receipt_amount').text((receipt.currency_id!='c8')?receipt.amount:receipt.orig_amount);
			$('.receipt_rate').text((receipt.currency_id!='c8')?receipt.rate:receipt.orig_rate);
			$('.receipt_amount_ugx').text(receipt.amount_ugx);
			
			$('.receipt_customer_name').text(receipt.customer_name);
			$('.receipt_date').text(receipt.date);			
			$('.receipt_customer_address').html(receipt.address);
			$('.receipt_customer_nationality').html(receipt.nationality);
			$('.receipt_customer_passport_number').html(receipt.passport_number);			
			//handle instruments column
			if(receipt.reciept_type=='purchased_receipts'){ 
				$('.receipt_instrument').hide();
				$('.receipt_type').html('Purchase Receipt');
				$('.purpose-title').html('Source of funds');
				$('.receipt_purpose').text(getPurposeForPurchases(receipt.purchased_purpose_id));				
			}
			else {
				$('.receipt_instrument').show();
				$('.receipt_instrument').html(receipt.instrument);
				$('.receipt_type').html('Sales Receipt');
				$('.purpose-title').html('Purpose of purchase');
				$('.receipt_purpose').text(getPurpose(receipt.purpose_id));
			}
			
			print_doc(receipt.id,receipt.date);			
		});
	}
	
	function getCurrencyText(c){
		if(c=='c1') return 'USD';
		else if(c=='c2') return 'Euro';
		else if(c=='c3') return 'GBP';
		else if(c=='c4') return 'Kshs';
		else if(c=='c5') return 'Tzshs';
		else if(c=='c6') return 'SAR';
		else if(c=='c7') return 'SP';
		else if(c=='c8') return 'Others';
		else return '';
	}
	
	function err(){
		lock=0;
	}
	
	function getPurpose(purpose_id){
		switch(purpose_id){
			case 'p1':
				return 'Transaction between Uganda Residents';
			case 'p2':
				return 'Currency Holdings/Deposits';
			case 'p3':
				return 'Govt. Imports';
			case 'p4':
				return 'Private Imports - Oil';
			case 'p5':
				return 'Private Imports - Gold';
			case 'p6':
				return 'Private Imports - Repair';
			case 'p7':
				return 'Private Imports - Goods produced in ports by carriers';
			case 'p8':
				return 'Private Imports - Goods for processing';
			case 'p9':
				return 'Private Imports - Other Imports';
			case 'p10':
				return 'Income Payments - Interest paid on external liabilities';
			case 'p11':
				return 'Income Payments - Dividends/profits paid';
			case 'p12':
				return 'Income Payments - Wages/Salaries';
			case 'p13':
				return 'Service Payments - Transportation - Freight';
			case 'p14':
				return 'Service Payments - Transportation - Passanger';
			case 'p15':
				return 'Service Payments - Transportation - Other';
			case 'p16':
				return 'Service Payments - Communication services';
			case 'p17':
				return 'Service Payments - Construction services';
			case 'p18':
				return 'Service Payments - Insurance & Re-insurance';
			case 'p19':
				return 'Service Payments - Financial services';
			case 'p20':
				return 'Service Payments - Travel - Education';
			case 'p21':
				return 'Service Payments - Travel - Medical';
			case 'p22':
				return 'Service Payments - Travel - Other Personal';
			case 'p23':
				return 'Service Payments - Travel - Other Personal';
			case 'p24':
				return 'Service Payments - Computer & info services';
			case 'p25':
				return 'Service Payments - Royalties & licence fees';
			case 'p26':
				return 'Service Payments - Other business services';
			case 'p27':
				return 'Service Payments - Personal, cultural, & recreational services';
			case 'p28':
				return 'Service Payments - Governmen services, n.i.e';
			case 'p29':
				return 'Transfers - NGO outflows';
			case 'p30':
				return 'Transfers - Government Grants';
			case 'p31':
				return 'Transfers - Worker\'s remittances';
			case 'p32':
				return 'Transfers - Other transfers';
			case 'p33':
				return 'Foreign Direct equity Investment';
			case 'p34':
				return 'Portfolio Investment - By Government';
			case 'p35':
				return 'Portfolio Investment - By Banks';
			case 'p36':
				return 'Portfolio Investment - By Other';
			case 'p37':
				return 'Portfolio Investment - Other transfers';
			case 'p38':
				return 'Loans Extended abroad - By commercial Banks - Short term';
			case 'p39':
				return 'Loans Extended abroad - By commercial Banks - Long term';
			case 'p40':
				return 'Loans Extended abroad - By Others - Private-Short term';
			case 'p41':
				return 'Loans Extended abroad - By Others - Private-Long term';
			case 'p42':
				return 'Loans Extended abroad - By Others - Government';
			case 'p43':
				return 'Loan Repaymen (Principal)';
			case 'p44':
				return 'Bank/bureaux';
			case 'p45':
				return 'Interbank';
			case 'p46':
				return 'Interbureaux';
			default:
				return '';
		}
	}
	
	function getPurposeForPurchases(purpose_id){
		switch(purpose_id){
			case 'p1':
				return 'Transaction between Uganda Residents';
			case 'p2':
				return 'Currency Holdings/Deposits';
			case 'p3':
				return 'Export of Goods - Gold Exports (non-monetary gold)';
			case 'p4':
				return 'Export of Goods - Repair on goods';
			case 'p5':
				return 'Export of Goods - Goods procured in ports by carriers';
			case 'p6':
				return 'Export of Goods - Goods for processig';
			case 'p7':
				return 'Export of Goods - Coffee and other Exports';
			case 'p8':
				return 'Income Receipts - Interest received on External assets';
			case 'p9':
				return 'Income Receipts - Dividends/ profits received';
			case 'p10':
				return 'Income Receipts - Wages/Salaries';
			case 'p11':
				return 'Service Receipts - Transportation -Freight';
			case 'p12':
				return 'Service Receipts - Transportation -Passanger';
			case 'p13':
				return 'Service Receipts - Transportation -Other';
			case 'p14':
				return 'Service Receipts - Communication services';
			case 'p15':
				return 'Service Receipts - Construction services';
			case 'p16':
				return 'Service Receipts - Insurance & Re-insurance';
			case 'p17':
				return 'Service Receipts - Financial serivces';
			case 'p18':
				return 'Service Receipts - Travel - Business/Official';
			case 'p19':
				return 'Service Receipts - Travel - Education';
			case 'p20':
				return 'Service Receipts - Travel - Medical';
			case 'p21':
				return 'Service Receipts - Travel - Other Personal';
			case 'p22':
				return 'Service Receipts - Computer & information services';
			case 'p23':
				return 'Service Receipts - Royalties & licence fees';
			case 'p24':
				return 'Service Receipts - Other business services';
			case 'p25':
				return 'Service Receipts - Personal, cultural, & recreational services';
			case 'p26':
				return 'Service Receipts - Government services, n.i.e';
			case 'p27':
				return 'Transfers - NGO inflows';
			case 'p28':
				return 'Transfers - Government Grants';
			case 'p29':
				return 'Transfers - Workers remittances';
			case 'p30':
				return 'Transfers - Other transfers';
			case 'p31':
				return 'Interbureaux';
			case 'p32':
				return 'Foreign Direct Equity Investment';
			case 'p33':
				return 'Portfolio investment - Government';
			case 'p34':
				return 'Portfolio investment - Bank';
			case 'p35':
				return 'Portfolio investment - Other';
			case 'p36':
				return 'Loan - Loan Received - By commercial Banks - Short term';
			case 'p37':
				return 'Loan - Loan Received - By commercial Banks - Long term';
			case 'p38':
				return 'Loan - Loan Received - By Others - Private Short term';
			case 'p39':
				return 'Loan - Loan Received - By Others - Private Long term';
			case 'p40':
				return 'Loan - Loan Received - By Others - Government';
			case 'p41':
				return 'Loan - Loan Repayment (Principal)';
			case 'p42':
				return 'Interbank';
			case 'p43':
				return 'Bank/bureaux';
			default:
				return '';
		}
	}
	
	function print_doc(receipt_id,_date){
		jsPrintSetup.setOption('orientation', jsPrintSetup.kPortraitOrientation);
		// set top margins in millimeters
		jsPrintSetup.setOption('marginTop', 15);
		jsPrintSetup.setOption('marginBottom', 15);
		jsPrintSetup.setOption('marginLeft', 20);
		jsPrintSetup.setOption('marginRight', 10);
		// set page header
		jsPrintSetup.setOption('headerStrLeft', _date);
		jsPrintSetup.setOption('headerStrCenter', '');
		jsPrintSetup.setOption('headerStrRight', receipt_id);
		// set empty page footer
		jsPrintSetup.setOption('footerStrLeft', '');
		jsPrintSetup.setOption('footerStrCenter', '');
		jsPrintSetup.setOption('footerStrRight', '');
		// Suppress print dialog
		jsPrintSetup.setSilentPrint(true);/** Set silent printing */
		// Do Print
		jsPrintSetup.print();
		// Restore print dialog
		jsPrintSetup.setSilentPrint(true); /** Set silent printing back to false */
 
	}

	
	
}