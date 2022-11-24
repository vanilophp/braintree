<form id="payment-form" action="{{ $url }}" method="post">
    <div id="dropin-container"></div>
    <input type="hidden" id="nonce" name="nonce"/>
    <input type="submit" value="Pay"/>
</form>

<div id="dropin-container"></div>
<script src="https://js.braintreegateway.com/web/dropin/1.33.7/js/dropin.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.88.4/js/data-collector.min.js"></script>

<script type="text/javascript">
      const form = document.getElementById('payment-form');

      braintree.dropin.create({
          authorization: @json($clientToken),
          container: '#dropin-container'
      }).then((dropinInstance) => {
          form.addEventListener('submit', (event) => {
              event.preventDefault();

              dropinInstance.requestPaymentMethod().then((payload) => {
                  document.getElementById('nonce').value = payload.nonce;
                  form.submit();
              }).catch((error) => { throw error; });
          });
      }).catch((error) => {
          // handle errors
      });
</script>
