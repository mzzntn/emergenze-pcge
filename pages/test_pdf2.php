<html>
	<!--script src='../vendor/Print.js-1.0.61/src/js/print.js'></script-->
	  <script src='https://printjs-4de6.kxcdn.com/print.min.js'></script>

	<script type="text/javascript">


function printPdfWithModal() {
      printJS({
        printable: './emergenze/pages/nuova_segnalazione.php',
        type: 'pdf',
        showModal: true
      })
    }
    
	</script>


	<body>

      <h1>Print.js Test Page</h1>

        <button onClick='printPdfWithModal();'>
          Print PDF
        </button>

  </body>
</html>
  