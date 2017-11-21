


<html>
    <head>
        <script src="https://banamex.dialectpayments.com/checkout/version/41/checkout.js"
                data-complete="completeCallback"
                data-cancel="<?php echo $url_cancelar ?>">
        </script>
    </head>
    <body>

    <script type="text/javascript">
        
        function completeCallback(resultIndicator, sessionVersion) {
            // alert(resultIndicator);
        }

        Checkout.configure({
            merchant: "TEST1060709",
            order: {
                amount: "<?php echo $importe_total ?>",
                currency: "MXN",
                description: "Paquete de libros / cuadernos para ciclo escolar <?php echo $ciclo_escolar ?>",
                id: "<?php echo $pedido_id ?>"
            },
            interaction: {
                merchant: {
                    name: "Comercializadora Colegios México",
                    address: {
                        line1: "San Borja 1011, Col.Del Valle",
                        line2: "Delegación México, Ciudad de México DF"            
                    }    
                },
                displayControl: {
                    billingAddress  : "HIDE",
                    customerEmail   : "HIDE",
                    orderSummary    : "READ_ONLY",
                    shipping        : "HIDE"
                }
            },
            session: {
                id: "<?php echo $session_id ?>"
            }
        });

       Checkout.showPaymentPage();
    </script>

</html>
