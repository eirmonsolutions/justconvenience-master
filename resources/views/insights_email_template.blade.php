<!DOCTYPE html>
<html>
  <head>
      <title>Just Convenience</title>
  </head>
  <body>
      <p>Hola</p>
      <p>Today's Number of customers: {{ $params['todayCustomerCount'] }}</p>
      <p>Today's Total Amount of receipts: {{ $params['todayTotalAmount'] }}</p>
      <p>Today's Total Amount of tickets: {{ $params['todayTicketsCount'] }}</p>
      <p>Today's Highest amount of receipts of shop: {{ isset($params['max_invoice']['invoice_amount']) ? $params['max_invoice']['invoice_amount'] : '' }} ({{ isset($params['max_invoice']->shop->shop_name) ? $params['max_invoice']->shop->shop_name : '' }})</p>
      <p>Today's Highest amount of customer of shop: {{ isset($params['max_customers']['customer_count']) ? $params['max_customers']['customer_count'] : '' }} ({{ isset($params['max_customers']->shop->shop_name) ? $params['max_customers']->shop->shop_name : '' }})</p>
  </body>
</html>