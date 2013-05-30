<?php

//
// Example of use for InvoiceFox library
//

require '../bin/invfoxAPI/lib/strpcapi.php';
require '../bin/invfoxAPI/lib/invfoxapi.php';


// we define a callback that is called when paypal payment is confirmed

class MyPayPal extends PayPalIPNPlus {

  function xcb_payment_made($idSales) {
		
    logPaypalIPN("xcb_payment_made entered");
			
    $head = $GLOBALS['cartPurchase']->getOrderHead($idSales);
    $body = $GLOBALS['cart']->getOrderBodyWithDL($idSales);

    logPaypalIPN("INVFOX::got all data");


    $api = new InvfoxAPI(INVFOX_API_KEY, INVFOX_API_DOMAIN, true);
			
    $r = $api->assurePartner(array(
				   'name' => $head['name']. " " . $head['surname'] . 
				   ($head['company'] ? ", " . $head['company'] : ""),
				   'street' => $head['address'],
				   'postal' => $head['zip'],
				   'city' => $head['city'],
				   'vatid' => '',
				   'phone' => '123',
				   'website' => '',
				   'email' => $head['email'],'',
				   'notes' => '',
				   'vatbound' => 0,
				   'custaddr' => '',
				   'payment_period' => 14,
				   'street2' => ''
				   ));
			
    logPaypalIPN("INVFOX::assured partner");

    if ($r->isOk()) {
			
      logPaypalIPN("INVFOX::befpre create invoice");

      $clientIdA = $r->getData();
      $clientId = $clientIdA[0]['id'];

      $date1 = toUSDate($head['date_time']);
      $invid = str_pad($head['id'], 4, "0", STR_PAD_LEFT);

      $body2 = array();
      foreach ( $body as $bl ) {
	$body2[] = array(
			 'title' => $bl['item_name'],
			 'qty' => $bl['qty'],
			 'mu' => 'piece',
			 'price' => $bl['item_price'],
			 'vat' => $head['tax'],
			 'discount' => 0
			 );
      }

      logPaypalIPN("INVFOX::before create invoice call");

      $r2 = $api->createInvoice(
				array(
				      'title' => $invid,
				      'date_sent' => $date1,
				      'date_to_pay' => $date1,
				      'id_partner' => $clientId,
				      ),
				$body2
				);

      logPaypalIPN("INVFOX::after create invoice");

      if ($r2->isOk()) {

	logPaypalIPN("INVFOX::before downloadyy PDF");

	$invIdA = $r2->getData();
	$invId = $invIdA[0]['id'];
	$api->downloadPDF($invId);
	logPaypalIPN("INVFOX::downloaded PDF");
      }

    }

  }

}

function toUSDate($d) {
  if (strpos($d, "-") > 0) {
    $da = explode(" ", $d);
    $d1 = explode("-", $da[0]);
    return $d1[1]."/".$d1[2]."/".$d1[0];
  } else {
    return $d;
  }
}

?>
